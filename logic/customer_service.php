<?php

include "c:/vendor/autoload.php";
use EasyWeChat\Factory;

$from_openid = $_GET['openid'];
$to_openid ="";
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

if($content[0] == "@")
{
    handleATmsg();
}

function handleATmsg()
{
	$jiedan_id = substr($GLOBALS['content'],1,3);
	if(is_numeric($jiedan_id)){
		if(is_dir("data/jiedaninfo/$jiedan_id"))   //判断@xxx对应的数字id是否存在
		{
			$jiedan_openid = file_get_contents("data/jiedaninfo/$jiedan_id/openid");   //获取jiedan openid
			if(is_file("data/talktmp/$jiedan_openid"))
			{
				$GLOBALS['content'] = "这个妹子现在正跟别人聊天！请稍等吧！";
	            $GLOBALS['to_openid'] = $GLOBALS['from_openid'];   //回复信息给发送顾客
		        $GLOBALS['nickname'] = "系统管理";
			}
			else
			{
				$customer_openid = $GLOBALS['from_openid'];
				file_put_contents("data/talktmp/$jiedan_openid", $customer_openid);
				file_put_contents("data/talktmp/$customer_openid", $jiedan_openid);
				$GLOBALS['to_openid'] = $jiedan_openid;    //to对象指向jiedan
				$GLOBALS['content'] = "新的顾客请求与你交流！";
			}
		}
		else
		{
			$GLOBALS['content'] = "指定的妹子并不存在哦。请查看一下在线妹子！";
			$GLOBALS['to_openid'] = $GLOBALS['from_openid'];   //回复信息给发送顾客
			$GLOBALS['nickname'] = "系统管理";
		}
    }
	else
	{
		$GLOBALS['content'] = "输入格式不正确，请看指导！！！";
		$GLOBALS['to_openid'] = $GLOBALS['from_openid'];   //回复信息给发送顾客
		$GLOBALS['nickname'] = "系统管理";
	}
	sendmsg();
}

function sendmsg()
{
    $GLOBALS['app']->customer_service->message("From {$GLOBALS['nickname']}:\n-----------\n {$GLOBALS['content']}")->to($GLOBALS['to_openid'])->send();
}

?>