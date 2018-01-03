<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/2
 * Time: 17:19
 */
include "c:/vendor/autoload.php";

use EasyWeChat\Factory;

$options = [
    // 必要配置
    'app_id'             => 'wx970b499222a1a820',
    'mch_id'             => '1485755392',
    'key'                => 'DF9A1609F910D80B383A5B8DB6084F45',   // API 密钥

    // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
 //   'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
 //   'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！

    'notify_url'         => 'http://123dfx.com/wxpay/notify.php',     // 你也可以在下单时单独设置来想覆盖它
];

$payment = Factory::payment($options);

$response = $payment->handlePaidNotify(function ($message, $fail) {
	file_put_contents("result.txt", "pay success!");
    // 你的逻辑
    return true;
    // 或者错误消息
});

$response->send();

?>