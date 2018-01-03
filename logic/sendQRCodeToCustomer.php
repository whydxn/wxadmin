<?php

include "c:/vendor/autoload.php";
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Messages\Image;

$indexid = $_GET['indexid'];

$to_openid ="";
$nickname = "";
$content = "";
$media_id ="";

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

$from_openid = file_get_contents("data/jiedaninfo/$indexid/openid");
$to_openid = file_get_contents("data/talktmp/{$from_openid}");

$result = $app->media->uploadImage("data/jiedaninfo/{$indexid}/qrcode.jpg");
$image = new Image($result['media_id']);
sendimage();

$shortname = substr($to_openid,-5);
$nickname = "系统管理";
$content = "支付码已经发送给[顾客:{$shortname}],请督促顾客付费!";
$to_openid = $from_openid;
sendmsg();

function sendmsg()
{
    $GLOBALS['app']->customer_service->message("From {$GLOBALS['nickname']}:\n-----------\n {$GLOBALS['content']}")->to($GLOBALS['to_openid'])->send();
}

function sendimage()
{
    $GLOBALS['app']->customer_service->message($GLOBALS['image'])->to($GLOBALS['to_openid'])->send();
}

?>