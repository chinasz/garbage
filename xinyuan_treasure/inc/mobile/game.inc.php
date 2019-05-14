<?php
	global $_W,$_GPC;
	$return = array('code'=>0,'error'=>'','data'=>array(),'msg'=>'');
	$font = array(
		'option'	=>	array(
			1=>'小',2=>'大',3=>'单',4=>'双'
		)
	);
	
	$cert = array(
		'cert'	=>	MODULE_ROOT.'/cert/'.md5('cert'.$_W['uniaccount']['uniacid']).'.pem',
		'key'	=>	MODULE_ROOT.'/cert/'.md5('key'.$_W['uniaccount']['uniacid']).'.pem',
	);
	$query = load()->object('query');
	if($_W['isajax']){
		if(checksubmit()){
			
			//支付参数
			if(empty($this->settings['mchid']) ||empty($this->settings['appid']) || empty($this->settings['secrect']) || !file_exists($cert['cert']) || !file_exists($cert['key'])){
				
				$return['code'] =	1;
				$return['error']=	'';
				$return['msg']	=	'请联客服,参数未设置';
				echo json_encode($return);
				return ;
				
			}
			
			$game_fee = 1;//游戏手续费
			$game_option = intval($_GPC['number']);//大小
			$game_second = intval($_GPC['time']);//次数
			if($game_second>0 && $game_option>0){
				
				$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
				$orderNum = orderNum();
				$game_spend = ($game_second*1)+1;//游戏金额
				$game_profit = $game_second*10;//游戏收益
				$member_id = $_SESSION['ids'];
				
				$member_score = $query->from($this->table['member'])->where(array('member_id'=>$member_id))->getcolumn('member_score');
				
				if(intval($member_score)<$game_spend+$game_profit){
					$return['code'] =	1;
					$return['msg']	=	'积分不足';
					echo json_encode($return);
					return ;	
				}else{
					//-积分
					$setDec_sql = "update ".tablename($this->table['member'])."set member_score = member_score-".floatval($game_spend)." where member_id = :member_id";
					$result = pdo_query($setDec_sql,array(':member_id'=>intval($member_id)));
					if(empty($result)){
						$return['code'] =	1;
						$return['msg']	=	'手续费扣除失败';
						echo json_encode($return);
						return ;	
					}
					
					//微信付款参数
					$data = array(
						'mch_appid'	=>	$this->settings['appid'],
						'mchid'	=>	$this->settings['mchid'],
						'nonce_str'=>	md5(uniqid(mt_rand(), true)),
						'partner_trade_no'=>$orderNum,
						'openid'	=>	$_SESSION['openid'],
						'check_name'=>	'NO_CHECK',
						'amount'	=>	100,
						'desc'		=>	'企业付款',
						'spbill_create_ip'=>	CLIENT_IP
					);
					
					
					
					//微信付款
					
					$pay = new pay($this->settings['secrect'],$cert);
					
					$data['sign'] = $pay->sign($data);
					
					$data = array2xml($data);
					
					$res = xml2array($pay->curl_post_ssl($url,$data));
					//--end付款
					
					if($res['return_code'] == 'SUCCESS' && $res['result_code']=='SUCCESS'){
						$payment_no = $res['payment_no'];
						$payment_no_suffix = intval(substr($payment_no,-1,1));
						$num = substr($payment_no,0,strlen($payment_no)-1);
						//增加或减少
						$symbol = '+';
						if($payment_no_suffix % 2 == 0 && $game_option==4){
							//结果为双
							$game_result = 2;
							
						}elseif($payment_no_suffix % 2 != 0 && $game_option==3){
							//结果为单
							$game_result = 2;
							
						}elseif($payment_no_suffix >=5 && $payment_no_suffix<=9 && $game_option==2){
							//结果为大
							$game_result = 2;
						}elseif($payment_no_suffix>=0 && $payment_no_suffix<=4 && $game_option==1){
							//结果为小
							$game_result = 2;
						}else{
							$symbol = '-';
							$game_result = 1;
						}
						
						$game_data = array(
							'game_num'	=>	$res['partner_trade_no'],
							'game_mchnum'=>	$payment_no,
							'game_spend'=>	$game_spend,
							'game_profit'=>	$game_profit,
							'game_user'	=>	$member_id,
							'game_second'=>	$game_second,
							'game_option'=>	$game_option,
							'game_time'	=>	date('Y-m-d H:i:s'),
							'game_result'=>	$game_result,
							'game_over_time'=>date('Y-m-d H:i:s'),
							'public_id'	=>	$_W['uniaccount']['uniacid']
						);
						//游戏记录
						$res = pdo_insert($this->table['game'],$game_data);
						
						if($res){
							$sql = "update ".tablename($this->table['member'])."set member_score = member_score".$symbol.$game_profit." where member_id = :member_id";
							$result = pdo_query($sql,array(':member_id'=>intval($member_id)));
							if(!empty($res)){
								//success return
								$return['data'] = array('result'=>$game_result,'money'=>$game_profit,'option'=>$font['option'][$game_option],'num'=>$num,'suffix'=>$payment_no_suffix,'second'=>$game_second);
								echo json_encode($return);
								return;
							}else{
								//积分加减失败
								$return['code']	=	1;
								$return['msg']	=	'系统出错，请联系客服';
								echo json_encode($return);
								return;
							}
						}else{
							//游戏记录存储失败
							$return['code']	=	1;
							$return['msg']	=	'系统出错，请联系客服';
							echo json_encode($return);
							
						}
						
					}else{
						$setDec_sql = "update ".tablename($this->table['member'])."set member_score = member_score+".floatval($game_spend)." where member_id = :member_id";
						$result = pdo_query($setDec_sql,array(':member_id'=>intval($member_id)));
						//微信付款  失败
						echo json_encode(array('code'=>1,'error'=>$res['err_code'],'msg'=>$res['err_code_des']."请联系客服"));
					}	
					
					
				}
	
			}else{
				$return['code'] =	1;
				$return['error']=	'';
				$return['msg']	=	'参数错误';
				echo json_encode($return);
				return ;
			}
			
		}	
		
	}else{
		
		
		
	}