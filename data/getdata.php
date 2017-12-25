<?php
// 从文件中读取数据到PHP变量
$json_string = file_get_contents('user.json');
// 把JSON字符串转成PHP数组
echo $json_string
?>