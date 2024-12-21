<?php

return [
    'credentials' => json_decode(file_get_contents(base_path('chargily-pay-credentials.json')), true),
];
