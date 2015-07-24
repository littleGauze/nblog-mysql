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
			
			if(!empty($action)){
				
				switch ($action){
					case 'LOGIN':
						$username = isset($params['username'])?$params['username']:"";
						$userpass = isset($params['userpass'])?$params['userpass']:"";
						
						if(empty($username) && empty($userpass)){
							returnMsg(405, '参数错误!');
						}
						
						//查询用户
						$userinfo = $users->findUserByName($username);
						if($userinfo){
							if($userinfo['user_pass'] == md5($userpass)){
								returnMsg(200, '登陆成功！正在跳转...', array('userinfo'=>$userinfo));
							}else{
								returnMsg(201, '密码错误！');
							}
						}else{
							returnMsg(406, '用户名不存在!');
						}
						
						break;
					case 'REGISTER':
						
						$username = isset($params['username'])?$params['username']:"";
						$userpass = isset($params['userpass'])?$params['userpass']:"";
						
						if(empty($username) && empty($userpass)){
							returnMsg(405, '参数错误!');
						}
						
						$params = array(
								'username'=> $username,
								'userpass'=> $userpass
						);
							
						$rs = $users->register($params);
						 if($rs){
							returnMsg(200, '注册成功！have fun');
						}else{
							returnMsg(201, '注册失败，请重试！');
						}
						break;
					case 'SAVEBASEINFO';
						$username = isset($params['username'])?$params['username']:'';
						if(!empty($username)){
							$rs = $users->saveBaseInfo($params);
							if($rs){
								//查询用户
								$userinfo = $users->findUserByName($username);
								
								returnMsg(200, '保存成功!', array('userinfo'=>$userinfo));
							}else{
								returnMsg(201, '保存失败!');
							}
						}else{
							returnMsg(405, '参数错误!');
						}
						break;
					default: 
						break;
				}
				
			}else{
				returnMsg(405, '参数错误!');
			}
			break;
		default: 
			break;
	}
}