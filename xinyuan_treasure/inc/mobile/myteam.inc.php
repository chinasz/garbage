<?php
    global $_W,$_GPC;
    $query = load()->object('query');
    $public_id = $_W['uniaccount']['uniacid'];
    $field = array('r.money','r.add_time','m_member_avatar');
    $on = array('r.from_user'=>'m.member_id');
    //一级
    $reward_list1 = $query->from($this->table['reward'],'r')->innerjoin($this->table['member'],'m')->on($on)->where(array('r.to_user'=>$_SESSION['ids'],'r.level'=>0,'m.public_id'=>$public_id))->getall();

    //二级
    $reward_list2 = $query->from($this->table['reward'],'r')->innerjoin($this->table['member'],'m')->on($on)->where(array('r.to_user'=>$_SESSION['ids'],'r.level'=>1,'m.public_id'=>$public_id))->getall();

    //三级
    $reward_list3 = $query->from($this->table['reward'],'r')->innerjoin($this->table['member'],'m')->on($on)->where(array('r.to_user'=>$_SESSION['ids'],'r.level'=>2,'m.public_id'=>$public_id))->getall();

    include $this->template('myteam');