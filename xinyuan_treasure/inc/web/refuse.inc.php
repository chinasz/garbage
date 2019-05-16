<?php
/**拉黑
*/
    global $_W,$_GPC;
	if(!$_W['isajax']){
		return message('页面不存在','','error');
	}
	$query = load()->object('query');
	
	//公众号id
	$public_id = $_W['uniaccount']['uniacid'];
	if(checksubmit()){
		
		$nickname = $query->from($this->table['member'])->where(array('member_id'=>intval($_GPC['id']),'is_del'=>intval($_GPC['status']),'public_id'=>$public_id))->getcolumn('member_nickname');
		if(empty($nickname)){
			echo json_encode(['data'=>'','error'=>'不存在的用户']);
			
		}else{
			$data = array(
                'is_del'    =>  intval($_GPC['status'])==1?0:1,
            );
            $res = pdo_update($this->table['member'], $data,$condition = array('member_id'=>intval($_GPC['id'])));
			if(!empty($res)){
				
                echo json_encode(['code'=>0,'data'=>$data['is_del']]);
                return;
			}else{
                echo json_encode(['code'=>1,'error'=>'失败']);
                return;
			}
			
		}
	}