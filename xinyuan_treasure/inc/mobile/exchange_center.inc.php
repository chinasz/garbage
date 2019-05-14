<?php
	global $_W,$_GPC;
	
	$query = load()->object('query');
	$query->from($this->table['member'])->where(array('member_id'=>$_SESSION['ids']))->get();
	
	include $this->template('exchange_center');