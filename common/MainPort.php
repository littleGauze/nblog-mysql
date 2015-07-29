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
					case 'CHECKUSERNAME':
						$username = isset($params['username'])?$params['username']:"";
						
						if(!empty($username)){
							$userinfo = $users->findUserByName($username);
							if($userinfo){
								returnMsg(201, '用户名已被使用，请换一个!');
							}else{
								returnMsg(200, '用户名可以使用!');
							}
							
						}else{
							returnMsg(405, '参数错误!');
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
		case 'posts':
			
			//处理帖子相关
			require_once 'models/Posts.class.php';
			require_once 'models/Users.class.php';
			require_once 'models/Messages.class.php';
			$posts = new Posts();
			$msg = new Messages();
			
			$action = isset($params['action'])?$params['action']:"";
			if(!empty($action)){
				switch($action){
					case 'PUBLISH':
						
						$username = isset($params['username'])?$params['username']:"";
						$key = isset($params['key'])?$params['key']:"";
						$desc = isset($params['desc'])?$params['desc']:"";
						
						if(!empty($username) && !empty($key)){
							
							$data = array(
								'username'=>$username,
								'key'=>$key
							);
							
							$rs = $posts->publish($data);
						 	if($rs){
						 		
						 		//如果desc不为空则添加一条评论
						 		if(!empty($desc)){
						 			
						 			$msginfo = array(
						 				'type'=>2,
						 				'content'=>$desc,
						 				'ref'=>$rs,
						 				'from'=>$username
						 			);
						 			//添加一条评论
						 			$msg->saveMessage($msginfo);
						 		}
						 		
								returnMsg(200, '发表成功!');
							}else{
								returnMsg(201, '发表失败！');
							}
						}else{
							returnMsg(405, '参数错误!');
						}
						break;
					case 'MYPOSTS':
						$username = isset($params['username'])?$params['username']:"";
						$page = isset($params['page'])?$params['page']:1;
						$limit = isset($params['limit'])?$params['limit']:10;
						if(!empty($username)){
							$rs = $posts->findPostsByUname($username, $page, intval($limit));
							
							if($rs){
								returnMsg(200, '查询成功!', array('posts'=>$rs));
							}else{
								returnMsg(201, '查询失败！');
							}
						}else{
							returnMsg(405, '参数错误!');
						}
						break;
					case 'ALLPOSTS':
						$page = isset($params['page'])?$params['page']:1;
						$limit = isset($params['limit'])?$params['limit']:20;
						
						$rs = $posts->findAllPosts($page, intval($limit));
							
						if($rs){
							returnMsg(200, '查询成功!', array('posts'=>$rs));
						}else{
							returnMsg(201, '查询失败！');
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