<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'siga' => [
        // Endereço público do SIGA (onde o navegador faz o login). Vazio: SSO desligado (dev local).
        'url' => env('SIGA_URL'),
        // Código deste sistema no cadastro siga.sistema (vira o client_id OIDC).
        'cliente' => env('SIGA_CLIENTE', 'recrutamento'),
        // Provedor OIDC pela rede interna (troca do código pelo token e consulta de quem entrou).
        'oidc_interno' => env('SIGA_OIDC_INTERNO'),
        // API do SIGA pela rede interna (ex.: fuso do município, na Fase 2).
        'api_interno' => env('SIGA_API_INTERNO'),
        // Token com que o SIGA chama a API de provisionamento (/siga/api). Vazio: a API não existe.
        'provisionamento_token' => env('SIGA_PROVISIONAMENTO_TOKEN'),
    ],

];
