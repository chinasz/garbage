<?php
    global $_W,$_GPC;
    $_W['page']['sitename'] = '我的团队';

    $query = load()->object('query');
    $public_id = $_W['uniaccount']['uniacid'];


    // $field = array('r.money','r.add_time','m_member_avatar');
    // $on = array('r.from_user'=>'m.member_id');
    // //一级
    // $reward_list1 = $query->from($this->table['reward'],'r')->innerjoin($this->table['member'],'m')->on($on)->where(array('r.to_user'=>$_SESSION['ids'],'r.level'=>0,'m.public_id'=>$public_id))->getall();

    // //二级
    // $reward_list2 = $query->from($this->table['reward'],'r')->innerjoin($this->table['member'],'m')->on($on)->where(array('r.to_user'=>$_SESSION['ids'],'r.level'=>1,'m.public_id'=>$public_id))->getall();

    // //三级
    // $reward_list3 = $query->from($this->table['reward'],'r')->innerjoin($this->table['member'],'m')->on($on)->where(array('r.to_user'=>$_SESSION['ids'],'r.level'=>2,'m.public_id'=>$public_id))->getall();

    //下三级
    $level1 = array();
    $level2 = array();
    $level3 = array();
    $total1 = 0;
    $total2 = 0;
    $total3 = 0;


    $level1= $query->from($this->table['member'])->select(array('member_id','member_firsttime'))->where(array('member_fid'=>$_SESSION['ids'],'public_id'=>$public_id))->orderby('member_firsttime','desc')->getall();

    if($level1 && is_array($level1)){
        foreach ($level1 as $value) {
            $res = $query->from($this->table['member'])->select(array('member_id'))->where(array('member_fid'=>$value['member_id'],'public_id'=>$public_id))->get();
            

            if($res){
                $level2[] = $res;
            }

            $sql = "select SUM(money) from ".tablename($this->table['reward'])." where from_user = :user";
            $total1 += pdo_fetchcolumn($sql,array(':user'=>$value['member_id']));

        }
        
    }

    if($level2 && is_array($level2)){
        foreach ($level2 as $value) {
           $res = $query->from($this->table['member'])->select(array('member_id'))->where(array('member_fid'=>$value['member_id'],'public_id'=>$public_id))->get();
           if($res){
                $level3[] = $res;
            }
            $sql = "select SUM(money) from ".tablename($this->table['reward'])." where from_user = :user";
            $total2 += pdo_fetchcolumn($sql,array(':user'=>$value['member_id']));

        }
    }


    if($level3 && is_array($level3)){
        foreach ($level3 as $value) {
            $sql = "select SUM(money) from ".tablename($this->table['reward'])." where from_user = :user";
            $total3 += pdo_fetchcolumn($sql,array(':user'=>$value['member_id']));
        }
        
    }

    include $this->template('myteam');