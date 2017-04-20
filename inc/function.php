<?php
//深度去除魔术引号
function stripslashes_deep($value){
    $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :
                stripslashes($value);
    return $value;
}


/*
*自动判断把gbk或gb2312编码的字符串转为utf8 
*能自动判断输入字符串的编码类，如果本身是utf-8就不用转换，否则就转换为utf-8的字符串 
*支持的字符编码类型是：utf-8,gbk,gb2312 
*@$str:string 字符串 
*/ 
function charset_2_utf8($str){ 
	$charset = mb_detect_encoding($str,array('UTF-8','GBK','GB2312')); 
	$charset = strtolower($charset); 
	if('cp936' == $charset){ 
		$charset='GBK'; 
	} 
	if("utf-8" != $charset){ 
		$str = iconv($charset,"UTF-8//IGNORE",$str); 
	} 
	return $str; 
}


//根据头尾标签获得内容
function getTagData($start, $end,$str){
	if ( $start == '' || $end == '' ){
		return;
	}
	$str = explode($start, $str);
	$str = explode($end, $str[1]);
	return $str[0];
}