<?php
	global $_W,$_GPC;
	if($_W['isajax']){
		$return = array('code'=>0);
		$query = load()->object('query');

		if(checksubmit()){
			$ordid = intval($_GPC['ordid']);
			$id = $query->from($this->table['recharge'])->where(array('id'=>$ordid,'status'=>0,'recharge_user'=>$_SESSION['ids']))->getcolumn('recharge_money');
			if(empty($id)){
				$return['code'] =1;
				$return['error']='订单无效';
				echo json_encode($return);
				return;
			}
			$data = array(
				'status'	=>	1,
			);
			$meal = $query->from($this->table['meal'])->select(array('gold','pay'))->where(array('id'=>$id))->get();
			//事务
			pdo_query("START TRANSACTION");
			//修改订单状态
			$res1 = pdo_update($this->table['recharge'],$data,array('id'=>$ordid));
			$sql = "update ".tablename($this->table['member'])."set member_score = member_score+".floatval($meal['gold'])." where member_id = :member_id";
			//加余额
			$res2 = pdo_query($sql,array(':member_id'=>intval($_SESSION['ids'])));
			//三级分销
			$arr = fenxiao($this->table,$_SESSION['ids']);

			$res3=true;
			$log = true;
			
			if(is_array($arr) && !empty($arr)){
				$arr_length = count($arr);
				for($i=0;$i<=$arr_length;$i++){
					//分销比例为空不返
					if(empty($this->settings['sale'.strval($i+1)])){
						continue;
					}
					//返现金额
					$reward = intval($this->settings['sale'.strval($i+1)])*floatval($meal['pay'])/100;
					$sql2 = "update ".tablename($this->table['member'])."set reward = reward+".floatval($reward)." where member_id = :member_id";
					//返现记录
					$reward_log = array(
						'from_user'	=>	$_SESSION['ids'],
						'to_user'	=>	$arr[$i],
						'level'		=>	$i,
						'scale'		=>	$this->settings['sale'.strval($i+1)],
						'recharge'	=>	$meal['pay'],
						'money'		=>	$reward,
						'add_time'	=>	date("Y-m-d H:i:s")
					);
					if($arr[$i]){
						
						$res3 = pdo_query($sql2,array(':member_id'=>$arr[$i]));
						$log = pdo_insert($this->table['reward'],$reward_log);
					}
					
					
				}

			}
			
			
			if($res1 && $res2 && $res3 && $log){
				pdo_query("COMMIT"); 
				
			}else{
				pdo_query("ROLLBACK"); 
				$return['code'] =1;
				$return['error']='数据写入失败,请联系客服';
			}
			$return['data'] = array();
			echo json_encode($return);
			return;
		}	
	}else{
		
		echo json_encode(array('code'=>1,'error'=>'fail request'));
	}