<?php
/**
 * api config
 */

return [
    // api跨域设置
    'cross_domain' => [
        // 是否允许跨域
        'allow' => env('api.allow_cross_domain', true),
        // header设置
        'header' => [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => '*',
            'Access-Control-Allow-Headers' => 'Content-Type,' . (env('api.token_position') === 'header' ? env('api.token_field') : 'token'),
            'Access-Control-Request-Headers' => 'Origin, Content-Type, Accept, ' . (env('api.token_position') === 'header' ? env('api.token_field') : 'token'),
        ],
    ],
    // api相应配置
    'response' => [
        // HTTP状态码和业务码同步
        'http_code_sync' => env('api.http_code_sync', false),
    ],
    // jwt认证
    'auth' => [
        'jwt_key' => env('api.jwt_key', 'q855kaCUnX0miKmgbTtijV1hbUFycBlGt1qBuoas'),
        'jwt_exp' => (int)env('api.jwt_exp', 86400000),
        'jwt_aud' => env('api.jwt_aud', 'server'),
        'jwt_iss' => env('api.jwt_iss', 'client'),
        'enable_refresh_token' => (bool)env('api.enable_refresh_token', true),
        'refresh_exp' => (int)env('api.refresh_exp', 1296000),
        'reuse_check' => (bool)env('api.reuse_check', true),
        'token_position' => env('api.token_position', 'header'),
        'token_field' => env('api.token_field', 'token'),
    ]
];