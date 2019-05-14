<?php

	global $_W,$_GPC;

	$_W['page']['sitename'] = '交易明细';

	$query = load()->object('query');
	$game_option = array(1=>'小',2=>'大',3=>'单',4=>'双');
	
	//game detail
	$game_list = $query->from($this->table['game'])->select(array('game_time','game_mchnum','game_profit','game_second','game_option','game_result'))->where(array('game_user'=>$_SESSION['ids']))->orderBy('game_time','desc')->getall();

	//recharge detail
	$krypton_list = $query->from($this->table['recharge'],'r')->select(array('r.recharge_num','r.recharge_pay','r.add_time','m.gold'))->innerjoin($this->table['meal'],'m')->on(array('r.recharge_money'=>'m.id'))->where(array('r.recharge_user'=>$_SESSION['ids'],'r.status'=>1))->orderBy('r.add_time','desc')->getall();
	//分销数据
	$member_fid = $query->from($this->table['member'])->select(array('member_fid','reward'))->where(array('member_id'=>$_SESSION['ids']))->get();

	
	include $this->template('gold_detail');