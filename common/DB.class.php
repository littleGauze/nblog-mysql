<?php
/*
	数据库操作类
*/
class Db_Action{
	private $dbname;
	private $host;
	private $dbuser;
	private $dbpass;
	private $dbport;
	private $dsn;
	private $connobj;
	private $defcharset;
	private $debugstatus;
	/*
		构造函数
	*/
	function __construct($dbname,$host,$dbuser,$dbpass,$debugstatus=0,$dbport=3306,$defcharset='utf8'){
		$this->dbname=$dbname;
		$this->host=$host;
		$this->dbuser=$dbuser;
		$this->dbpass=$dbpass;
		$this->dbport=$dbport;
		$this->defcharset=$defcharset;
		$this->debugstatus=$debugstatus;
		$this->dsn='mysql:dbname='.$dbname.';host='.$host.';port='.$dbport;
		$this->ConnDb();
	}
	/*
		连接数据库
	*/
	public function ConnDb(){
		try{
			$this->connobj=new PDO($this->dsn,$this->dbuser,$this->dbpass,
				array(PDO::MYSQL_ATTR_INIT_COMMAND=>"set names ".$this->defcharset)
			);
			//$this->connobj->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);//这个是通过设置属性方法进行关闭自动提交和上面的功能一样
			$this->connobj->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);//开启异常处理
					return true;
		} catch (PDOException $e){
			return false;
		}
	}
	
	public function StartMulti(){
		return $this->connobj->beginTransaction();
	}
	public function EndMulti(){
		return $this->connobj->commit();
	}
	public function RollbackMulti(){
		return $this->connobj->rollBack();
	}
	/*
		生成预处理参数
	*/
	protected function GeneratePreParam($param){
		if(empty($param)){
			$predefine='';
		}else{
			$predefine=substr(str_repeat('?,',count($param)),0,-1);
		}
		return $predefine;
	}
	/*
		由错误日志标志来决定执行失败时的情况
	*/
	protected function ErrorStatus($cursth){
		if($this->debugstatus==1){
			print_r($cursth->errorInfo());
			return false;
		}else{
			return false;
		}
	}
	/*
		数据删除的相关操作
		参数依次是:
		存储过程名称、相应的参数
	*/
	public function DelData($procedure_name,$procedure_param){
		$querystr="call ".$procedure_name.'('.$this->GeneratePreParam($procedure_param).')';
		$sth=$this->connobj->prepare($querystr);
		$sth->execute($procedure_param);
		if($sth->errorCode()=='00000'){
			return true;
		}else{
			$this->ErrorStatus($sth);
		}
	}
	/*
		数据修改的相关操作
		参数依次是:
		存储过程名称、相应的参数
	*/
	public function ModiData($procedure_name,$procedure_param){
		$querystr="call ".$procedure_name.'('.$this->GeneratePreParam($procedure_param).')';
		$sth=$this->connobj->prepare($querystr);
		$sth->execute($procedure_param);
		if($sth->errorCode()=='00000'){
			return true;
		}else{
			$this->ErrorStatus($sth);
		}
	}
	/*
		数据插入的相关操作
		参数依次是:
		存储过程名称、相应的参数、是否返回最后插入ID
	*/
	public function InsData($procedure_name,$procedure_param,$iflastid=0){
		$querystr="call ".$procedure_name.'('.$this->GeneratePreParam($procedure_param).')';
		$sth=$this->connobj->prepare($querystr);
		$sth->execute($procedure_param);
		if($sth->errorCode()=='00000'){
			if($iflastid==1){
				$res=$sth->fetch(PDO::FETCH_ASSOC);
				return $res['lastid'];
			}else{
				return true;
			}
		}else{
			$this->ErrorStatus($sth);
		}
	}
	/*
		数据查询的相关操作
		参数依次是:
		存储过程名称、相应的参数、查询类型
		查询类型依次是
		101分页总数
		102单条查询
		103分页详细
	*/
	public function SelData($procedure_name,$procedure_param,$seltype){
		$querystr="call ".$procedure_name.'('.$this->GeneratePreParam($procedure_param).')';
		$sth=$this->connobj->prepare($querystr);
		$sth->execute($procedure_param);
		if($sth->errorCode()=='00000'){
			if($seltype==101){
				$res=$sth->fetch(PDO::FETCH_ASSOC);
				return $res['amount'];
			}elseif($seltype==102){
				return $sth->fetch(PDO::FETCH_ASSOC);
			}elseif($seltype==103){
				return $sth->fetchAll(PDO::FETCH_ASSOC);
			}else{
				
			}
		}else{
			$this->ErrorStatus($sth);
		}
	}
	
	//query方式的查询方式
	public function DataQuery($sql,$type){
		$count = $this->connobj->query($sql);
		if ($type=="list") {
			return $count->fetchAll();
		}else if($type=="single"){
			return $count->fetch();
		}else if($type=="count"){
			return $count->rowCount();
		}else if($type=='insert'){
			return $this->connobj->lastInsertId();
		}else{
			return $count;
		}
	}
	
	//query方式的操作方式
	public function InsDataQuery($sql,$type){
		try {
			$this->connobj->exec($sql);
			if($type=="insert"){
				return $this->connobj->lastInsertId();
			}
		}catch(PDOException  $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function CloseConn(){
		$this->connobj=null;
	}
	/*
		析构函数
	*/
	function __destruct(){
		$this->connobj=null;
	}
}
/*
	针对具体的数据库实例,就是不同的数据库实例链接的时候相应的信息
*/
class Db_Instance extends Db_Action{
    function __construct($dbname,$dbhost,$dbport='3306',$uname,$pwd,$ifdebug=0,$defcharset='utf8'){
    	parent::__construct($dbname,$dbhost,$uname,$pwd,$ifdebug,$dbport,$defcharset);
	}
}
?>
