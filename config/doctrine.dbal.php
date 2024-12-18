<?php

return [
  'connections' => [

    'mysql' => [
      'driver' => 'mysql',
      'host' => env('DB_HOST', '127.0.0.1'),
      'port' => env('DB_PORT', '3306'),
      'database' => env('DB_DATABASE', 'forge'),
      'user' => env('DB_USERNAME', 'forge'),
      'password' => env('DB_PASSWORD', ''),
    ]

  ],
];
