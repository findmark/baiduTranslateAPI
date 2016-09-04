<?php

/**
 * 帮助函数
 * @author Mark <sylar.developer@gmail.com>
 */
switch ($_GET['action']) {
	case 'genDB': genDB(); break;
	case 'saveCSV': saveCSV(); break;
	case 'startTrans': startTrans(); break;
	case 'outputCSV': outputCSV(); break;
	default: echo 'Function not specify!'; break;
}

/**
 * 建立数据表
 * @author Mark <sylar.developer@gmail.com>
 */
function genDB(){
	
	include 'db.php';
	//检测数据库设置
	$dbConfig = array(db::DBHOST,db::DB,db::DB,db::DBTABLE);
	foreach ($dbConfig as $v) {
		if(!$v) die('Missing DB Config!! See config.php');
	}

	//连接数据库
	$con = mysqli_connect(db::DBHOST,db::DBUSER,db::DBPSW);
	if(!$con) die('Could not connect:'.mysqli_connect_error($con));

	//查看数据库是否存在
	if(mysqli_select_db($con,db::DB)) die(db::DB.' already exists!');

	//建立数据库
	mysqli_query($con,"CREATE DATABASE ".db::DB." character set utf8 COLLATE utf8_general_ci;");

	//建立表结构
	if(!mysqli_select_db($con,db::DB)) die('Could not connect: ' . mysqli_error($con));

	$sql = "CREATE TABLE ".db::DBTABLE." 
			(
				`trans_id` int(11) NOT NULL auto_increment,
				`translate_from` varchar(255) NOT NULL,
				`translate_to` varchar(255),
				PRIMARY KEY  (`trans_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8";

	if(!mysqli_query($con,$sql)) die(db::DBTABLE.' Created with error: '.mysqli_error($con));

	mysqli_close($con);
	echo 'DATABASE CREATE SUCCESSFULLY!';

}

/**
 * 保存CSV文档数据
 * @author Mark <sylar.developer@gmail.com>
 */
function saveCSV(){
	
	include 'db.php';

	$db = new db;

	$file = $_FILES['file']['tmp_name'];
	$filename = $_FILES['file']['name'];
	$dir = './uploads/';
	$path = $dir.$filename;
	move_uploaded_file($_FILES['file']['tmp_name'], $path);

	if($db->saveCSV($path)) exit('Uploaded!');
	echo 'failed, please upload again!';
	
}

/**
 * 调用API翻译接口
 * @author Mark <sylar.developer@gmail.com>
 */
function startTrans(){
	include 'api.php';
	$api = new api;
	if($api->translate())
		echo 'Translate Successfully!';
	else
		echo 'API Request Failed!';
}

/**
 * 导出翻译CSV文档
 * @author Mark <sylar.developer@gmail.com>
 */
function outputCSV(){
	include 'db.php';
	$db = new db;
	if(!$db->outPutCSV()) echo 'Failed Output, do it again!';
	echo 'Output Successfully!';
}