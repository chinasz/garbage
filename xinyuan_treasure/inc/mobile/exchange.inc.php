<?php
	global $_W,$_GPC;
	$query = load()->object('query');

	$_W['page']['sitename'] = '积分商城';
	$public_id = $_W['uniaccount']['uniacid'];
	
	$class_list = $query->from($this->table['goods_class'])->select(array('class_name','class_id'))->where(array('class_status'=>1,'public_id'=>$public_id))->orderBy('class_sort','desc')->getall();
	//all goods
	$goods_all_list	= $query->from($this->table['goods'])->select(array('goods_id','goods_name','goods_score','goods_thumb'))->where(array('public_id'=>$public_id,'goods_status'=>1))->orderBy('goods_sort','desc')->getall();
	//class goods
	foreach($class_list as $k=>$v){
		
		$v['goods'] =	$query->from($this->table['goods'])->select(array('goods_id','goods_name','goods_score','goods_thumb'))->where(array('public_id'=>$public_id,'goods_status'=>1,'goods_class'=>$v['class_id']))->orderBy('goods_sort','desc')->getall();
		
		$class_list[$k]	=	$v;
	
	}
	include $this->template('exchange');