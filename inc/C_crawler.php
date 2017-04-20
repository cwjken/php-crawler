<?php

/**
* @version 1.0.0
* @CreTime:2016-12-16
* @CreName:hw
* @note:爬虫
*/
require_once("C_Sql.php");
final class Company{
	static public function getUrlData($arr,$num,$tablename,$dbn) {
		$sql_str = '1';
		foreach ($arr as $k => $v) {
			if('fetch_code'==$k){
				$sql_str.= ' and '.$k.'="'.$v.'"';
			}

			if('is_fetch_done'==$k){
				$sql_str.= ' and '.$k.'="'.$v.'"';
			}

			if('fetch_times'==$k){
				$sql_str.= ' and '.$k.'="'.$v.'"';
			}
			
		}
		$sql    = "SELECT * FROM ".$tablename." WHERE ".$sql_str." limit 0,".$num;
		$result = $dbn->query($sql);
		$return = array();
		foreach ($result as $k => $v) {
			$return[$k]['fetch_time']    = $v['fetch_time'];
			$return[$k]['company_url']   = $v['company_url'];
			$return[$k]['is_fetch_done'] = $v['is_fetch_done'];
		} 
		return $return;
	}


	/***************************************************
	 插入一条记录列表
	***************************************************/
	static public function save_one_url_list($_inputData,$dbn){
		if(true==self::check_one_goods_url($_inputData['company_url'],$dbn)){
			self::update_setting($_inputData,$_inputData['company_url'],'gaide_company_url',$dbn);
		}else{
			$tablename=C_TableNamePrefix."gaide_company_url";
			$sql_command="insert into `".$tablename."` ".out_sql_insert($_inputData);
			$result  = $dbn->exec($sql_command);
			$returnid= $dbn->lastInsertId();
			if($result<=0){
				return false;
			}
			return $returnid;
		}
		
	}
	

	//更新
	static public function update_setting($_inputData,$goods_page_url,$tablename,$dbn){
		$tablename   = C_TableNamePrefix."{$tablename}";
		$sql_command = "UPDATE  `".$tablename."` SET  ".out_sql_update($_inputData)." WHERE  `goods_page_url` ='".$goods_page_url."'";
		$result=$dbn->exec($sql_command);
	
		if($result>0){
			return true;
		}
		return false;
	}

		//更新
	static public function update_setting_url($_inputData,$goods_page_url,$tablename,$dbn){
		$tablename   = C_TableNamePrefix."{$tablename}";
		$sql_command = "UPDATE  `".$tablename."` SET  ".out_sql_update($_inputData)." WHERE  `company_url` ='".$goods_page_url."'";
		$result=$dbn->exec($sql_command);
	
		if($result>0){
			return true;
		}
		return false;
	}

	/***************************************************
	 检查一条记录是否存在于goods表中
	***************************************************/
	static public function check_one_goods_url($company_url,$dbn){
		$tablename  = C_TableNamePrefix."company_goods";
		$sql_command= "SELECT  goods_id FROM  `".$tablename."` WHERE  `company_url` ='{$company_url}' ";
		$result=$dbn->query($sql_command);

		if(result>0){
			return true;
		}
		return false;
	}				

// 	/***************************************************
// 	 检查一条记录是否存在于公司信息表中
// 	***************************************************/
// 	static public function check_one_page_list($goods_page_url){
// 		$tablename  = C_TableNamePrefix."company_goods_page";
// 		$sql_command= "SELECT  * FROM  `".$tablename."` WHERE  `goods_page_url` ='{$goods_page_url}' ";
// 		$result=mysql_query($sql_command);
// 		$num=mysql_affected_rows();
// 		if($num>0){
// 			return true;
// 		}
// 		return false;
// 	}				

// 	/***************************************************
// 	 检查一条记录是否存在于公司信息表中
// 	***************************************************/
// 	static public function check_one_url_list($company_id){
// 		$tablename  = C_TableNamePrefix."company";
// 		$sql_command= "SELECT  * FROM  `".$tablename."` WHERE  `company_id` ='{$company_id}' ";
// 		$result=mysql_query($sql_command);
// 		$num=mysql_affected_rows();
// 		if($num>0){
// 			return true;
// 		}
// 		return false;
// 	}				




}
