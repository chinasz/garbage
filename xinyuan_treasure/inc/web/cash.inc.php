<?php
    global $_W,$_GPC;
    $_W['page']['title'] = '提现';
    $query = load()->object('query');
    $public_id = $_W['uniaccount']['uniacid'];
    
    /* */

    pageauth($_W['current_module']['name'],$_GPC['do']);

    /* */
    
    //打款
    if($_W['isajax']){
        if(checksubmit()){
           
            if(empty($_GPC['id']) || !in_array($_GPC['ment'],array(0,1))){
                echo json_encode(array('code'=>1,'error'=>'参数错误'));
                return;
            }
            
            $cash_info = $query->from($this->table['cash'])->where(array('id'=>$_GPC['id'],'cash_status'=>2))->get();
            if(empty($cash_info)){
                echo json_encode(array('code'=>1,'error'=>'提现订单无效'));
                return;

            }
            $member = $query->from($this->table['member'])->where(array('member_id'=>$cash_info['cash_user'],'public_id'=>$public_id,'is_del'=>1))->get();
            if(empty($member)){

                echo json_encode(array('code'=>1,'error'=>'无效的用户'));
                return;
            }


            if($_GPC['ment'] == 1){
                $jine = $cash_info['cash_money'] - $cash_info['cash_money'] * $cash_info['cash_fee'] / 100;

                $cert = array(
                    'cert'	=>	MODULE_ROOT.'/cert/'.md5('cert'.$_W['uniaccount']['uniacid']).'.pem',
                    'key'	=>	MODULE_ROOT.'/cert/'.md5('key'.$_W['uniaccount']['uniacid']).'.pem',
                );
            
                if(empty($this->settings['mchid']) || empty($this->settings['appid'])|| empty($this->settings['secrect']) || !file_exists($cert['cert']) || !file_exists($cert['key'])){
                            
                    echo json_encode(array('code'=>1,'error'=>'支付参数未设置'));
                    return;
                    
                }
    
                //微信付款参数
                $wechat = array(
                    'mch_appid'	=>	$this->settings['appid'],
                    'mchid'	=>	$this->settings['mchid'],
                    'nonce_str'=>	md5(uniqid(mt_rand(), true)),
                    'partner_trade_no'=>$cash_info['cash_num'],
                    'openid'	=>	$member['member_openid'],
                    'check_name'=>	'NO_CHECK',
                    'amount'	=>	$jine*100,
                    'desc'		=>	'企业付款',
                    'spbill_create_ip'=>	CLIENT_IP
                );
                $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
                
                
                //微信付款
                
                $pay = new pay($this->settings['secrect'],$cert);
                
                $wechat['sign'] = $pay->sign($wechat);
                
                $wechat = array2xml($wechat);
                
                $res = xml2array($pay->curl_post_ssl($url,$wechat));
                if($res['return_code'] == 'SUCCESS' && $res['result_code']=='SUCCESS'){
                    $data = array(
                        'cash_three_num'    =>  $res['payment_no'],
                        'cash_status'       =>  1,
                        'cash_msg'          =>  '打款成功，打款金额为'.$jine.'元,手续费'.$cash_info['cash_fee'].'%',
                        'cash_otime'        =>  date("Y-m-d H:i:s")
                    );
                    $return['code'] =   0;
                }else{
                    $data = array(
                        'cash_msg'          =>  '打款失败，'.$res['err_code_des'].',请重试',
                    );
                    $return['code'] =   1;
                    $return['error'] =   $res['err_code_des'];
                }
                
            }elseif ($_GPC['ment'] == 0) {
                $data = array(
                    'cash_status'   =>  1,
                    'cash_msg'      =>  '手动打款确认',
                    'cash_otime'        =>  date("Y-m-d H:i:s")
                );
                $return['code'] =   0;
            }
            $ures = pdo_update($this->table['cash'],$data,array('id'=>$cash_info['id']));

            if($ures){
                echo json_encode($return);
                return;

            }else{
                $return['code'] =   1;
                $return['error'] =   '数据写入失败';
                echo json_encode($return);
                return;
            }

        }
    /*end */
    }else{
    
    $cash_list = $query->from($this->table['cash'],'c')->innerjoin($this->table['member'],'m')->on(array('c.cash_user'=>'m.member_id'))->where(array('m.public_id'=>$public_id))->orderby('c.cash_time','desc')->getall();

    include $this->template('cash');
    }
