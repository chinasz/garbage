<?php
/*
微信 支付
*/
class pay
{

	public $cert1;
	public $cert2;
	public $secrect;
	public function __construct($secrect,$cert){
		
		$this->secrect = $secrect;
		$this->cert1 = $cert['cert'];
		$this->cert2 = $cert['key'];
	}
	
	public function sign($data){
		ksort($data);
	
		$tem = array();
		foreach ($data as $k => $v) {
			if ((string) $v === '') {
				continue;
			}
			$tem[] = "{$k}={$v}";
		}
		$tem = implode('&', $tem);
		$tem .= '&key='.$this->secrect;
		
		$tem = strtoupper(md5($tem));
		return $tem;
	}
	
	public function curl_post_ssl($url, $vars, $second=30){
		$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $second);
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);//严格校验
		//设置header
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		curl_setopt($ch,CURLOPT_SSLCERT, $this->cert1);

		curl_setopt($ch,CURLOPT_SSLKEY, $this->cert2);
		//post提交方式
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
		//运行curl
		$data = curl_exec($ch);
		//返回结果
		if($data){
			curl_close($ch);
			return $data;
		} else { 
			$error = curl_errno($ch);
			curl_close($ch);
			throw new Exception("curl出错，错误码:$error");
		}
	}
}