<?php
class Users extends Model{
	private $tableName = 'users';
	
	public function __construct(){
		parent::__construct($this->tableName);
		parent::relationsOpra($this->relations());
	}

	public function getTableName(){
		return $this->tableName;
	}
	
	public function getFields(){
		$fields = array(
			'user_no',
			'user_name',
			'user_pass',
			'user_nick',
			'user_gender',
			'user_ctime',
			'user_motto',
			'user_avator_key',
			'user_permission',
			'user_extension'
		);
		
		return $fields;
	}
	
	public function relations(){
		$ralations = array(
				
		);
		
		return $ralations;
	}
	
	//用户注册
	function register($params){
		$datas = array(
			'user_name'=>$params['username'],
			'user_pass'=> md5($params['userpass'])
		);
		
		return $this->insertData($datas);
	}
	
	//根据用户名查询用户
	function findUserByName($name){
		$dbc = new DbCriteria();
		$dbc->condition = array('user_name'=>':user_name');
		$dbc->params = array(':user_name'=>$name);
		
		return $this->find($dbc);
		
	}
	
	//修改用户密码
	function changeUserPass($name, $password){
		$dbc = new DbCriteria();
		
		$datas = array(
			'user_pass'=> md5($password)
		);
		
		$dbc->condition = array('user_name'=>':user_name');
		$dbc->params = array(':user_name'=>$name);
		
		return $this->updateData($datas, $dbc);
		
	}
	
	//保存用户基本信息
	function saveBaseInfo($params){
		
		$username = $params['username'];
		
		$datas = array(
			'user_gender'=>$params['gender'],
			'user_nick'=>$params['nickname'],
			'user_motto'=>$params['motto'],
			'user_avator_key'=>$params['key']
		);
		
		$dbc = new DbCriteria();
		$dbc->condition = array('user_name'=>':user_name');
		$dbc->params = array(':user_name'=>$username);
		
		return $this->updateData($datas, $dbc);
	}
	
}