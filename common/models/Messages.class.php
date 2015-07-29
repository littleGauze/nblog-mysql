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
	
	//保存帖子信息
	function saveMessage($params){
		
		$datas = array(
			'message_type'=>$params['type'],
			'message_content'=>$params['content'],
			'message_from'=>$params['from'],
			'message_ref'=>$params['ref']
		);
		
		isset($params['to']) && $datas['message_to'] = $params['to'];
		isset($params['parent']) && $datas['message_parent'] = $params['parent'];
		
		return $this->insertData($datas);
	}
	
}