<?php
header('Content-Type: application/json');
header('Content-Type: text/html;charset=utf-8');

$indexlist = glob('../logic/data/online/*');
$indexcount = count($indexlist);

$ones = array();
$ones['count'] = $indexcount;
$ones['page'] = 1;
$ones['data'] = array();

foreach(glob('../logic/data/online/*') as $single){ 
	$indexid = basename($single);
	$one = array();
	$one[0] = $indexid;
	$one[1] = file_get_contents("../logic/data/jiedaninfo/$indexid/job");
	array_push($ones['data'], $one);
}

echo json_encode($ones, JSON_UNESCAPED_UNICODE);

/*$one = ['100','内容描述'];
$ones = [$one];
$result=array(
              'count'=>1,
			  'page'=>1,
              'data'=>$ones   
            );
echo json_encode($result, JSON_UNESCAPED_UNICODE);
*/

?>