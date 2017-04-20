<?
/**
* @version 1.0.0
* @CreTime:2011-06-24
* @CreName:cz
*/

require_once("var.php");
//mysql_connect连接方式
// $con=mysql_connect($mysql_var["hostname"],$mysql_var["username"],$mysql_var["password"]);
// if($con==false)
// {
// 	echo '连接服务器失败'.mysql_error();
// 	exit();
// }
// $return=mysql_select_db($mysql_var["select_db"],$con);
// if($return==false)
// {
// 	echo '无法选择数据库';
// 	exit();
// }
// mysql_query("set names 'utf8'",$con);
// 


//PDO 连接方式
$opt = array(
	PDO::ATTR_PERSISTENT => true,     //确认是否为持久连接
);

$dsn = 'mysql:dbname='.$mysql_var["select_db"].';host='.$mysql_var['hostname'].';charset=utf8';

try{
	$dbn = new PDO($dsn,$mysql_var["username"],$mysql_var["password"],$opt);
}catch(PDOException $e){
	echo '数据库链接失败:'.$e->getMessage();
	exit;
}
