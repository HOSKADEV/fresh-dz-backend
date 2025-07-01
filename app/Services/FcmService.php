<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FcmService
{
    private $serviceAccountPath;
    private $serviceAccount;
    private $projectId;
    private $fcmUrl;

    public function __construct()
    {
        $this->serviceAccountPath = base_path(env('GOOGLE_APPLICATION_CREDENTIALS'));
        $this->loadServiceAccount();
        $this->projectId = $this->serviceAccount['project_id'];
        $this->fcmUrl = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
    }

    /**
     * Load and parse service account JSON file
     */
    private function loadServiceAccount()
    {
        if (!file_exists($this->serviceAccountPath)) {
            throw new \Exception("Service account file not found: {$this->serviceAccountPath}");
        }

        $content = file_get_contents($this->serviceAccountPath);
        $this->serviceAccount = json_decode($content, true);

        if (!$this->serviceAccount) {
            throw new \Exception("Invalid service account JSON file");
        }

        // Validate required fields
        $requiredFields = ['project_id', 'private_key', 'client_email', 'token_uri'];
        foreach ($requiredFields as $field) {
            if (!isset($this->serviceAccount[$field])) {
                throw new \Exception("Missing required field '{$field}' in service account file");
            }
        }
    }

    /**
     * Send notification to a single device
     */
    public function sendToDevice($token, $title, $body, $data = ['key' => 'value'])
    {
        $message = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body
                ],
                'data' => $this->convertDataToStrings($data),
                'android' => [
                    'priority' => 'high',
                    'notification' => [
                        'sound' => 'default',
                        'channel_id' => 'fresh_dz_channel'
                    ]
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                            'badge' => 1
                        ]
                    ]
                ]
            ]
        ];

        return $this->sendRequest($message);
    }

    /**
     * Send notification to multiple devices (using multicast)
     */
    public function sendToMultipleDevices($tokens, $title, $body, $data = ['key' => 'value'])
    {
        $results = [];

        // FCM v1 doesn't support multicast directly, so we send individual messages
        // For better performance, you could use batch requests or async processing
        foreach ($tokens as $token) {
            $result = $this->sendToDevice($token, $title, $body, $data);
            $results[] = [
                'token' => $token,
                'result' => $result
            ];
        }

        return [
            'success' => true,
            'message' => 'Batch notifications processed',
            'results' => $results
        ];
    }

    /**
     * Send notification to a topic
     */
    public function sendToTopic($topic, $title, $body, $data = ['key' => 'value'])
    {
        $message = [
            'message' => [
                'topic' => $topic,
                'notification' => [
                    'title' => $title,
                    'body' => $body
                ],
                'data' => $this->convertDataToStrings($data),
                'android' => [
                    'priority' => 'high',
                    'notification' => [
                        'sound' => 'default',
                        'channel_id' => 'fresh_dz_channel'
                    ]
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                            'badge' => 1
                        ]
                    ]
                ]
            ]
        ];

        return $this->sendRequest($message);
    }

    /**
     * Send data-only message (silent notification)
     */
    public function sendDataMessage($token, $data)
    {
        $message = [
            'message' => [
                'token' => $token,
                'data' => $this->convertDataToStrings($data),
                'android' => [
                    'priority' => 'high'
                ]
            ]
        ];

        return $this->sendRequest($message);
    }

    /**
     * Send notification with condition
     */
    public function sendToCondition($condition, $title, $body, $data = ['key' => 'value'])
    {
        $message = [
            'message' => [
                'condition' => $condition,
                'notification' => [
                    'title' => $title,
                    'body' => $body
                ],
                'data' => $this->convertDataToStrings($data)
            ]
        ];

        return $this->sendRequest($message);
    }

    /**
     * Send notification with custom message payload
     */
    public function sendCustomMessage($messagePayload)
    {
        $message = [
            'message' => $messagePayload
        ];

        return $this->sendRequest($message);
    }

    /**
     * Get service account information
     */
    public function getServiceAccountInfo()
    {
        return [
            'project_id' => $this->serviceAccount['project_id'],
            'client_email' => $this->serviceAccount['client_email'],
            'client_id' => $this->serviceAccount['client_id'] ?? null,
            'private_key_id' => $this->serviceAccount['private_key_id'] ?? null,
            'auth_uri' => $this->serviceAccount['auth_uri'] ?? null,
            'token_uri' => $this->serviceAccount['token_uri'] ?? null,
            'universe_domain' => $this->serviceAccount['universe_domain'] ?? 'googleapis.com',
            'type' => $this->serviceAccount['type'] ?? 'service_account'
        ];
    }

    /**
     * Get project ID from service account
     */
    public function getProjectId()
    {
        return $this->projectId;
    }
    private function getAccessToken()
    {
        // Cache the token for 50 minutes (tokens expire after 1 hour)
        return Cache::remember('fcm_access_token', 50 * 60, function () {
            try {
                $now = time();
                $payload = [
                    'iss' => $this->serviceAccount['client_email'],
                    'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                    'aud' => $this->serviceAccount['token_uri'],
                    'iat' => $now,
                    'exp' => $now + 3600
                ];

                $jwt = $this->createJWT($payload, $this->serviceAccount['private_key']);

                $response = Http::asForm()->post($this->serviceAccount['token_uri'], [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['access_token'];
                }

                throw new \Exception('Failed to get access token: ' . $response->body());
            } catch (\Exception $e) {
                Log::error('FCM access token error', ['error' => $e->getMessage()]);
                throw $e;
            }
        });
    }

    /**
     * Create JWT token for OAuth
     */
    private function createJWT($payload, $privateKey)
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'RS256']);
        $payload = json_encode($payload);

        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        $signature = '';
        openssl_sign($base64Header . '.' . $base64Payload, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64Header . '.' . $base64Payload . '.' . $base64Signature;
    }

    /**
     * Convert data array values to strings (FCM requirement)
     */
    private function convertDataToStrings($data)
    {
        $stringData = [];
        foreach ($data as $key => $value) {
            $stringData[$key] = is_string($value) ? $value : json_encode($value);
        }
        return $stringData;
    }

    /**
     * Make HTTP request to FCM v1 API
     */
    private function sendRequest($payload)
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->fcmUrl, $payload);

            $result = $response->json();

            if ($response->successful()) {
                Log::info('FCM notification sent successfully', [
                    'response' => $result
                ]);

                return [
                    'success' => true,
                    'message' => 'Notification sent successfully',
                    'data' => $result
                ];
            } else {
                Log::error('FCM notification failed', [
                    'status' => $response->status(),
                    'response' => $result
                ]);

                return [
                    'success' => false,
                    'message' => 'Failed to send notification',
                    'error' => $result,
                    'status' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('FCM notification exception', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Exception occurred while sending notification',
                'error' => $e->getMessage()
            ];
        }
    }
}
