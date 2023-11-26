<?php
include("./api.inc.php");
if ($confs['template_index'] && file_exists("./template/{$confs['template_index']}/dlcx.php")){
	$index_template = $confs['template_index'];
}else{
	$index_template = "default";
}
include("./template/{$index_template}/dlcx.php");
?>