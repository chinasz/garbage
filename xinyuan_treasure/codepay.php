<?php
	class codepay{
		public $codeurl = "https://codepay.fateqq.com/creat_order/";
		public $codepay_id;
		public $codepay_key;
		public function __construct($codepay_id,$codepay_key){
			
			$this->codepay_id = $codepay_id;
			$this->codepay_key = $codepay_key;
		}
		public function pay($orderNum,$money,$notify_url,$return_url,$param=""){
			$data = array(
				"id" => $this->codepay_id,//你的码支付ID
				"pay_id" => $orderNum,
				"type" => 3,//1支付宝支付 3微信支付 2QQ钱包
				"price" => $money,//金额
				"param" => $param,//自定义参数
				"notify_url"=>$notify_url,//通知地址
				'return_url'=>$return_url,
			);
			$sign = $this->sign($data);
			$query = $sign['query']. '&sign=' . md5($sign['sign'] .$this->codepay_key);
			$url = $this->codeurl.'?'.$query; 
			return $url;
		}
		public function sign($data){
			ksort($data);
			reset($data); //内部指针指向数组中的第一个元素
			$sign = ''; 
			$urls = '';
			foreach ($data as $key => $val) {
				if ($val == ''||$key == 'sign') continue; 
				if ($sign != '') {
					$sign .= "&";
					$urls .= "&";
				}
				$sign .= "$key=$val";
				$urls .= "$key=" . urlencode($val);

			}
			return array('sign'=>$sign,'query'=>$urls);
		}
		
		public function notify($notify_data){
			$sign = $this->sign($notify_data)['sign'];
			if (!$notify_data['pay_no'] || md5($sign . $this->codepay_key) != $_POST['sign']) {
				return false;
			} else { 

				$pay['pay_id'] = $notify_data['pay_id']; 
				$pay['money'] = (float)$notify_data['money']; 
				$pay['price'] = (float)$notify_data['price'];
				$pay['param'] = $notify_data['param'];
				$pay['pay_no'] = $notify_data['pay_no'];
				return $pay;
			}
			
		}
	}