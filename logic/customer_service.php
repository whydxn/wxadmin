<?php

include "c:/vendor/autoload.php";
use EasyWeChat\Factory;

$from_openid = $_GET['openid'];
$nickname = $_GET['nickname'];
$content = $_GET['content'];
$options = [
    'app_id'    => 'wx6db36cbd7e005897',
    'secret'    => '934824280622d1dd24a7779df95d14ff',
    'token'     => '123456789',
    'aes_key' => '',                    // EncodingAESKey，兼容与安全模式下请一定要填写！！！

    'log' => [
        'level' => 'debug',
        'file'  =>  __DIR__.'/wechat.log',
    ],
];

$app = Factory::officialAccount($options);

$server = $app->server;
$user = $app->user;

$app->customer_service->message("From {$nickname}:\n-----------\n {$content}")->to('oA9Ks0s7dB4-V-NserRBszdxV7Jg')->send();

?>