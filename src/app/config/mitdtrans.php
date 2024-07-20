<?php

use Illuminate\Support\ServiceProvider;

return [
  'midtrans' => [
      'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
      'server_key' => env('MIDTRANS_SERVER_KEY'),
      'client_key' => env('MIDTRANS_CLIENT_KEY'),
  ],
];