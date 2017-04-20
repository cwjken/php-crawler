<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>采集管理</title>
</head>
<?php
	require_once("./inc/inc.php");
	error_reporting(E_ALL ^ E_NOTICE && E_WARNING);
	set_time_limit ( 0 );
	$set_array=array(
		'is_fetch_done' => 'N',
	    'fetch_time'    => 0,		   
	);

	$url_list=Company::getUrlData($set_array,1,'hxj_gaide_company_url_list',$dbn);	

	// echo "<pre>";
	// print_r($url_list);
	// die();
	if(empty($url_list)){
		die('没有记录');
	}
 
	$useragent = "Baiduspider ( http://www.baidu.com/search/spider.htm)";
	$the_header=header_rand();

	$options = array(
		CURLOPT_USERAGENT => $useragent,//这步为关键一步一定要设置一个值
		// CURLOPT_ENCODING  => 'gzip,deflate',
		CURLOPT_REFERER      => "https://www.baidu.com",

    );
	$rc = new muti_curl("get_detail_url_callback");
	$rc->timeout     = 15;
	$rc->thread_size = 1;
	foreach ((array)$url_list as $k=>$val) {
		$etc     = array(
			'dbn'=>$dbn,
			'url'=>$val['company_url']
		);
		$the_url = $val['company_url'];
		$request = new request_setting($the_url, $method = "GET", $post_data = NULL,$the_header, $options,$etc);
		$rc->add($request);
	}
	$rc->add($request);
	$rc->execute();
 
	$refresh_time=2000;
	echo '<script language="javascript" >'."\r\n";
	echo '	function myrefresh(){'."\r\n";
	echo '		window.location.reload();'."\r\n";
	echo '	}'."\r\n";
	echo '	setTimeout(\'myrefresh()\','.$refresh_time.'); //指定2秒刷新一次'."\r\n";		
	echo '</script>'."\r\n";		


	/*
	 *  回调函数，用于调试各种结果。
	 *  $response, 代表响应结果，一般来说是一长长的字符串。
	 *  $info      curl获得的信息，里面好多东东 是一个数组形式
	 *  $request   请求的信息，为一堆可变的数组
	 *  对 $response 分析，并写入进数据库
	*/
	function get_detail_url_callback($response, $info, $request) {
	
		global $sucesesnum,$school_id;
		echo "<pre>";
		if( $response !== false  ) {
			echo "成功响应<br>";
		}
		echo '<br>第'. $sucesesnum.'成功请求。用时:'. $info['total_time'].'秒'; 
		echo "<br>返回数据如下: <br> ";
		echo '<hr />';
		// print_r($info);
		print_r($request);
		// var_dump($request->etc);
		// file_put_contents('log.txt', $response);
		// echo $response;
		
		// die();
		$response=getTagData('<div class="clist_list_content">','<!--左侧结束-->',$response);
		$response=charset_2_utf8($response);//转化成UTF-8编码
		// echo $response.'<br />';
        preg_match_all('/<ul>(.*?)<\/ul>/ism', $response, $matches);
		// preg_match_all('/id=\"listnam\_(.*?)\">(.*?)<\/b>/ism', $response, $matches);
		// print_r($matches);

		$company = array();
		$company['fetch_time']       = '0';
		$company['is_fetch_done']    = 'N';
		$company['create_time']      = date('Y-m-d H:i:s');
die();
		foreach($matches[1] as $k=>$v){
			if(strstr($v,'vipflag.png')){
				$company['is_vip'] = 1;
			}else{
				$company['is_vip'] = 0;
			}

			$company_name = getTagData('class="dblue cuti">','</a>',$response);
			$company_url  = getTagData('<a href="','" target="_blank"',$response);
			
			$company['company_name']       = $company_name;
			$company['company_url']        = $company_url;
		
			print_r($company);
			// die();
			Company::save_one_url_list($company,$request->etc['dbn']);
			// die();
		}

			$_inputData['fetch_time']    = '1';
			$_inputData['is_fetch_done'] = 'Y';

			Company::update_setting_url($_inputData,$request->etc['url'],'gaide_company_url_list',$request->etc['dbn']);

		// die();

	}


	function check_input_item($arr) {
		$size=count($arr);
		$return_array=array();
		if($size==2){
			$key  = trim($arr[0]);
			$val  = trim($arr[1]);

			if($val=='--'){
				$val = '';
			}

			switch ($key) {
				case '公司类型':
					$return_array['e_type'] = $val;
					break;

				case '所属行业':
					$return_array['e_industry'] = $val;
					break;

				case '认证资质':
					$return_array['e_qualifications'] = $val;
					break;

				case '主要市场':
					$return_array['e_mark'] = $val;
					break;

				case '销售额（元/年）':
					$return_array['e_tradenum'] = $val;
					break;

				case '公司成立时间':
					$return_array['e_time'] = $val;
					break;

				case '公司人数':
					$return_array['e_outnumber'] = $val;
					break;

				case '所在地区':
					$rs = explode(' ',$val);
					$return_array['area_p'] = $rs[0];
					$return_array['area_c'] = $rs[1];
					$return_array['area_x'] = $rs[2];
					break;

				case '公司网址':
					$return_array['e_web_url'] = $val;
					break;
				
				default:
					# code...
					break;
			}
		}

		if($size==3){
			$logo = $arr[0];
			$key  = $arr[1];
			$val  = $arr[2];

			preg_match_all('<img.*?src="(.*?)">',$logo,$matches);
			$logo                      = $matches[1];
			$return_array['e_logo'] = 'http:'.$logo[0];
			$return_array['e_name']    = $val;

		}

		return $return_array;
	}
	
	/***************************************
	*根据一定的ip段随机模拟出IP
	**************************************/
	function ip_rand(){
		$ip_long = array(
				array('607649792'  , '608174079'), //36.56.0.0-36.63.255.255
				array('1038614528' , '1039007743'), //61.232.0.0-61.237.255.255
				array('1783627776' , '1784676351'), //106.80.0.0-106.95.255.255
				array('2035023872' , '2035154943'), //121.76.0.0-121.77.255.255
				array('2078801920' , '2079064063'), //123.232.0.0-123.235.255.255
				array('-1950089216', '-1948778497'), //139.196.0.0-139.215.255.255
				array('-1425539072', '-1425014785'), //171.8.0.0-171.15.255.255
				array('-1236271104', '-1235419137'), //182.80.0.0-182.92.255.255
				array('-770113536' , '-768606209'), //210.25.0.0-210.47.255.255
				array('-569376768' , '-564133889'), //222.16.0.0-222.95.255.255
		 );
		$rand_key = mt_rand(0, 9);
		$ip= long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));
		return $ip;
	}


	/********************************************
	*随机给出伪造过的IP，http的头文件
	**************************************/
	function header_rand(){
		$header = array( 'CLIENT-IP:58.68.44.61', 'X-FORWARDED-FOR:58.68.44.61', ); 
		$ip1= ip_rand(); $ip2= ip_rand();
		$header[0]='CLIENT-IP:'.$ip1;
		$header[1]='X-FORWARDED-FOR:'.$ip2;
		return $header;
	}




?>
	