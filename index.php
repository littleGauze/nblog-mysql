<?php
session_start();
require './config/config.inc.php';
require './common/PublicFunction.php';
require './common/MainPort.php';

$method = 'get';
$auth = isset($_GET['auth'])?$_GET['auth']:"";

if(empty($auth)){
	$auth = isset($_POST['auth'])?$_POST['auth']:"";
	$method = 'post';
}

header("Content-Type: text/html;charset=utf8");

if(!empty($auth) && checkAuth($auth)){
	$params = array_merge($_POST, $_GET);
	MainPort($params);
	
}else{
	returnMsg(401, '秘钥验证失败!');
}