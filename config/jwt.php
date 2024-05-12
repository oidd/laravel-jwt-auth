<?php

return [
    'input_key' => env('AUTH_JWT_INPUT_KEY', 'api_token'),
    'storage_key' => env('AUTH_JWT_STORAGE_KEY', 'id'),
    'token_key' => env('AUTH_JWT_TOKEN_KEY', 'user_id'),
    'secret' => env('AUTH_JWT_SECRET_KEY', '')
];
