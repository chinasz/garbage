<?php
	/*
	*会员管理
	*
	*/
	global $_W,$_GPC;

	/* */

	pageauth($_W['current_module']['name'],$_GPC['do']);

	/* */

	$_W['page']['title'] = '会员管理';
	//公众号id
	$public_id = $_W['uniaccount']['uniacid'];
	
	$query = load()->object('query');
	//会员总数
	$total_member = $query->from($this->table['member'])->where(array('public_id'=>$public_id))->count();
	//今日登陆会员
	$a = array('member_lasttime >='=>date('Y-m-d'),'member_lasttime <='=>date("Y-m-d",strtotime("+1 day")),'public_id'=>$public_id);
	$today_member = count($query->from($this->table['member'])->select('member_id')->where($a)->getall());
	
	$fields = array('member_id','member_nickname','member_fid','member_avatar','member_score','member_firsttime','is_del');
	$where = array('public_id'=>$public_id);
	if(checksubmit()){
		//搜索条件
		if(!empty($_GPC['status']) && in_array($_GPC['status'],array(0,1))){
			$where['is_del']	=	intval($_GPC['status']);		
		}
		
		if(!empty($_GPC['keyword'])){
			$where['member_nickname like']	=	'%'.trim($_GPC['keyword']).'%';
			//$whereOr['member_id']	=	'%'.intval($_GPC['keyword']).'%';
		}
		
	}
	$member_list = $query->from($this->table['member'])->select($fields)->where($where)->orderby('member_firsttime')->getall();

	if(intval($_GPC['export']) ==1){
		$time = time();
		$filename = '会员信息'.$time;
		//导出csv
		$html = "\xEF\xBB\xBF";;
		/* 输出表头 */
		$header = array(
			'member_id' => 'id',
			'member_nickname' => '昵称',
			'member_fid' => '上级',
			'member_score' => '可用余额',
			'is_del' => '状态'
		);
		foreach ($header as $key => $title) {
			$html .= $title ."\t,";
		}
		$html .= "\n";
		foreach($member_list as $member){
			foreach($header as $key=>$title){
				if($key == 'member_nickname'){
					$html .= trim(str_replace(',','',$member[$key]))."\t, ";
				}elseif($key == 'is_del'){
					$html .= $member[$key]=='1'?'正常':'黑名单'."\t, ";
				}else{
					$html .= $member[$key]."\t, ";
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
	
	include $this->template('manage_member');