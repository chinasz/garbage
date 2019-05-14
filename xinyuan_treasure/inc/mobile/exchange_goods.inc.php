<?php
	global $_W,$_GPC;
	
	$id = intval($_GPC['id']);

	$public_id	=	$_W['uniaccount']['uniacid'];
	$query = load()->object('query');
	
	$goods = $query->from($this->table['goods'])->select(array('goods_id','goods_name','goods_thumb','goods_title','goods_score','goods_price','goods_oprice','is_real'))->where(array('goods_status'=>1,'public_id'=>$public_id,'goods_id'=>$id))->get();
	if(empty($goods)){
		
		message('不存在的商品');
		
	}
	include $this->template('exchange_goods_detail');