<?php 
	global $_W,$_GPC;

	$_W['page']['sitename'] = '分销海报';
	if($this->settings['poster_status'] == 0){
		
		message('海报未开启');
		
	}
	

	include $this->template('poster');