<?php
class Relations extends Model{
	private $tableName = 'relations';
	
	public function __construct(){
		parent::__construct($this->tableName);
		parent::relationsOpra($this->relations());
	}

	public function getTableName(){
		return $this->tableName;
	}
	
	public function getFields(){
		$fields = array(
			'relations_no',
			'relations_star',
			'relations_fans',
			'relations_eachother',
			'relations_ftime'
		);
		
		return $fields;
	}
	
	public function relations(){
		$ralations = array(
			
		);
		
		return $ralations;
	}
	
	//查询某用户是否关注了我
	function isFallowMe($user, $me){
		$cdb = new DbCriteria();
		
		$cdb->condition = array("AND"=>":and");
		$condition = array("relations_star"=>$me, "relations_fans"=>$user);
		$cdb->params = array(":and"=>$condition);
		$rs = $this->hasData($cdb);
		return $rs;
	}
	
	//改变用户的关注状态
	function doEachother($user, $me, $status){
		
		$datas = array("relations_eachother"=>$status);
		
		$cdb = new DbCriteria();
		$cdb->condition = array("AND"=>":and");
		$condition = array("relations_star"=>$me, "relations_fans"=>$user);
		$cdb->params = array(":and"=>$condition);
		
		return $this->updateData($datas, $cdb);
	}
	
	//查询我关注的所有用户
	function findAllStars($uname){
		$cdb = new DbCriteria();
		$cdb->condition = array('relations_fans'=>':fans');
		$cdb->params = array(':fans'=> $uname);
		$cdb->order = 'relations_ftime DESC';
		$cdb->limit = array(0, 100000);
		
		$rs = $this->findAll($cdb);
		return $rs;
	}
	
	//查询所有关注我的用户
	function findAllFans($uname){
		$cdb = new DbCriteria();
		$cdb->condition = array('relations_star'=>':star');
		$cdb->params = array(':star'=> $uname);
		$cdb->order = 'relations_ftime DESC';
		$cdb->limit = array(0, 100000);
		
		$rs = $this->findAll($cdb);
		return $rs;
	}
	
	//关注用户
	function fallowSomeone($user, $me){
		
		$datas = array(
			'relations_star'=>$user,
			'relations_fans'=>$me,
			'relations_eachother'=>0
		);
		
		if($this->isFallowMe($user, $me)){
			//到这步说明接下来就是互相关注了
			$datas['relations_eachother'] = 1;
			//改变我关注的用户的状态为互相关注
			$this->doEachother($user, $me, 1);
			
		}
		
		return $this->insertData($datas);
	}
	
	//取消关注
	function unfallowSomeone($user, $me){
		$cdb = new DbCriteria();
		$cdb->condition = array("AND"=>":and");
		$condition = array("relations_star"=>$user, "relations_fans"=>$me);
		$cdb->params = array(":and"=>$condition);
		$cdb->limit = 1;
		
		$rs = $this->deleteData($cdb);
		
		if($rs){
			//成功后检查该用户是否关注了我
			if($this->isFallowMe($user, $me)){
				//改变我关注的用户的状态为互相关注
				$this->doEachother($user, $me, 0);
				//删除来自改用户的系统消息
			}
		}
		
		return $rs;
	}
	
}