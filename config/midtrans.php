<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Midtrans Environment
    |--------------------------------------------------------------------------
    |
    | The environment for Midtrans can either be 'sandbox' or 'production'.
    | Set to 'sandbox' for testing purposes and 'production' when you're ready to go live.
    |
    */

    'environment' => env('MIDTRANS_ENVIRONMENT', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | Midtrans API Keys
    |--------------------------------------------------------------------------
    |
    | Your Midtrans API keys for accessing the payment gateway. You should store
    | your `server_key` and `client_key` in your .env file for security reasons.
    |
    */

    'server_key' => env('MIDTRANS_SERVER_KEY', 'your-midtrans-server-key'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'your-midtrans-client-key'),

    /*
    |--------------------------------------------------------------------------
    | Midtrans Notifications URL
    |--------------------------------------------------------------------------
    |
    | The URL that Midtrans will use to send notification callbacks to your server.
    | You can set it to any route in your application that handles the payment callback.
    |
    */

    'notification_url' => env('MIDTRANS_NOTIFICATION_URL', 'https://yourdomain.com/midtrans/notification'),
];
