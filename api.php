<?php

include 'db.php';

class api
{
	
	public function translate(){

		$model = new db;

		// 组装数据		
		$paramArr = $this->prepareParam();
		
		//获取待翻译翻译数据
		$toTranslate = $model->getData();

		foreach ($toTranslate as $k => $v) {
			$paramArr['q'] .= $k.'='.$v.'_';
		}

		//获取签名
		$str = db::APPID.$paramArr['q'].$paramArr['salt'].db::PRIVATEKEY;
		//补全参数
		$paramArr['sign'] = $this->genSign($str);

		//调用api
		$result = $this->callAPI(db::REQUESTURL,$paramArr);

		if(!$result)
			return false;

		$data = json_decode($result,true);

		if(isset($data['error_code']))
			return false;

		// 保存数据入库
		$translated = $data['trans_result'][0]['dst'];
		$model->saveData($translated);

		return true;
	}

	/**
	 * 组装参数
	 * @author Mark <mark@zhaomark.com>
	 * @return array
	 */
	private function prepareParam(){
		$arr = array(
			'appid' => db::APPID,
			'salt'  => rand(10000,99999),
			'from'  => db::FROM,
			'to'	=> $_GET['to'],
			'q'		=> ''
		);
		return $arr;
	}

	/**
	 * 生成签名
	 * @author Mark <mark@zhaomark.com>
	 * @param  string $str [参数串接字符串]
	 * @return string      [加密32位字符串]
	 */
	private function genSign($str = ''){
	    return md5($str);
	}

	public function callAPI($url, $paramArr=null, $method="post", $testflag = 0, $timeout = 10, $headers=array())
	{/*{{{*/
	    $ret = false;
	    $i = 0; 
	    while($ret === false) 
	    {
	        if($i > 1)
	            break;
	        if($i > 0) 
	        {
	            sleep(1);
	        }
	        $ret = $this->callOnce($url, $paramArr, $method, false, $timeout, $headers);
	        $i++;
	    }
	    return $ret;
	}/*}}}*/

	public function callOnce($url, $paramArr=null, $method="post", $withCookie = false, $timeout = 10, $headers=array())
	{/*{{{*/
	    $ch = curl_init();
	    if($method == "post") 
	    {
	        $data = $this->convert($paramArr);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	        curl_setopt($ch, CURLOPT_POST, 1);
	    }
	    else 
	    {
	        $data = convert($paramArr);
	        if($data) 
	        {
	            if(stripos($url, "?") > 0) 
	            {
	                $url .= "&$data";
	            }
	            else 
	            {
	                $url .= "?$data";
	            }
	        }
	    }
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    if(!empty($headers)) 
	    {
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    }
	    if($withCookie)
	    {
	        curl_setopt($ch, CURLOPT_COOKIEJAR, $_COOKIE);
	    }
	    $r = curl_exec($ch);
	    curl_close($ch);
	    return $r;
	}/*}}}*/

	public function convert(&$paramArr)
	{/*{{{*/
	    $data = '';
	    if (is_array($paramArr))
	    {
	        foreach ($paramArr as $key=>$val)
	        {
	            if (is_array($val))
	            {
	                foreach ($val as $k=>$v)
	                {
	                    $data .= $key.'['.$k.']='.rawurlencode($v).'&';
	                }
	            }
	            else
	            {
	                $data .="$key=".rawurlencode($val)."&";
	            }
	        }
	        return trim($data, "&");
	    }
	    return $paramArr;
	}/*}}}*/

}
