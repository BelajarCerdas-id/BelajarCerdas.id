<?php

return  [
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'is_production' => false, // ganti true kalau sudah production
    'sanitized' => true,
    '3ds' => true,
];