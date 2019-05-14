<?php
	global	$_W,$_GPC;

	$_W['page']['sitename'] = '分销海报';

	$switch = $this->settings['poster_status'];
	if($switch == 1){
		
		include $this->template('poster');
	}else{
		
		message('此功能未开启');
	}