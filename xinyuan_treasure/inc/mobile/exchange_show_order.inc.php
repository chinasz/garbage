<?php
	global $_W,$_GPC;
	
	$query = load()->object('query');
	$id = intval($_SESSION['ids']);
	$where= array('o.order_user'=>$id);
	$field = array('g.goods_name','g.goods_thumb','o.order_id','o.order_fee','o.order_actual','o.order_poster','o.order_status');
	//all order
	$order_all_list = $query->from($this->table['order'],'o')->innerjoin($this->table['goods'],'g')->on('o.order_goods','g.goods_id')->select($field)->where($where)->getall();

	include $this->template('exchange_show_order');