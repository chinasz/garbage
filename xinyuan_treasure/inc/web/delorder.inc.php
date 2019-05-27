<?php
    global $_W,$_GPC;
    $ordid = $_GPC['ordid'];
    $query = load()->object('query');

    if(!$_W['isajax']){
        return;
    }
    if(!checksubmit()){
        return;
    }
    $ordid = $query->from($this->table['order'])->where(array('order_id'=>intval($ordid)))->getcolumn('order_id');

    if(empty($ordid)){
        $return['code'] = 1;
        $return['error'] = '订单不存在';
        echo json_encode($return);
        return;

    }else{
        $res = pdo_delete($this->table['order'],array('order_id'=>intval($ordid)));

        if($res){
            $return['code'] = 0;
            echo json_encode($return);
            return;
        }else{

            $return['code'] = 1;
            $return['error'] = '删除失败';
            echo json_encode($return);
            return;
        }
    }