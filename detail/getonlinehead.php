<?php
header('Content-Type: application/json');
header('Content-Type: text/html;charset=utf-8');

$one = ['100','内容描述'];
$ones = [$one];
$result=array(
              'count'=>1,
			  'page'=>1,
              'data'=>$ones   
            );
echo json_encode($result, JSON_UNESCAPED_UNICODE);


?>