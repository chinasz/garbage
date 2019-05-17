<?php
    global $_W,$_GPC;
    $_W['page']['title'] = '游戏记录';

     /* */

     pageauth($_W['current_module']['name'],$_GPC['do']);

     /* */

    $option =array(
		1=>'小',2=>'大',3=>'单',4=>'双'
    );
    $page = empty($_GPC['page'])?1:intval($_GPC['page']);

    $pagesize = 10;

    $query = load()->object('query');
    $public_id = $_W['uniaccount']['uniacid'];

    $game_list = $query->from($this->table['game'],'g')->innerjoin($this->table['member'],'m')->on(array('g.game_user'=>'m.member_id'))->where(array('public_id'=>$public_id))->orderby('g.game_time', 'DESC')->limit(($page-1)*$pagesize,$pagesize)->getall();
    //total
    $game_total = $query->getLastQueryTotal();

    //分页html
	$limit_html = pagination($game_total,$page,$pagesize);

    include $this->template('game');