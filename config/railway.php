<?php
return [
    'app' => [
        'name' => 'DIWJIB',
        'env' => 'production',
        'key' => 'base64:fkhiKAjskaqWPNQ6E7DyJHptxAWAneMvZ/kzHrFExq4=',
        'debug' => true,
        'url' => 'https://diwjib-production.up.railway.app',
    ],
    'database' => [
        'connection' => 'mongodb',
        'uri' => 'mongodb+srv://diwjib_db_pedrojesam:6v8UckuxDHlqrHtA@pedrojesam-diwjib.yuayso9.mongodb.net/diwjib?retryWrites=true&w=majority',
    ],
    'services' => [
        'ors_api_key' => 'eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6ImE0MWU3YTY5Mzk5MDQ0NzQ5NGFlMTY3MWIzZjQ4YzM4IiwiaCI6Im11cm11cjY0In0=',
        'recaptcha_site_key' => '6LeptIkrAAAAAO9FsN3Zh_P08aSv5xMGAnQrGhHe',
        'recaptcha_secret_key' => '6LeptIkrAAAAAAlsJpdZbQ5v7Tldog2rs6bOyTq1',
        'admin_access_code' => '12345',
    ],
];