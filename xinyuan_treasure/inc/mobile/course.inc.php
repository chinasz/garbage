<?php
	global $_W,$_GPC;
	$_W['page']['sitename'] = '新手教程';
	//公众号id
	$public_id = $_W['uniaccount']['uniacid'];
	
	$query = load()->object('query');
	$field = array('course_title','course_text');
	$course_list = $query->from($this->table['course'])->select($field)->where(array('is_show'=>1,'public_id'=>$public_id))->orderby('course_sort','desc')->getall();
	
	
	include $this->template('course');