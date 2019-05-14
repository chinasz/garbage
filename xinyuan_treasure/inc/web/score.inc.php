<?php
	global $_W,$_GPC;
	if(!$_W['isajax']){
		return message('页面不存在','','error');
	}
	$query = load()->object('query');
	//公众号id
	$public_id = $_W['uniaccount']['uniacid'];
	if($_GPC['token'] == token()){
		
		$nickname = $query->from($this->table['member'])->where(array('member_id'=>intval($_GPC['id']),'public_id'=>$public_id))->getcolumn('member_nickname');
		if(empty($nickname)){
			echo json_encode(['data'=>'','error'=>'不存在的用户']);
			
		}else{
			
			$sql = "update ".tablename($this->table['member'])."set member_score = member_score+".floatval($_GPC['score'])." where member_id = :member_id";
			$res = pdo_query($sql,array(':member_id'=>intval($_GPC['id'])));
			if(!empty($res)){
				
				echo json_encode(['data'=>'','error'=>'','msg'=>'充值成功']);
			}else{
				echo json_encode(['data'=>'','error'=>'充值失败']);
			}
		}
	}else{
		
		
	}