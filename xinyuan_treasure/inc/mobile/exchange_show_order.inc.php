<?php
	global $_W,$_GPC;
	$_W['page']['sitename'] = '我的兑换';
	$public_id = $_W['uniaccount']['uniacid'];
	$query = load()->object('query');
	$id = intval($_SESSION['ids']);
	$where = '';
	$status = empty($_GPC['status'])?'':intval($_GPC['status']);
	
	if(in_array($status,array(1,2,3))){
		$where .= ' and o.order_status = '.$status;
	}

	$field = 'g.goods_name,g.goods_thumb,g.is_real,g.goods_stock,g.goods_score,o.order_id,o.order_fee,o.order_actual,o.order_poster,o.order_status,c.class_name';
	//all order
	$sql = "select * from ".tablename($this->table['order'])." o inner join ".tablename($this->table['goods'])." g on o.order_goods = g.goods_id inner join ".tablename($this->table['goods_class'])." c on c.class_id = g.goods_class where o.order_user = :user".$where." order by o.add_time";

	$order_all_list = pdo_fetchall($sql,array(':user'=>$id));

	//$order_all_list = $query->from($this->table['order'],'o')->innerjoin($this->table['goods'],'g')->on('o.order_goods','g.goods_id')->select($field)->where($where)->getall();

	//推荐
	$recommend_list = $query->from($this->table['goods'])->where(array('public_id'=>$public_id,'goods_status'=>1))->limit(2)->getall();

	include $this->template('exchange_show_order');