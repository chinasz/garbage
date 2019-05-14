<?php
	global $_W,$_GPC;
	
	$public_id = $_W['uniaccount']['uniacid'];

	$_W['page']['sitename'] = '充值';
	$query  = load()->object('query');
	$member_score = $query->from($this->table['member'])->where(array('member_id'=>$_SESSION['ids']))->getcolumn('member_score');

	$recharge_list = $query->from($this->table['meal'])->where(array('public_id'=>$public_id))->orderBy('gold')->getall();
	if(empty($recharge_list)){
		message('充值未设置');
	}
	//payment

	if($this->settings['codepay']['status'] == 1 && !empty($this->settings['codepay']['id']) && !empty($this->settings['codepay']['key'])){
		$payment = 'code';
		
		
		
	}else{
		$payment = 'wechat';
		
	}

	
	include $this->template('krypton_gold');