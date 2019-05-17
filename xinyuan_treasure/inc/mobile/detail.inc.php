<?php

	global $_W,$_GPC;

	$_W['page']['sitename'] = '代理明细';

	$query = load()->object('query');
	//$game_option = array(1=>'小',2=>'大',3=>'单',4=>'双');
	
	//game detail
	//$game_list = $query->from($this->table['game'])->select(array('game_time','game_mchnum','game_profit','game_second','game_option','game_result'))->where(array('game_user'=>$_SESSION['ids']))->orderBy('game_time','desc')->getall();

	//recharge detail
	//$krypton_list = $query->from($this->table['recharge'],'r')->select(array('r.recharge_num','r.recharge_pay','r.add_time','m.gold'))->innerjoin($this->table['meal'],'m')->on(array('r.recharge_money'=>'m.id'))->where(array('r.recharge_user'=>$_SESSION['ids'],'r.status'=>1))->orderBy('r.add_time','desc')->getall();
	//分销数据
	//$member_fid = $query->from($this->table['member'])->select(array('member_fid','reward'))->where(array('member_id'=>$_SESSION['ids']))->get();

	//代理信息
	//佣金余额，上级,积分余额
	$member = $query->from($this->table['member'])->select(array('member_score','reward','member_fid'))->where(array('member_id'=>$_SESSION['ids']))->get();
	//今日佣金,总佣金,佣金记录
	
	$tody_reward_sql = "select SUM(money) from ".tablename($this->table['reward'])." where to_user = :to_user and add_time >= :start_time and add_time < :end_time";

	$tody_reward = pdo_fetchcolumn($tody_reward_sql,array(':to_user'=>$_SESSION['ids'],':start_time'=>date("Y-m-d"),':end_time'=>date("Y-m-d",strtotime("+1 day"))));

	$total_reward_sql = "select SUM(money) from ".tablename($this->table['reward'])." where to_user = :to_user";
	$total_reward = pdo_fetchcolumn($total_reward_sql,array(':to_user'=>$_SESSION['ids']));

	$rewad_log = $query->from($this->table['reward'])->where(array('to_user'=>$_SESSION['ids']))->getall();

	
	include $this->template('detail');