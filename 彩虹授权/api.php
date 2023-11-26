<?php
$mod='blank';
include("api.inc.php");
if($url=$_GET['url']) {
	if(checkauth2($url)){
		$result=array("code"=>1,"msg"=>"正版授权");
		}else{
		$result=array("code"=>-1,"msg"=>"暂未授权");
	}
}
else
{
	$result=array("code"=>-5,"msg"=>"No Act!");
}

echo json_encode($result);

?>