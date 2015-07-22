<?php
	class DbCriteria{
		
		public $select='*';
		
		public $distinct=false;
		
		public $condition='';
		
		public $params=array();
		
		public $limit=array();
		
		public $like=array();
		
		public $disableClassRelation=array();
		
		public $offset=-1;
		
		public $order='';
		
		public $allload = false;
		
		public $group='';
		
		public $join='';
		
		public $having='';
		
		private $instance;
		
		public function __construct(){
		}
		
		public function selectOpra(){
			$selectRtn = array();
			if ($this->select!='*') {
				if(is_array($this->select)){
					foreach ($this->select as $val){
						$classExp = explode('.', $val);
						if (count($classExp)>1) {
							$className = $classExp[0];
							$modelInstance = new $className;
							$tableName = $modelInstance->getTableName();
							array_push($selectRtn, $tableName.'.'.$classExp[1]);
						}else{
							array_push($selectRtn, $val);
						}
					}
				}else {
					$selectRtn = $this->select;
				}
			}else{
				$selectRtn = $this->select;
			}
			return $selectRtn;
		}
		
		public function joinOpra($relations){
			$joinListRtn = array();
			if($this->allload){
				foreach ($relations as $className=>$fskey){
					$joinArray = array();
					$instanceModel2 = new $className;
					if(in_array($className, $this->disableClassRelation)){
						continue;
					}
					if(is_array($fskey['fk'])){
						$joinSkFkArr = array();
						foreach ($fskey['fk'] as $fkkey=>$fkval){
							$joinSkFkArr[$fkval] = $fskey['sk'][$fkkey];
						}
						$joinArray['[>]'.$instanceModel2->getTableName()] = $joinSkFkArr;
					}else{
						$joinArray['[>]'.$instanceModel2->getTableName()] = array($fskey['fk']=>$fskey['sk']);
					}
					$joinArray = $this->getRelationByClassName($className, $joinArray);
					$joinListRtn = array_merge($joinListRtn,$joinArray);
				}
			}
			return $joinListRtn;
		}
		
		private function getRelationByClassName($className, $joinClass){
			$sds = array();
			$instanceModel1 = new $className;
			$relations = $instanceModel1->relations();
			if(!empty($relations)){
				foreach ($relations as $className=>$fskey){
					$instanceModel2 = new $className;
					if(in_array($className, $this->disableClassRelation)){
						continue;
					}
					if(is_array($fskey['fk'])){
						$joinSkFkArr = array();
						foreach ($fskey['fk'] as $fkkey=>$fkval){
							$joinSkFkArr[$instanceModel1->getTableName().'.'.$fkval] = $fskey['sk'][$fkkey];
						}
						$joinClass['[>]'.$instanceModel2->getTableName()] = $joinSkFkArr;
					}else{
						$joinClass['[>]'.$instanceModel2->getTableName()] = array($instanceModel1->getTableName().'.'.$fskey['fk']=>$fskey['sk']);
					}
					$joinClass = $this->getRelationByClassName($className,$joinClass);	
				}
				return $joinClass;
			}else{
				return $joinClass;
			}
		}
		
		public function conditionOpra(){
			$conditionRtn = null;
			$condition = $this->condition;
			$params = $this->params;
			if (!empty($condition)&&!empty($params)) {
				$conditionRtn = $this->recursiveCondition($condition, $params);
			}
			if(!empty($this->limit)){
				$conditionRtn['LIMIT'] = $this->limit;
			}
			if(!empty($this->like)){
				$conditionRtn['LIKE'] = $this->like;
			}
			if(!empty($this->order)){
				$conditionRtn['ORDER'] = $this->order;
			}
			if(!empty($this->group)){
				$conditionRtn['GROUP'] = $this->group;
			}
			return $conditionRtn;
		}
		
		private function recursiveCondition($condition, $params){
			$condition = $this->valueSet($condition,$params);
			foreach ($condition as $key => $val ){
				if (is_array ($val)){
					$condition[$key] = $this->recursiveCondition ($val, $params);
				}
			}
			return $condition;
		}
		
		private function valueSet($condition,$params){
			foreach($condition as $key=>$val){
				if(!is_array($val)&&!empty($params[$val])){
					$condition[$key] = $params[$val];
				}
			}
			return $condition;
		}
		
	}