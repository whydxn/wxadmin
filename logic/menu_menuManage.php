<?phpinclude "vendor/autoload.php";use EasyWeChat\Factory;$options = [    'app_id' => 'wx6db36cbd7e005897',    'secret' => '934824280622d1dd24a7779df95d14ff',    'token' => '123456789',    'aes_key' => '',                    // EncodingAESKey，兼容与安全模式下请一定要填写！！！    'log' => [        'level' => 'debug',        'file' => __DIR__ . '/wechat.log',    ],];$app = Factory::officialAccount($options);$server = $app->server;$user = $app->user;if ($_SERVER["REQUEST_METHOD"] == "GET") {    $operation = test_input($_GET["operation"]);    if(isset($_GET["param"])){        $param = test_input($_GET["param"]);    }    switch ($operation) {        case 'showmenu':            getMenuListFromWX();            break;        case 'updatemenu':            updateAllMenu();            break;        default:            echo 'error';    }}function getMenuListFromWX(){    $list = $GLOBALS['app']->menu->list();    echo json_encode($list, JSON_UNESCAPED_UNICODE);}function updateAllMenu(){    $common_buttons =     [        [            "type" => "click",            "name" => "客户指导",            "key"  => "V1001_CUSTOMER_GUIDE"        ],        [            "name"       => "菜单",            "sub_button" =>[                [                    "type" => "click",                    "name" => "我的状态",                    "key"  => "V1001_CUSTOMER_STATE"                ],                [                    "type" => "view",                    "name" => "在线妹子",                    "url"  => "http://123dfx.com/detail/browser.html"                ],                [                    "type" => "click",                    "name" => "断开当前通话",                    "key"  => "V1001_CUSTOMER_DISCONNECT"                ]            ]        ],        [            "type" => "click",            "name" => "下单",            "key"  => "V1001_PAY"        ]    ];        $jiedan_buttons =     [        [            "type" => "click",            "name" => "使用指导",            "key"  => "V1002_JIEDAN_GUIDE"        ],        [            "name"       => "菜单",            "sub_button" =>[                [                    "type" => "click",                    "name" => "我的状态",                    "key"  => "V1002_JIEDAN_STATE"                ],                [                    "type" => "view",                    "name" => "在线妹子",                    "url"  => "http://123dfx.com/detail/browser.html"                ],                [                    "type" => "click",                    "name" => "上线接单",                    "key"  => "V1002_JIEDAN_ON"                ],                [                    "type" => "click",                    "name" => "下线",                    "key"  => "V1002_JIEDAN_OFF"                ],                [                    "type" => "click",                    "name" => "断开当前通话",                    "key"  => "V1002_JIEDAN_DISCONNECT"                ]            ]        ]    ];    $matchRule =     [    "tag_id" => 101,    ];        $GLOBALS['app']->menu->delete();    $GLOBALS['app']->menu->create($common_buttons);    $GLOBALS['app']->menu->create($jiedan_buttons, $matchRule);}function test_input($data) {    $data = trim($data);    $data = stripslashes($data);    $data = htmlspecialchars($data);    return $data;}?>