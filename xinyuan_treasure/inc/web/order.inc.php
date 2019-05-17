<?php
	/*
	*订单管理
	*
	*
	*/
	global $_W,$_GPC;

	/* */

	pageauth($_W['current_module']['name'],$_GPC['do']);

	/* */


	$_W['page']['title'] = '订单管理';
	//公众号id
	$public_id = $_W['uniaccount']['uniacid'];
	
	$field = 'o.order_id,o.order_num,o.order_fee,o.order_actual,o.order_receiver,o.order_addr,o.order_phone,o.add_time,o.order_poster,o.order_status,o.order_method,m.member_nickname,m.member_avatar,g.goods_name,g.goods_thumb,g.is_real';
	
	$where = '';
	if(checksubmit()){
		if(!empty($_GPC['keyword'])){
			$where .= " and o.order_num like '%".trim($_GPC['keyword'])."%'";
		}
		if(!empty($_GPC['member'])){
			$where .= " and o.order_receiver like '%".trim($_GPC['member'])."%' or o.order_phone like '%".trim($_GPC['member'])."%'";

		}
		if(intval($_GPC['status'])>0){

			$where .= " and o.order_status = ".intval($_GPC['status']);
		}
		if(!empty($_GPC['time']['start']) && strtotime($_GPC['time']['start']) && !empty($_GPC['time']['end']) && strtotime($_GPC['end']['start'])){
			$where .= "and o.add_time >=".$_GPC['time']['start']." and o.add_time <=".$_GPC['time']['end'];
		}
	}
	
	
	$sql = "select * from ".tablename($this->table['order'])." o inner join ".tablename($this->table['member'])." m on o.order_user = m.member_id inner join ".tablename($this->table['goods'])." g on o.order_goods = g.goods_id  where o.public_id = :public_id ".$where." order by o.add_time desc";
	
	$order_list = pdo_fetchall($sql,array(':public_id'=>$public_id));
	
	$order_status = array(1=>'已完成',2=>'已发货',3=>'已付款',4=>'未付款',5=>'交易关闭');
	$payment =	array(1=>'积分余额');

	if(intval($_GPC['export']) ==1){
		$time = time();
		$filename = '订单信息'.$time;
		//导出csv
		$html = "\xEF\xBB\xBF";;
		/* 输出表头 */
		$header = array(
			'member_nickname' => '用户',
			'order_receiver' => '收货人',
			'order_addr' => '收货地址',
			'order_phone' => '收货手机号',
			'order_status' => '订单状态',
			'add_time'	=>	'下单时间'
		);
		foreach ($header as $key => $title) {
			$html .= $title ."\t,";
		}
		$html .= "\n";
		foreach($order_list as $order){
			foreach($header as $key=>$title){
				if($key == 'member_nickname'){
					$html .= trim(str_replace(',','',$order[$key]))."\t, ";
				}elseif($key == 'order_status'){
					$html .= $order_status[$order[$key]]."\t, ";
				}else{
					$html .= $order[$key]."\t, ";
				}	
			}
			$html .= "\n"; 
		}
		
		/* 输出CSV文件 */
		header("Content-type:text/csv");
		header("Content-Disposition:attachment; filename=".$filename.".csv");
		echo $html;
		exit();
	/*-end 导出-*/
	}
	
	include $this->template('manage_order');