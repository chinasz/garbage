<?php 
	global $_W,$_GPC;
	if($_W['isajax']){
		$query = load()->object('query');
		$return = array('code'=>0);
		if(checksubmit()){
			$id = intval($_GPC['id']);
			//$query->from($this->table['recharge'])->select(array('recharge_pay','id','recharge_num'))->where();
			$recharge_list = $query->from($this->table['meal'])->select(array('pay'))->where(array('id'=>$id))->orderBy('gold')->get();
			
			if($this->settings['codepay']['status'] == 1 && !empty($this->settings['codepay']['id']) && !empty($this->settings['codepay']['key'])){
				$payment = 'code';
				
				
				
			}else{
				$payment = 'wechat';
				
			}
			
			if(!empty($recharge_list)){
				//充值订单
				$data = array(
					'recharge_num'	=>	orderNum(),
					'recharge_user'	=>	$_SESSION['ids'],
					'recharge_money'=>	$id,
					'recharge_pay'	=>	$recharge_list['pay'],
					'remark'		=>	'用户充值',
					'payment'		=>	$payment,
					'add_time'		=>	date('Y-m-d H:i:s'),
					'public_id'		=>	$_W['uniaccount']['uniacid']
				);
				
				$res = pdo_insert($this->table['recharge'],$data);
				if($res){
					$recharge_id = pdo_insertid();
					$return['data']	=	array('id'=>$recharge_id,'num'=>$data['recharge_num'],'fee'=>$data['recharge_pay']);
					
				}else{
					
					$return['code'] = 1;
					$return['error']= '订单生成失败';
				}
				echo json_encode($return);
				return;
			}else{
				$return['code']=1;
				$return['error']='充值金额无效';
				echo json_encode($return);
				return;
			}
			
		}
	}else{
		
		echo json_encode(array('code'=>1,'error'=>'fail request'));
		return;
	}