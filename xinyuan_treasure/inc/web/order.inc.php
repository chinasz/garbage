<?php
	/*
	*订单管理
	*
	*
	*/
	global $_W,$_GPC;
	$_W['page']['title'] = '订单管理';
	//公众号id
	$public_id = $_W['uniaccount']['uniacid'];
	
	$field = 'o.order_id,o.order_num,o.order_fee,o.order_actual,o.order_receiver,o.order_addr,o.order_phone,o.add_time,o.order_poster,o.order_status,o.order_method,m.member_nickname,m.member_avatar,g.goods_name,g.goods_thumb';
	
	
	
	$where = '';
	$query = load()->object('query');
	
	
	$sql = "select * from ".tablename($this->table['order'])." o inner join ".tablename($this->table['member'])." m on o.order_user = m.member_id inner join ".tablename($this->table['goods'])." g on o.order_goods = g.goods_id  where o.public_id = :public_id order by o.add_time desc";
	
	$order_list = pdo_fetchall($sql,array(':public_id'=>$public_id));
	
	$order_status = array(1=>'已完成',2=>'已发货',3=>'已付款',4=>'未付款',5=>'交易关闭');
	$payment =	array(1=>'积分余额');
	
	include $this->template('manage_order');