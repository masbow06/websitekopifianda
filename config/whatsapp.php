<?php

return [
    'api_url' => env('WHATSAPP_API_URL', "https://api.fonnte.com/send"),
    'api_key' => env('WHATSAPP_API_KEY', 'key'),
    'owner_phone_number' => env('WHATSAPP_OWNER_PHONE_NUMBER', '6281234567890'),
    'admin_phone_number' => env('WHATSAPP_ADMIN_PHONE_NUMBER', '6281234567890'),
];