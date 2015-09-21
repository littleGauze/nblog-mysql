<?php
class Messages extends Model{
	private $tableName = 'messages';
	
	public function __construct(){
		parent::__construct($this->tableName);
		parent::relationsOpra($this->relations());
	}

	public function getTableName(){
		return $this->tableName;
	}
	
	public function getFields(){
		$fields = array(
			'message_no',
			'message_type',
			'message_content',
			'message_ctime',
			'message_from',
			'message_to',
			'message_ref',
			'message_parent',
			'message_disabled'
		);
		
		return $fields;
	}
	
	public function relations(){
		$ralations = array(
			
		);
		
		return $ralations;
	}
	
	//保存消息信息
	function saveMessage($params){
		
		$datas = array(
			'message_type'=>$params['type'],
			'message_content'=>$params['content'],
			'message_from'=>$params['from'],
			'message_from_nick'=>$params['fnick'],
			'message_to'=>$params['to'],
			'message_to_nick'=>$params['tnick']
		);
		
		if($params['ref']){
			$datas['message_ref'] = $params['ref'];
		}
		
		isset($params['parent']) && $datas['message_parent'] = $params['parent'];
		
		return $this->insertData($datas);
	}
	
	//删除消息
	function deleteMessage($type='', $form='', $to=''){
		$cdb = new DbCriteria();
		$cdb->condition = array("AND"=>":and");
		$condition = array();
		
		if($type){
			$condition['message_type'] = $type;
		}
		if($form){
			$condition['message_from'] = $form;
		}
		if($to){
			$condition['message_to'] = $to;
		}
		
		$cdb->params = array(":and"=>$condition);
		
		$rs = $this->deleteData($cdb);
		
		return $rs;
	}
	
	//获取消息
	function getCommentMsg($postid){
		$cdb = new DbCriteria();
		
		$cdb->condition = array("AND"=>":and");
		$condition = array();
		
		$condition['message_ref'] = $postid;
		$condition['message_type'] = 2;
		
		$cdb->params = array(':and'=> $condition);
		$cdb->limit = array(0, 100000);
		
		$rs = $this->findAll($cdb);
		
		return $rs;
	}
	
	//获取被赞的次数
	function getLikeCount($postid){
		
	}
	
}