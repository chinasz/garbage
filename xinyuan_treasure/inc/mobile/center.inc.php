<?php 
    global $_W,$_GPC;
    $_W['page']['title'] = '我的';
    $query = load()->object('query');
    $member_score = $query->from($this->table['member'])->where(array('member_id'=>$_SESSION['ids']))->getcolumn('member_score');
    
    include $this->template('center');