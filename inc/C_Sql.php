<?
/**
* @version 1.0.0
* @CreTime:2011-06-24
* @CreName:hw
*/


/*
生成SQL语句Where
输入格式:一维数组
返回格式:string
*/
function out_sql_where($arr)
{
	if(is_array($arr))
	{
		foreach($arr as $arr_key_t=>$arr_value_t)
		{
			if(!empty($arr_key_t))
			{
				$out_arr[$arr_key_t]="`$arr_key_t`='$arr_value_t'";
			}
		}
		
		if(is_array($out_arr))
		{
			$str_sql=" ".join(" and ",$out_arr)." ";
			return $str_sql;
		}
	}
	return false;
}


/*
生成SQL语句update
输入格式:一维数组
返回格式:string
*/
function not_null_update($arr){
	if(is_array($arr))	{
		foreach($arr as $arr_key_t=>$arr_value_t){
			if(!empty($arr_key_t) and $arr_value_t!==NULL)
			{
				$arr_value_t=stripslashes_deep($arr_value_t);
				$arr_value_t=addslashes($arr_value_t);
				$out_arr[$arr_key_t]="`$arr_key_t`='$arr_value_t'";
			}
		}
		
		if(is_array($out_arr))		{
			$str_sql=" ".join(",",$out_arr)." ";
			return $str_sql;
		}
	}
	return false;
}

/*
生成SQL语句update
输入格式:一维数组
返回格式:string
*/
function out_sql_update($arr){
	if(is_array($arr))	{
		foreach($arr as $arr_key_t=>$arr_value_t){
			if(!empty($arr_key_t))
			{
				$out_arr[$arr_key_t]="`$arr_key_t`='$arr_value_t'";
			}
		}
		
		if(is_array($out_arr))		{
			$str_sql=" ".join(",",$out_arr)." ";
			return $str_sql;
		}
	}
	return false;
}

/*
生成SQL语句insert
输入格式:一维数组
返回格式:string
*/
function out_sql_insert($arr){
	if(is_array($arr)){
		$i=0;
		foreach($arr as $arr_key_t=>$arr_value_t){
			if(!empty($arr_key_t)){
				$i++;
				$filed[$i]="`$arr_key_t`";//字段
				$arr_value_t=stripslashes_deep($arr_value_t);
				$arr_value_t=addslashes($arr_value_t);
				
				if($arr_value_t=='null')
					$values[$i]=$arr_value_t;
				else 
					$values[$i]="'$arr_value_t'";
			}
		}
		return $str_sql=' ('.join(",",$filed).') values ('.join(",",$values).') ';
	}
	return false;
}


/**
 * [insert_all 批量导入]
 * @param  [array] $arr [二维数组]
 * @return [string]      [sql]
 */
// function insert_all($arr){
// 	if(is_array($arr)){
// 		$field =array();
// 		$i = 0;
// 		foreach ($arr[0] as $key => $value) {
// 			$field[] = $key;
// 		}

// 		$sql = '('.implode(',',$field).')';
// 		foreach ($arr as $k => $v) {

// 			echo $k;
// 			echo $v['company_url'];
// 			// $sql = 
// 		}
// 	}

// 	return false;
// }
