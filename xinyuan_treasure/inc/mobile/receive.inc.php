<?php 
/** 收货
 */
    global $_W,$_GPC;
    $id = $_SESSION['ids'];
    $query = load()->object('query');

    if(!$_W['isajax']){

        return;
    }

    if(checksubmit()){
        $order_id = $_GPC['ordid'];
        if($order_id){
            $order_id = $query->from($this->table['order'])->where(array('order_id'=>$order_id,'order_user'=>$id))->get();
            if($order_id['order_id']>0){
                $data = array(
                    'order_status'    => 1
                );

                $res = pdo_update($this->table['order'],$data,array('order_id'=>$order_id));

                if(empty($res)){
                    $return['code'] = 0;
                    echo json_encode($return);
                    return;

                }else{
                    $return['code'] = 1;
                    $return['error']= '服务器繁忙,请重试';
                    echo json_encode($return);
                    return;

                }

            }else{
                $return['code'] = 1;
                $return['error']= '无效的订单';
                echo json_encode($return);
                return;
            }

        }else{

            $return['code'] = 1;
            $return['error']= 'Invalid param';
            echo json_encode($return);
            return;
        }


    }