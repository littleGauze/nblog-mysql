<?php
class Model extends Medoo{
	
	private $table;
	
	private $where = null;
	
	private $select = null;
	
	private $join = null;
	
	private $relations = null;
	
	private $fieldMapping = null;
	
	public function __construct($tableMame){
		global $DB_MYSQL_CONF;
		$this->table = $tableMame;
		parent::__construct(array(
				'database_type' => 'mysql',
				'database_name' => $DB_MYSQL_CONF['DB_NAME'],
				'server' => $DB_MYSQL_CONF['DB_HOST'],
				'username' => $DB_MYSQL_CONF['DB_USER'],
				'password' => $DB_MYSQL_CONF['DB_PWD'],
				// 可选参数
				'port' => $DB_MYSQL_CONF['DB_PORT'],
				'charset' => 'utf8',
				// 连接参数可参考官方手册： http://www.php.net/manual/en/pdo.setattribute.php
				'option' => array(PDO::ATTR_CASE => PDO::CASE_NATURAL)
		));
	}
	
	protected function relationsOpra($relations){
		$this->relations = $relations;
	}
	
	private function dbCriteriaOpra($dbCriteria){
		$this->join = $dbCriteria->joinOpra($this->relations);
		$this->select = $dbCriteria->selectOpra();
		$this->where = $dbCriteria->conditionOpra();
	}
	
	public function find($dbCriteria){
		$this->dbCriteriaOpra($dbCriteria);
        $modelList = array();
        if(!empty($this->join)){
			$modelList = $this->select($this->table, $this->join, $this->select, $this->where);
		}else{
			$modelList = $this->select($this->table, $this->select, $this->where);
		}
        if(!empty($modelList)){
            return $modelList[0];
        }else{
            return $modelList;
        }
	}
	
	public function findAll($dbCriteria){
		$this->dbCriteriaOpra($dbCriteria);
		if(!empty($this->join)){
			return $this->select($this->table, $this->join, $this->select, $this->where);
		}else{
			return $this->select($this->table, $this->select, $this->where);
		}
	}
	
	public function insertData($dataInsert){
		return $this->insert($this->table, $dataInsert);
	}
	
	public function updateData($dataUpdate,$dbCriteria){
		$this->dbCriteriaOpra($dbCriteria);
		return $this->update($this->table, $dataUpdate, $this->where);
	}
	
	public function replaceData($column, $search, $replace, $dbCriteria){
		$this->dbCriteriaOpra($dbCriteria);
		return $this->replace($this->table, $column, $search, $replace, $this->where);
	}
	
	public function replacementData($column, $replacement, $dbCriteria){
		$this->dbCriteriaOpra($dbCriteria);
		return $this->replace($this->table, $column, $replacement, $this->where);
	}
	
	public function replaceColumnsData($columns, $dbCriteria){
		$this->dbCriteriaOpra($dbCriteria);
		return $this->replace($this->table, $columns, $this->where);
	}
	
	public function hasData($dbCriteria){
		$this->dbCriteriaOpra($dbCriteria);
		if(!empty($this->join)){
			return $this->has($this->table, $this->join, $this->where);
		}else{
			return $this->has($this->table, $this->where);
		}
	}
	
	public function dataSum($dbCriteria){
		$this->dbCriteriaOpra($dbCriteria);
		if(!empty($this->join)){
			return $this->sum($this->table, $this->join, $this->select, $this->where);
		}else{
			return $this->sum($this->table, $this->select, $this->where);
		}
	}
	
	public function dataCount($dbCriteria){
		$this->dbCriteriaOpra($dbCriteria);
		if(!empty($this->join)){
			return $this->count($this->table, $this->join, $this->select, $this->where);
		}else{
			return $this->count($this->table, $this->where);
		}
	}
		
	public function deleteData($dbCriteria){
		$this->dbCriteriaOpra($dbCriteria);
		return $this->delete($this->table, $this->where);
	}
}
