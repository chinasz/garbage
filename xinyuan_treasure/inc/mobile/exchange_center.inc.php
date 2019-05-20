<?php
	global $_W,$_GPC;
	$_W['page']['sitename'] = '个人中心';
	$query = load()->object('query');
	$query->from($this->table['member'])->where(array('member_id'=>$_SESSION['ids']))->get();
	
	include $this->template('exchange_center');