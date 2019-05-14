<?php 
	global $_W,$_GPC;

	$_W['page']['sitename'] = '排行榜';
	$num = intval($this->settings['sale_rank']);
	//公众号id
	$public_id = $_W['uniaccount']['uniacid'];
	$query = load()->object('query');
	if($num>0){
		
		$rank_list = $query->from($this->table['member'])->select(array('member_nickname','member_avatar','reward'))->where(array('public_id'=>$public_id,'is_del'=>1))->limit($num)->orderBy('reward','desc')->getall();
		
		
	}else{
		
		message('此功能未设置');
	}
	
	include $this->template('rank');