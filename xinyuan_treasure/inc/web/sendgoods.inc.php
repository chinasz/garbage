<?php 
    global $_W,$_GPC;
    $query = load()->object('query');
    if($_W['isajax']){
        if(checksubmit()){
            $order_id = empty($_GPC['ordid'])?0:intval($_GPC['ordid']);
            $order_express = empty($_GPC['express'])?'':trim($_GPC['express']);

            $is_ord = $query->from($this->table['order'])->where(array('order_id'=>$order_id,'order_status'=>3,'order_express'=>''))->get();
            if(empty($is_ord)){
                $return = array('code'=>1,'error'=>'订单错误');
                echo json_encode($return);
                return;
            }

            $data = array(
                'order_status'  =>  2,
                'order_express' =>  $order_express

            );

            pdo_query('START TRANSACTION');

            $res = pdo_update($this->table['order'],$data,array('order_id'=>$order_id));
            //库存-1
            $sql = "update ".tablename($this->table['goods'])." set goods_stock = goods_stock - 1 where goods_id = :goods_id";
            $res2 = pdo_query($sql,array(':goods_id'=>$is_ord['order_goods']));


            if(!empty($res) && !empty($res2)){
                pdo_query("COMMIT"); 
                $return = array('code'=>0);
                echo json_encode($return);
                return;
            }else{
                pdo_query("ROLLBACK");
                $return = array('code'=>1,'error'=>'数据写入失败');
                echo json_encode($return);
                return;

            }

        }            

    }