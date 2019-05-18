<?php
	global $_W,$_GPC;
	
	$_W['page']['sitename'] = '佣金提现';
	$query = load()->object('query');
	$member_reward = $query->from($this->table['member'])->where(array('member_id'=>intval($_SESSION['ids']),'is_del'=>1))->getcolumn('reward');
	$cash_low = floatval($this->settings['cash_low']);
	if(empty($cash_low)){
		message('提现未设置');
		
		
	}else if(empty($this->settings['mchid']) ||empty($this->settings['appid']) || empty($this->settings['secrect']) || !file_exists($cert['cert']) || !file_exists($cert['key'])){
				
		message('请联客服,支付参数未设置');
		
	}
	if($_GPC['cash'] =='1'){
		$return = array('code'=>0);
		if($_W['isajax']){
			if(checksubmit()){
				
				//提现手续费
				$cash_fee = intval($this->settings['cash_fee']);
				//每日提现次数
				$cash_time= empty($this->settings['cash_fee'])?999999:intval($this->settings['cash_time']);
				//请求提现金额
				$r_money = floatval($_GPC['money']);

				//自动打款参数
				$is_auto = 1;
				$auto_num = 50;

				if(!empty($cash_low)){

					if($r_money > $member_reward){
						$return = array('code'=>1,'error'=>'佣金余额不足!');
						echo json_encode($return);
						return;

					}

					//手续费
					if($cash_fee>0){
						
						$money = $member_reward - ($r_money * $cash_fee / 100);
					}else{
						$money = $r_money;
						
					}
					//最低提现
					if($r_money<$cash_low){
						$return = array('code'=>1,'error'=>'低于最低提现金额!');
						echo json_encode($return);
						return;
						
						
					}

					//提现次数
					$time = $query->from($this->table['cash'])->wher(array('cash_user'=>intval($_SESSION['ids']),'cash_time>='=>date("Y-m-d"),'cash_time<='=>date("Y-m-d",strtotime("+1 day"))))->count();
					
					if($time>$cash_time){
						$return = array('code'=>1,'error'=>'今日提现次数已达上限!');
						echo json_encode($return);
						return;
						
					}

					$orderNum = orderNum();
					//微信付款参数
					$data = array(
						'mch_appid'	=>	$this->settings['appid'],
						'mchid'	=>	$this->settings['mchid'],
						'nonce_str'=>	md5(uniqid(mt_rand(), true)),
						'partner_trade_no'=>$orderNum,
						'openid'	=>	$_SESSION['openid'],
						'check_name'=>	'NO_CHECK',
						'amount'	=>	$money * 100,
						'desc'		=>	'企业付款',
						'spbill_create_ip'=>	CLIENT_IP
					);


					//提现数据
					$cash_insert = array(
						'cash_num'	=>	$orderNum,
						'cash_user'	=>	intval($_SESSION['ids']),
						'cash_fee'	=>	$cash_fee,
						'cash_money'=>	$member_reward,
						'cash_status'=>	2,
						'cash_time'	=>	date("Y-m-d H:i:s")
					);

					if($is_auto == 1 && $r_money<=$auto_num){
						//微信打款
						
					
						$pay = new pay($this->settings['secrect'],$cert);
						
						$data['sign'] = $pay->sign($data);
						
						$data = array2xml($data);
						
						$res = xml2array($pay->curl_post_ssl($url,$data));
						//--end付款
						
						if($res['return_code'] == 'SUCCESS' && $res['result_code']=='SUCCESS'){
							//微信打款单号
							$payment_no = $res['payment_no'];
							$cash_insert = array(
								'cash_num'	=>	$orderNum,
								'cash_three_num'=>	$payment_no,
								'cash_user'	=>	intval($_SESSION['ids']),
								'cash_fee'	=>	$cash_fee,
								'cash_money'=>	$money,
								'cash_status'=>	1,
								'cash_msg'	=>	'用户提现，自动打款成功,打款金额为'.$money.'元',
								'cash_time'	=>	date("Y-m-d H:i:s"),
								'cash_otime'=>	date("Y-m-d H:i:s")
							);


						}


					}
					//－佣金
					$sql = "update ".tablename($this->table['member'])." set reward = reward - ".floatval($money)." where member_id = :member_id";
					pdo_query("START TRANSACTION");
					//cash order
					$res = pdo_insert($this->table['cash'],$cash_insert);
					$res2= pdo_query($sql,array(':member_id'=>$_SESSION['ids']));
					
					if($res && $res2){
						pdo_query("COMMIT"); 
						$return['code'] =0;
						echo json_encode($return);
						return;
						
					}else{
						pdo_query("ROLLBACK"); 
						$return['code'] =1;
						$return['error']='数据写入失败,请重试';
						echo json_encode($return);
						return;
					}
					
				}else{
					
					$return = array('code'=>1,'error'=>'cash amount not set');
					echo json_encode($return);
					return;
					
				}
			
			}
		}else{
			$return = array('code'=>1,'error'=>'param intvalid');
			echo json_encode($return);
			return;

		}
		
		
	}else{
		
		include $this->template('cash');
		
	}
	
	