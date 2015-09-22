<?php
class Posts extends Model{
	private $tableName = 'posts';
	
	public function __construct(){
		parent::__construct($this->tableName);
		parent::relationsOpra($this->relations());
	}

	public function getTableName(){
		return $this->tableName;
	}
	
	public function getFields(){
		$fields = array(
			'post_no',
			'post_img_key',
			'post_ctime',
			'post_user'
		);
		
		return $fields;
	}
	
	public function relations(){
		$ralations = array(
			'Users'=>array('sk'=>'user_name', 'fk'=>'post_user'),
			'Relations'=>array('sk'=>'relations_star', 'fk'=>'post_user')
		);
		
		return $ralations;
	}
	
	//保存帖子信息
	function publish($params){
		
		$datas = array(
			'post_img_key'=>$params['key'],
			'post_user'=>$params['username']
		);
		
		return $this->insertData($datas);
	}
	
	//根据用户名查询帖子
	function findPostsByUname($uname, $page, $limit){
		$start = ($page-1)*$limit;
		$cdb = new DbCriteria();
		$cdb->condition = array('post_user'=>':post_user');
		$cdb->params = array(':post_user'=> $uname);
		$cdb->order = 'post_ctime DESC';
		$cdb->limit = array($start,$limit);
		
		$rs = $this->findAll($cdb);
		return $rs;
		
	}
	
	//查询我关注的人的帖子(包括我的)
	function findFallowPosts($uname, $page, $limit){
		$start = ($page-1)*$limit;
		$cdb = new DbCriteria();
		$cdb->condition = array('OR'=>':or');
		$condition = array('relations_fans'=>$uname, 'post_user'=>$uname);
		$cdb->params = array(':or'=> $condition);
		$cdb->allload = true;
		$cdb->order = 'post_ctime DESC';
		$cdb->limit = array($start,$limit);
		
		$rs = $this->findAll($cdb);
		
		return $rs;
	}
	
	//查询所有最新的帖子
	function findAllPosts($page, $limit, $exclude=''){
		$start = ($page-1)*$limit;
		$cdb = new DbCriteria();
		
		if(!empty($exclude)){
			$cdb->allload = true;
			$cdb->condition = array('post_user[!]'=>':post_user');
			$cdb->params = array(':post_user'=>$exclude);
		}
		
		$cdb->allload = true;
		$cdb->order = 'post_ctime DESC';
		$cdb->limit = array($start,$limit);
		
		$rs = $this->findAll($cdb);
		
		return $rs;
	}
	
}