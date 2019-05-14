<?php
	global $_W,$_GPC;
	$return = array('code'=>0,'error'=>'','data'=>array());
	$public_id = $_W['uniaccount']['uniacid'];
	$poster	=	0;
	$query = load()->object('query');
	$goods_num = 1;
	
	if(!$_W['isajax']){
		$return['code']	=	1;
		$return['error']=	'request fail';
		echo json_encode($return);
		return;
	}
	if(!checksubmit()){
		$return['code']	=	1;
		$return['error']=	'request invalid';
		echo json_encode($return);
		return;
	}
	$data = array(
		'id'	=>	intval($_GPC['id']),
		'member'=>	intval($_SESSION['ids']),
		'phone'=>	trim($_GPC['phone']),
	);
	
	//验证规则
	$rule = array(
		'id'	=>	'required',
		'member'=>	'required',
		'phone'	=>	'maxlen:11'
	);
	$message=array(
		'id.required'	=>	'param invalid',
		'member.required'=>	'param invalid',
		'phone.maxlen:11'	=>	'param invalid',	
	);
	$form = new form($data,$rule,$message);

	if($form->check()){
		$error = $form->getError();
		if(!empty($error)){
			$return['code']	=	1;
			$return['error']=	$error[0];
			echo json_encode($return);
			return;
		}
		
		$member_score = $query->from($this->table['member'])->where(array('member_id'=>$data['member'],'public_id'=>$public_id))->getcolumn('member_score');
		$goods = $query->from($this->table['goods'])->select(array('goods_stock','goods_score'))->where(array('public_id'=>$public_id,'goods_status'=>1))->get();
		
		$order_price = intval($goods['goods_score']) * $goods_num + $poster;
		
		if(!empty($goods) && intval($member_score)>=0){			
			if(intval($member_score)<$order_price){
				$return['code']	=	1;
				$return['error']=	'积分不足';
				echo json_encode($return);
				return;
				
			}
			if(intval($goods['goods_stock'])<1){
				
				$return['code']	=	1;
				$return['error']=	'库存不足';
				echo json_encode($return);
				return;
				
				
			}
			
			$insert=array(
				'order_num'	=>	orderNum(),
				'order_type'=>	1,
				'order_goods'=>	$data['id'],
				'order_fee'	=>	$goods['goods_score'],
				'order_remark'=>'',
				'order_user'=>	$data['member'],
				'order_actual'=>$order_price,
				'order_addr'  =>trim($_GPC['addr']),
				'order_receiver'=>trim($_GPC['receiver']),
				'order_phone'=>$data['phone'],
				'order_poster'=>$poster,
				'order_status'=>3,
				'order_method'=>1,
				'add_time'	=>	date("Y-m-d H:i:s"),
				'public_id'	=>	$public_id
			);
			
			//事务
			pdo_query("START TRANSACTION");
			
			$_order = pdo_insert($this->table['order'],$insert);
			$sql = "update ".tablename($this->table['member'])."set member_score = member_score-".floatval($order_price)." where member_id = :member_id";
			$_member = pdo_query($sql,array(':member_id'=>$data['member']));
			if($_member && $_order){
				pdo_query("COMMIT"); 
				echo json_encode($return);
				return;
	
			}else{
				pdo_query("ROLLBACK"); 
				$return['code'] =1;
				$return['error']='下单失败';
				echo json_encode($return);
				return;
			}
			
			
		}else{
			
			$return['code']	=	1;
			$return['error']=	'参数错误';
			echo json_encode($return);
			return;
			
		}
	}