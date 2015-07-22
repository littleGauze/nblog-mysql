<?php
require 'utils/Medoo.class.php';
require 'utils/DbCriteria.class.php';
require 'models/Model.class.php';
/*
	数据库操作API主要入口
*/
function MainPort($params){
	
	$type = $params['type'];
	switch ($type){
		case 'users':
			
			//用户相关处理
			require_once 'models/Users.class.php';
			$users = new Users();
			
			$action = isset($params['action'])?$params['action']:"";
			$username = isset($params['username'])?$params['username']:"";
			$userpass = isset($params['userpass'])?$params['userpass']:"";
			
			if(!empty($action) && !empty($username) && !empty($userpass)){
				
				switch ($action){
					case 'LOGIN':
						
						//查询用户
						$userinfo = $users->findUserByName($username);
						if($userinfo){
							if($userinfo['user_pass'] == md5($userpass)){
								returnMsg(200, '登陆成功！', array('userinfo'=>$userinfo));
							}else{
								returnMsg(201, '密码错误！');
							}
						}else{
							returnMsg(406, '用户名不存在!');
						}
						
						break;
					case 'REGISTER':
						break;
					default: 
						break;
				}
				
			}else{
				returnMsg(405, '参数错误!');
			}
			$params = array(
				'username'=> $username,
				'userpass'=> $userpass
			);
			
			/* $rs = $users->register($params);
			if($rs){
				returnMsg(200, '注册成功！have fun'); 
			} */
			break;
		default: 
			break;
	}
}