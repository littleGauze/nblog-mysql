<?php

	/**
	 * 字符串截取方法
	 * @param unknown $string 传入字符串
	 * @param unknown $length 截取长度
	 * @param unknown $repstr 追加的字符串
	 * @return string
	 */
	 function cutstr($string, $length, $repstr) {
    	$wordscut = '';
    	$j = 0;
    	preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $info);
    	for($i=0; $i<count($info[0]); $i++) {
    		$wordscut .= $info[0][$i];
    		$j = ord($info[0][$i]) > 127 ? $j + 2 : $j + 1;
    		if ($j > $length - 3) {
    			return $wordscut.$repstr;
    		}
    	}
    	return join('', $info[0]);
    }
    
    /**
     * 获取传入的参数过滤值
     * @param string  $key 		键值
     * @param string  $default 	默认值
     * @param string  $method 	接受类型
     * @return 过滤后的值
     */
    function getParam($key,$default='',$method='GET'){
    	$findArr=array('GET'=>INPUT_GET,'POST'=>INPUT_POST);
    	$value = filter_input($findArr[$method],$key);
    	$value = trim($value);
    	if($method=='GET'){
    		if(isset($_GET[$key])){
    			return $value!==''?$value:$default;
    		}else{
    			return $default;
    		}
    	}elseif($method=='POST'){
    		if(isset($_POST[$key])){
    			return $value!==''?$value:$default;
    		}else{
    			return $default;
    		}
    	}else{
    		return !empty($value)?$value:$default;
    	}
    }
    
    /**
     * 返回状态json数据封装
     * @param unknown $result_code
     * @param unknown $result_desc
     * @param unknown $ext
     */
    function returnMsg($result_code,$result_desc,$ext=array()){
    	$params = array('result_code'=>$result_code,'result_desc'=>$result_desc);
    	$params = array_merge($params,$ext);
    	exit( json_encode($params,JSON_UNESCAPED_UNICODE) );
    }
    
    //解析评论数据
    function parseComments($raws){
    	$comments = array();
    	foreach($raws as $comm){
    		if($comm['message_parent'] != 0) continue;
    		$msgno = $comm['message_no'];
    		$comments[] = $comm;
    		foreach($raws as $filter){
    			if($filter['message_parent'] == 0) continue;
    			if($filter['message_parent'] == $msgno){
    				$comments[] = $filter;
    			}
    		}
    	}
    	return $comments;
    }
    
    //解析留言数据
    function parseLeaveMsg($raws){
    	$msgs = array();
    	foreach($raws as $msg){
    		if($msg['message_parent'] != 0) continue;
    		$msg['replys'] = array();
    		foreach ($raws as $sub){
    			if($sub['message_parent'] == 0) continue;
    			if($msg['message_no'] == $sub['message_parent']){
    				$msg['replys'][] = $sub;
    			}
    		}
    		
    		if($msg['message_parent'] == 0){
    			$msgs[] = $msg;
    		}
    	}
    	
    	return $msgs;
    }
    
    function getPathInfo($filename) {
    	return pathinfo($filename);
    }
    
    //简单验证请求来源
    function checkAuth($auth){
    	$token = TOKEN;
    	$token = str_split($token);
    	$auth = str_split($auth);
    	$tlength = count($token);
    	$flength = 0;
    	
    	while(($char = array_shift($token))){
    		$index = array_search($char, $auth);
    		if($index !== false){
    			$del = array_splice($auth, $index, 1);
    			$flength++;
    		}
    	}
    	
    	return ($tlength == $flength);
    }
    
    function getRandLowerStr($length) {
    	$str = 'bcdfghjkmnopqrstuvwxyABCDEFHIJKLMNOPQRSTUVWXYZ0123456789';
    	$randString = '';
    	$len = strlen($str)-1;
    	for($i = 0;$i < $length;$i ++){
    		$num = mt_rand(0, $len);
    		$randString .= $str[$num];
    	}
    	return strtolower($randString);
    }