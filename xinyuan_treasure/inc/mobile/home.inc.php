<?php
	global $_W,$_GPC;
	$_W['page']['sitename'] = '首页';
	
	$query  = load()->object('query');
	$member = $query->from($this->table['member'])->select(array('member_score','member_fid','reward'))->where(array('member_id'=>$_SESSION['ids']))->get();
	include $this->template('home_page');