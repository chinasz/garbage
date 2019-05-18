<?php
    global $_W,$_GPC;

    $_W['page']['title']    =   '资金明细';
    $query = load()->object('query');
    //提现记录
    $cash_log = $query->from($this->table['cash'])->where(array('cash_user'=>$_SESSION['ids']))->order('cash_time','desc')->getall();


    //分销记录
    $reward_log = $query->from($this->table['reward'])->where(array('to_user'=>$_SESSION['ids']))->order('add_time','desc')->getall();


    include $this->template('cash_log');