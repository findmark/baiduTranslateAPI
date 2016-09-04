<?php 

include 'config.php';
use \app\config\config as Config;

class db extends Config
{
	private $db = Config::DB;
	private $db_table = Config::DBTABLE;
	
	/**
	 * 获取数据
	 * @author Mark <sylar.developer@gmail.com>
	 * @return array 待翻译数组
	 */
	public function getData(){
		
		$con = $this->selectDB();
		$sql = "SELECT * FROM ".$this->db_table."";
		$result = mysqli_query($con, $sql);

		while($row = mysqli_fetch_array($result)){
			$data[$row['trans_id']] = $row['translate_from'];
		}
		return $data;
	}

	/**
	 * 保存csv数据
	 * @author Mark <sylar.developer@gmail.com>
	 * @param  string $path [文件路径]
	 * @return boolean
	 */
	public function saveCSV($path = ''){
		if(filesize($path) == 0){
		    unlink($path);
			return false;
		}

		$con = $this->deleteData(); 
		if(!$con) 
			return false;

	    $handle = fopen($path,"r");
	    while ($data = fgetcsv($handle,1000,",","'")){
	        if ($data[0]) {
	        	$sql = "INSERT INTO ".$this->db_table." (translate_from) VALUES ('".$data[0]."')";
	        	mysqli_query($con,$sql);
	        } 
	    };

		mysqli_close($con);
	    fclose($handle);
	    unlink($path);
	    return true;
	}

	/**
	 * 清空表数据
	 * @author Mark <sylar.developer@gmail.com>
	 * @return boolean
	 */
	private function deleteData(){

		$con = $this->selectDB();
		
		$sql = "SELECT * FROM ".$this->db_table."";
		$result = mysqli_query($con,$sql);
		if($result !== 0){
			$sql = "DELETE FROM ".$this->db_table."";
			$deleted = mysqli_query($con,$sql);

			if($deleted !== false)
				return $con;
			else
				return false;
		}

		return $con;
	}

	/**
	 * 连接数据库
	 * @author Mark <sylar.developer@gmail.com>
	 * @return boolean/string
	 */
	private function selectDB(){
		$con = mysqli_connect(Config::DBHOST,Config::DBUSER,Config::DBPSW,$this->db);
		if(!$con) die('Could not connect:'.mysqli_connect_error($con));
		mysqli_query($con,"SET NAMES UTF8");
		$selected = mysqli_select_db($con,$this->db);
		if($selected)
			return $con;
		else
			die(mysqli_error($con));
	}

	/**
	 * 导出csv文件
	 * @author Mark <sylar.developer@gmail.com>
	 * @return boolean
	 */
	public function outPutCSV(){
		
		$data = $this->prepareContent();
		foreach ($data as $k => $v) {
			$rows .= '"'.$data[$k].'","'.$v.'"'."\n";
		}
		$filename = date('YmdHis');
		file_put_contents('./uploads/'.$filename.'_translate.csv', $rows);
		return true;
	}

	/**
	 * 获取表数据
	 * @author Mark <sylar.developer@gmail.com>
	 * @return array 翻译后的数据
	 */
	private function prepareContent(){
		$con = $this->selectDB();
		$sql = "SELECT * FROM ".$this->db_table."";
		$result = mysqli_query($con, $sql);

		while($row = mysqli_fetch_array($result)){
			$data[$row['translate_from']] = $row['translate_to'];
		}
		
		mysqli_close($con);
		return $data;
	}

	/**
	 * 保存翻译结果
	 * @author Mark <sylar.developer@gmail.com>
	 * @param string $data [翻译后的字符串]
	 * @return boolean
	 */
	public function saveData($str=''){
		$str = substr($str,0,-1);
		$tmp = explode("_",$str);
		foreach ($tmp as $v) {
			$arr = explode('=', $v);
			$rows[trim($arr[0])] = $arr[1];
		}
		$con = $this->selectDB();
		foreach ($rows as $k => $v) {
			$sql = "UPDATE ".$this->db_table." SET translate_to = '".$v."' WHERE trans_id = '".$k."'";
			mysqli_query($con,$sql);
		}
		
		return true;
	}

}

