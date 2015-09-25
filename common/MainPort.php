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
					case 'CHANGEPASS';
						$username = isset($params['username'])?$params['username']:'';
						$oldpass = isset($params['oldpassword'])?$params['oldpassword']:'';
						$newpass = isset($params['newpassword'])?$params['newpassword']:'';
						if(!empty($username) && !empty($oldpass) && !empty($newpass)){
							
							//查询旧密码是否正确
							$user = $users->findUserByName($username);
							
							if($user && $user['user_pass'] == md5($oldpass)){
								$rs = $users->changeUserPass($username, $newpass);
								
								if($rs){
									returnMsg(200, '修改密码成功!');
								}else{
									returnMsg(201, '修改密码失败!');
								}
								
							}else{
								returnMsg(202, '旧密码错误！');
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
			require_once 'models/Relations.class.php';
			$posts = new Posts();
			$rels = new Relations();
			$users = new Users();
			$msg = new Messages();
			
			$action = isset($params['action'])?$params['action']:"";
			
			if(!empty($action)){
				switch($action){
					case 'PUBLISH':
						
						$username = isset($params['username'])?$params['username']:"";
						$nick = isset($params['nick'])?$params['nick']:"";
						$key = isset($params['key'])?$params['key']:"";
						$desc = isset($params['desc'])?$params['desc']:"";
						
						if(!empty($username) && !empty($key)){
							
							$data = array(
								'username'=>$username,
								'key'=>$key,
								'desc'=>$desc
							);
							
							$rs = $posts->publish($data);
						 	if($rs){
						 		
						 		//如果desc不为空则添加一条评论
						 		if(!empty($desc)){
						 			
						 			$msginfo = array(
						 				'type'=>2,
						 				'content'=>$desc,
						 				'ref'=>$rs,
						 				'from'=>$username,
						 				'to'=>$username,
						 				'fnick'=>$nick,
						 				'tnick'=>$nick
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
						$fans = isset($params['fans'])?$params['fans']:"";
						$page = isset($params['page'])?$params['page']:1;
						$limit = isset($params['limit'])?$params['limit']:20;
						$fallow = false;
						if(!empty($username)){
							if(!empty($fans)){
								//查询关注状态
								$fallow = $rels->isFallowMe($fans, $username);
							}
							//查询用户信息
							$userinfo = $users->findUserByName($username);
							$rs = $posts->findPostsByUname($username, $page, intval($limit));
							
							if($rs){
								returnMsg(200, '查询成功!', array('posts'=>$rs, 'userinfo'=>$userinfo, 'fallow'=>$fallow));
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
						$exclude = isset($params['exclude'])?$params['exclude']:"";
						
						$rs = $posts->findAllPosts($page, intval($limit), $exclude);
						
						if($rs){
							returnMsg(200, '查询成功!', array('posts'=>$rs));
						}else{
							returnMsg(201, '查询失败！');
						}
						
						break;
					case 'FALLOWPOSTS':
						$page = isset($params['page'])?$params['page']:1;
						$limit = isset($params['limit'])?$params['limit']:20;
						$uname = isset($params['uname'])?$params['uname']:"";
						
						if(!empty($uname)){
							
							$rs = $posts->findFallowPosts($uname, $page, $limit);
							if($rs){
								returnMsg(200, '查询成功!', array('posts'=>$rs));
							}else{
								returnMsg(201, '查询失败！');
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
		case 'relations':
			//处理帖子相关
			require_once 'models/Relations.class.php';
			require_once 'models/Messages.class.php';
			$rels = new Relations();
			$msg = new Messages();
			
			$action = isset($params['action'])?$params['action']:"";
			
			if(!empty($action)){
				switch($action){
					case 'FALLOW':
						$user = isset($params['user'])?$params['user']:"";
						$me = isset($params['me'])?$params['me']:"";
						$nick = isset($params['nick'])?$params['nick']:"";
						
						$rs = $rels->fallowSomeone($user, $me);
						
						if($rs){
							//添加关注消息
							$fans = empty($nick)?$me:$nick;
							$datas = array(
								'type'=>1,
								'content'=> $fans.' 开始关注您了！',
								'from'=>$me,
								'fnick'=>'',
								'to'=>$user,
								'tnick'=>'',
								'ref'=>0
							);
							$msg->saveMessage($datas);
							
							returnMsg(200, '关注成功!');
						}else{
							returnMsg(201, '关注失败！');
						}
						break;
					case 'UNFALLOW':
						$user = isset($params['user'])?$params['user']:"";
						$me = isset($params['me'])?$params['me']:"";
						
						$rs = $rels->unfallowSomeone($user, $me);
						
						if($rs){
							//删除该用户的系统消息
							$msg->deleteMessage(1, $me, $user);
							
							returnMsg(200, '取消关注成功!');
						}else{
							returnMsg(201, '取消关注失败！');
						}
						
						break;
					case 'GETFRIENDS':
						$user = isset($params['user'])?$params['user']:"";
						$page = isset($params['page'])?$params['page']:1;
						$limit = isset($params['limit'])?$params['limit']:20;
						
						$start = ($page-1)*$limit;
						
						$rs = $rels->getFriends($user, $start, $limit);
						
						if($rs){
							returnMsg(200, '获取成功!', array('lists'=>$rs));
						}else{
							returnMsg(201, '获取失败！');
						}
						
						break;
					case 'FALLOWING'://所有关注我的用户
						$user = isset($params['user'])?$params['user']:"";
						$page = isset($params['page'])?$params['page']:1;
						$limit = isset($params['limit'])?$params['limit']:20;
					
						$start = ($page-1)*$limit;
					
						$rs = $rels->findAllFans($user, $start, $limit);
					
						if($rs){
							returnMsg(200, '获取成功!', array('lists'=>$rs));
						}else{
							returnMsg(201, '获取失败！');
						}
					
						break;
					case 'FALLOWED'://所有我关注的用户
						$user = isset($params['user'])?$params['user']:"";
						$page = isset($params['page'])?$params['page']:1;
						$limit = isset($params['limit'])?$params['limit']:20;
					
						$start = ($page-1)*$limit;
					
						$rs = $rels->findAllStars($user, $start, $limit);
					
						if($rs){
							returnMsg(200, '获取成功!', array('lists'=>$rs));
						}else{
							returnMsg(201, '获取失败！');
						}
					
						break;
					default: 
						break;
				}
			}else{
				returnMsg(405, '参数错误!');
			}
			//fallowSomeone
			
			break;
		case 'message':
			
			//处理消息相关
			require_once 'models/Users.class.php';
			require_once 'models/Relations.class.php';
			require_once 'models/Posts.class.php';
			require_once 'models/Messages.class.php';
			$users = new Users();
			$rels = new Relations();
			$msg = new Messages();
				
			$action = isset($params['action'])?$params['action']:"";
				
			if(!empty($action)){
				switch($action){
					case 'GETALL':
						$postid = isset($params['postid'])?$params['postid']:"";
						if($postid){
							
							//获取该帖子的所有评论
							$rs = $msg->getCommentMsg($postid);
							
							//获取该帖子的点赞次数
							$count = $msg->getLikeCount($postid);
							
							//解析评论数据
							if($rs){
								$comments = parseComments($rs);
							}else{
								$comments = array();
							}
							
							if($rs){
								returnMsg(200, '获取成功!',  array('comments'=>$comments, 'likes'=>$count));
							}else{
								returnMsg(201, '获取失败！', array('likes'=>$count));
							}
							
						}else{
							returnMsg(405, '参数错误!');
						}
						
						break;
					case 'COMMENT':
						$postid = isset($params['postid'])?$params['postid']:"";
						$content = isset($params['content'])?$params['content']:"";
						$from = isset($params['from'])?$params['from']:"";
						$fnick = isset($params['fnick'])?$params['fnick']:"";
						$to = isset($params['to'])?$params['to']:"";
						$tnick = isset($params['tnick'])?$params['tnick']:"";
						$parent = isset($params['parent'])?$params['parent']:"";
						
						if($postid && $content){
							//添加评论
							$datas = array(
								'type'=>2,
								'content'=>$content,
								'from'=>$from,
								'fnick'=>$fnick,
								'to'=>$to,
								'tnick'=>$tnick,
								'ref'=>$postid,
								'parent'=>$parent
							);
							
							$rs = $msg->saveMessage($datas);
							if($rs){
								returnMsg(200, '评论成功!',  array('from'=>$from, 'to'=>$to, 'msgid'=>$rs));
							}else{
								returnMsg(201, '评论失败！');
							}	
							
						}else{
							returnMsg(405, '参数错误!');
						}
						break;
					case 'LIKE':
						$postid = isset($params['postid'])?$params['postid']:"";
						$from = isset($params['from'])?$params['from']:"";
						$fnick = isset($params['fnick'])?$params['fnick']:"";
						$to = isset($params['to'])?$params['to']:"";
						$tnick = isset($params['tnick'])?$params['tnick']:"";
						$content = $fnick.' 赞了您的说说!';
						
						if($msg->isliked($postid, $from)){
							returnMsg(200, '点赞成功!');
						}
						
						if($postid && $content){
							//添加评论
							$datas = array(
									'type'=>3,
									'content'=>$content,
									'from'=>$from,
									'fnick'=>$fnick,
									'to'=>$to,
									'tnick'=>$tnick,
									'ref'=>$postid
							);
								
							$rs = $msg->saveMessage($datas);
							if($rs){
								returnMsg(200, '点赞成功!');
							}else{
								returnMsg(201, '点赞失败！');
							}
								
						}else{
							returnMsg(405, '参数错误!');
						}
						break;
					case 'MYMSG':
						$user = isset($params['user'])?$params['user']:"";
						$page = isset($params['page'])?$params['page']:1;
						$limit = isset($params['limit'])?$params['limit']:10;
						
						$start = ($page-1)*$limit;
						if($user){
							$rs = $msg->getMsgAboutMe($user, $start, $limit);
							if($rs){
								returnMsg(200, '获取成功!', array('messages'=>$rs));
							}else{
								returnMsg(201, '获取失败！');
							}
						
						}else{
							returnMsg(405, '参数错误!');
						}
						break;
					case 'LEAVEMSG':
						$parent = isset($params['parent'])?$params['parent']:0;
						$content = isset($params['content'])?$params['content']:'';
					
						//添加评论
						$datas = array(
								'type'=>4,
								'content'=>$content,
								'parent'=>$parent,
								'from'=> 'guest',
								'fnick'=> '游客',
								'to'=> 'admin',
								'tnick'=> '管理员',
								'ref'=>0
						);
							
						$rs = $msg->saveMessage($datas);
						if($rs){
							returnMsg(200, '留言成功!', array('msgid'=>$rs));
						}else{
							returnMsg(201, '留言失败！');
						}
					
						break;
					case 'GETLEAVEMSG':
						
						$rs = $msg->getLeaveMsg();
						
						$rs = parseLeaveMsg($rs);
						
						if($rs){
							returnMsg(200, '获取成功!', array('messages'=> $rs));
						}else{
							returnMsg(201, '获取失败！');
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