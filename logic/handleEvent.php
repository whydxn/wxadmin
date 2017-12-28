<?php

include "c:/vendor/autoload.php";
use EasyWeChat\Factory;

$from_openid = $_GET['openid'];
$event =$_GET['event'];
$eventkey =$_GET['key'];
$to_openid ="";
$nickname = "";
$content = "";

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

$eventtype = substr($eventkey,0,5);
if($eventtype == 'V1001')
{
    handleCustomerEvent();
}
else
{
	handleJiedanEvent();
}

function handleCustomerEvent()
{
	if($GLOBALS['eventkey'] == "V1001_CUSTOMER_STATE")
	{
		if(is_file("data/talktmp/{$GLOBALS['from_openid']}"))
		{
			$jiedan_id = file_get_contents("data/talktmp/{$GLOBALS['from_openid']}"); 
			$GLOBALS['content'] = "当前正在与[$jiedan_id]连接中！";
			$GLOBALS['to_openid'] = $GLOBALS['from_openid'];
			$GLOBALS['nickname'] = "系统管理";
		}
		else
		{
			$GLOBALS['content'] = "未连接状态！";
			$GLOBALS['to_openid'] = $GLOBALS['from_openid'];
			$GLOBALS['nickname'] = "系统管理";
		}
		sendmsg();
	}
}

function handleJiedanEvent()
{
	$indexid = file_get_contents("data/jiedaninfo/{$GLOBALS['from_openid']}/indexid");
	switch($GLOBALS['eventkey']){
		case 'V1002_JIEDAN_STATE':
		    if(is_file("data/online/$indexid"))
			{
				if(!is_file("data/talktmp/{$GLOBALS['from_openid']}"))
				{
					$GLOBALS['content'] = "在线状态\n没有对话";
					$GLOBALS['to_openid'] = $GLOBALS['from_openid'];
					$GLOBALS['nickname'] = "系统管理";
				}
				else
				{
					$customer_id = file_get_contents("data/talktmp/{$GLOBALS['from_openid']}"); 
					$GLOBALS['content'] = "在线状态\n当前正在与[顾客:$customer_id]对话中！";
					$GLOBALS['to_openid'] = $GLOBALS['from_openid'];
					$GLOBALS['nickname'] = "系统管理";
				}
	        }
			else
			{
				$GLOBALS['content'] = "离线状态!";
				$GLOBALS['to_openid'] = $GLOBALS['from_openid'];
				$GLOBALS['nickname'] = "系统管理";
			}
			break;
		case 'V1002_JIEDAN_ON':
			if(is_file("data/online/$indexid"))
			{
				$GLOBALS['content'] = "当前已经是在线状态！不需要重复上线.";
				$GLOBALS['to_openid'] = $GLOBALS['from_openid'];
				$GLOBALS['nickname'] = "系统管理";
			}
			else
			{
				file_put_contents("data/online/$indexid", 'online');
				$GLOBALS['content'] = "上线成功，请刷新在线妹子查看！";
				$GLOBALS['to_openid'] = $GLOBALS['from_openid'];
				$GLOBALS['nickname'] = "系统管理";
			}
			break;
		case 'V1002_JIEDAN_OFF':
			if(!is_file("data/online/$indexid"))
			{
				$GLOBALS['content'] = "当前处于离线状态，不需要重复下线.";
				$GLOBALS['to_openid'] = $GLOBALS['from_openid'];
				$GLOBALS['nickname'] = "系统管理";
			}
			else
			{
				if(is_file("data/talktmp/{$GLOBALS['from_openid']}"))
				{
					$GLOBALS['content'] = "当前正在对话中,请先断开连接！";
					$GLOBALS['to_openid'] = $GLOBALS['from_openid'];
					$GLOBALS['nickname'] = "系统管理";
				}
				else
				{
					if(unlink("data/online/$indexid"))
					{
						$GLOBALS['content'] = "下线成功，当前处于离线状态！";
					}
					$GLOBALS['to_openid'] = $GLOBALS['from_openid'];
					$GLOBALS['nickname'] = "系统管理";
				}
			}
		    break;
		case 'V1002_JIEDAN_DISCONNECT':
		    if(is_file("data/talktmp/{$GLOBALS['from_openid']}"))
			{
				$customer_openid = file_get_contents("data/talktmp/{$GLOBALS['from_openid']}");
				unlink("data/talktmp/$customer_openid");
				unlink("data/talktmp/{$GLOBALS['from_openid']}");
				$GLOBALS['content'] = "与[$customer_openid]的连接已经断开.";
				$GLOBALS['to_openid'] = $GLOBALS['from_openid'];
				$GLOBALS['nickname'] = "系统管理";
				sendmsg();
				
				$GLOBALS['content'] = "与[妹子:$indexid]的连接已经断开.";
				$GLOBALS['to_openid'] = $customer_openid;
				$GLOBALS['nickname'] = "系统管理";
				
			}
			else
			{
				$GLOBALS['content'] = "当前并没有连接，不需要断开！";
				$GLOBALS['to_openid'] = $GLOBALS['from_openid'];
				$GLOBALS['nickname'] = "系统管理";
			}
			break;
	}
	sendmsg();
}

function sendmsg()
{
    $GLOBALS['app']->customer_service->message("From {$GLOBALS['nickname']}:\n-----------\n {$GLOBALS['content']}")->to($GLOBALS['to_openid'])->send();
}

?>