<?php
	global $_W,$_GPC;
	
	$_W['page']['sitename'] = empty($this->settings['game_name'])?'开始游戏':$this->settings['game_name'];
	
	$option =array(
		1=>'小',2=>'大',3=>'单',4=>'双'
	);
	
	$query = load()->object('query');
	
	$member_score = $query->from($this->table['member'])->where(array('member_id'=>$_SESSION['ids']))->getcolumn('member_score');
	//最近10次游戏
	$game10 = $query->from($this->table['game'])->select(array('game_mchnum','game_profit','game_second','game_option','game_time','game_result'))->where(array('game_user'=>$_SESSION['ids']))->orderBy('game_time','desc')->limit(10)->getall();

	include $this->template('game');