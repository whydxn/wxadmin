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

    'notify_url'         => 'http://123dfx.com/wxpay/notify.php',     // 你也可以在下单时单独设置来想覆盖它
];

$payment = Factory::payment($options);

$product = [
    'trade_type'       => 'NATIVE', // 微信公众号支付填JSAPI,扫码支付为native
    'body'             => '一盒火柴',
    'detail'           => '一盒火些',
    'out_trade_no'     => 'MYERPORDERID12345678', // 这是自己ERP系统里的订单ID，不重复就行。
    'total_fee'        => 10, // 金额，这里的8888分人民币。单位只能是分。
    'notify_url'       => 'http://123dfx.com/wxpay/notify.php', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
	'spbill_create_ip'   => '123.12.12.123'
];

$result = $payment->order->unify($product); // 这里的order是上面一步得来的。 这个prepare()帮你计算了校验码，帮你获取了prepareId.省心。
$prepayId = null;
if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS'){
    $prepayId = $result['prepay_id']; // 这个很重要。有了这个才能调用支付。
    $codeUrl = $result['code_url'];
	var_dump($codeUrl);
} else {
    var_dump($result);
    die("出错了。");  // 出错就说出来，不然还能怎样？
}

?>