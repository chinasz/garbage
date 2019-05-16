<?php
	global $_W,$_GPC;
	
	$_W['page']['sitename'] = '佣金提现';
	$query = load()->object('query');
	$member_reward = $query->from($this->table['member'])->where(array('member_id'=>intval($_SESSION['ids']),'is_del'=>1))->getcolumn('reward');
	$cash_low = floatval($this->settings['cash_low']);
	if(empty($cash_low)){
		message('提现未设置');
		
		
	}
	if($_GPC['cash'] =='1'){
		$return = array('code'=>0);
		if($_W['isajax']){
			if(checksubmit()){
				
				$cash_fee = intval($this->settings['cash_fee']);
				$cash_time= empty($this->settings['cash_fee'])?999:intval($this->settings['cash_time']);
				if(!empty($cash_low)){
					if($cash_fee>0){
						
						$money = $member_reward - ($member_reward * $cash_fee / 100);
					}else{
						$money = $member_reward;
						
					}
					if($money<$cash_low){
						$return = array('code'=>1,'error'=>'低于最低提现金额!');
						echo json_encode($return);
						return;
						
						
					}
					$time = $query->from($this->table['cash'])->wher(array('cash_user'=>intval($_SESSION['ids']),'cash_time>='=>date("Y-m-d"),'cash_time<='=>date("Y-m-d",strtotime("+1 day"))))->count();

					if($time>=$cash_time){
						$return = array('code'=>1,'error'=>'提现次数已达上限!');
						echo json_encode($return);
						return;
						
					}
					
					$cash_insert = array(
						'cash_num'	=>	orderNum(),
						'cash_user'	=>	intval($_SESSION['ids']),
						'cash_fee'	=>	$cash_fee,
						'cash_money'=>	$member_reward,
						'cash_status'=>	2,
						'cash_time'	=>	date("Y-m-d H:i:s")
					
					);
					$member_data = array(
						'reward' => 0	
					);
					pdo_query("START TRANSACTION");
					//cash order
					$res = pdo_insert($this->table['cash'],$cash_insert);
					$res2= pdo_update($this->table['member'],$member_data,array('member_id'=>$_SESSION['ids']));
					
					if($res && $res2){
						pdo_query("COMMIT"); 
						$return['code'] =0;
						echo json_encode($return);
						return;
						
					}else{
						pdo_query("ROLLBACK"); 
						$return['code'] =1;
						$return['error']='服务器繁忙,请重试';
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
	
	