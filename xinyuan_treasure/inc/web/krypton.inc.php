<?php 
	global $_W,$_GPC;

	/* */

	pageauth($_W['current_module']['name'],$_GPC['do']);

	/* */

	$_W['page']['title'] = '充值记录';
	
	$query = load()->object('query');
	$where = '';
	$condition = "";
	$page = empty($_GPC['page'])?1:intval($_GPC['page']);
	
	$pagesize = 10;
	/*查询条件*/
	if(checksubmit()){
		$keyword = empty($_GPC['keyword'])?"":trim($_GPC['keyword']);
		$status = empty($_GPC['status'])?"":intval($_GPC['status']);
		$starttime = $_GPC['time']['start'];
		$endtime = $_GPC['time']['end'];

		if($keyword){$condition.="r.recharge_num like '".$keyword."%'  and";}
		if(!empty($status) or status === 0){ $condition.='r.status = '.$status.' and';}
		
		if(!empty($starttime) && strtotime($starttime)){ $condition .= " r.add_time >= '".$starttime."' and";}
		if(!empty($endtime) && strtotime($endtime)){ $condition .= " r.add_time <= '".$endtime."' and";}

	}
	
	if(empty($condition)){
		
		$where ="where r.public_id =".intval($_W['uniaccount']['uniacid']);
	}else{
		$condition = rtrim($condition,"and");
		$where .= "where r.public_id =".intval($_W['uniaccount']['uniacid'])." and ".$condition;
	}
	
	/*end查询条件*/
	$field = 'r.recharge_num,m.member_nickname,m.member_avatar,l.gold,l.pay,r.recharge_pay,r.payment,r.remark,r.add_time,r.status'; 
	//充值记录sql
	$krypton_sql = "select ".$field." from ".tablename($this->table['recharge'])." r inner join ".tablename($this->table['member'])." m on r.recharge_user = m.member_id inner join ".tablename($this->table['meal'])." l on r.recharge_money = l.id ".$where." order by r.add_time desc limit ".($page-1)*$pagesize.",".$pagesize;
	//分页总数
	$count_sql = "select count(r.id) from ".tablename($this->table['recharge'])." r inner join ".tablename($this->table['member'])." m on r.recharge_user = m.member_id inner join ".tablename($this->table['meal'])." l on r.recharge_money = l.id ".$where;
	
	$krypton_list = pdo_fetchall($krypton_sql);
	$krypton_total = pdo_fetchcolumn($count_sql);
	//分页html
	$limit_html = pagination($krypton_total,$page,$pagesize);

	include $this->template('manage_krypton');