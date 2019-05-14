<?php
/**
 * xin_treasure模块微站定义
 *
 * @author 心缘心工作室
 * @url
 */
defined('IN_IA') or exit('Access Denied');

include_once 'func.php';
include_once 'pay.php';
include_once 'codepay.php';
session_start();
class Xinyuan_treasureModuleSite extends WeModuleSite {

	public $settings;
	public $prefix = 'ims_';
	public $table = array('course'=>'xin_course','member'=>'xin_member','goods'=>'xin_goods','goods_class'=>'xin_goods_class','order'=>'xin_order','cash'=>'xin_cash','recharge'=>'xin_recharge','meal'=>'xin_recharge_meal','game'=>'xin_game');
	
	public function __construct(){
		global $_W,$_GPC;
		
		strip_gpc($_GPC);
		
		$sql = 'SELECT `settings` FROM ' . tablename('uni_account_modules') . ' WHERE `uniacid` = :uniacid AND `module` = :module';
		$settings = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid'], ':module' => 'xinyuan_treasure'));
		$this->settings = iunserializer($settings);
		
		if($_W['container'] == 'wechat' and $_W['os'] == 'mobile'){
			
			$fid = $_GPC['fid']?$_GPC['fid']:0;
			login($this->table,$fid,$_W['uniaccount']['uniacid']);
		}

	}
	
	
	// public function getRuleMenus(){
	// 	global $_W,$_GPC;
	// 	return array(
	// 		array(
	// 			'title'	=> '开始游戏',
	// 			'url'	=>	$this->createMobileUrl('index'),
	// 		),
	// 		array(
	// 			'title'	=> '充值',
	// 			'url'	=>	$this->createMobileUrl('krypton')
	// 		),
	// 		array(
	// 			'title'	=> '交易明细',
	// 			'url'	=>	$this->createMobileUrl('detail')
	// 		),
	// 		array(
	// 			'title'	=> '积分商城',
	// 			'url'	=>	$this->createMobileUrl('exchange')
	// 		),
	// 		array(
	// 			'title'	=> '新手教程',
	// 			'url'	=>	$this->createMobileUrl('course')
	// 		),
	// 		array(
	// 			'title'	=> '分享海报',
	// 			'url'	=>	$this->createMobileUrl('poster')
	// 		),
	// 		array(
	// 			'title'	=> '排行榜',
	// 			'url'	=>	$this->createMobileUrl('rank')
	// 		),
	// 	);
	// }
	
	public function getMenus(){
		return array(
			array(
				'title'	=> '新手教程',
				'url'	=>	$this->createWebUrl('setting'),
				'icon'	=>	'wi wi-apply'
			),
			array(
				'title'	=> '会员管理',
				'url'	=>	$this->createWebUrl('member'),
				'icon'	=>	'wi wi-user-group'
			),
			array(
				'title'	=> '商品分类',
				'url'	=>	$this->createWebUrl('gclass'),
				'icon'	=>	'fa fa-pencil-square-o'
			),
			array(
				'title'	=> '商品管理',
				'url'	=>	$this->createWebUrl('goods'),
				'icon'	=>	'fa fa-gift'
			),
			array(
				'title'	=> '订单管理',
				'url'	=>	$this->createWebUrl('order'),
				'icon'	=>	'fa fa-truck',
			),
			array(
				'title'	=> '游戏记录',
				'url'	=>	$this->createWebUrl('game'),
				'icon'	=>	'fa fa-keyboard-o',
			),
			array(
				'title'	=> '充值记录',
				'url'	=>	$this->createWebUrl('krypton'),
				'icon'	=>	'fa fa-book'
			),
			array(
				'title'	=>	'充值套餐',
				'url'	=>	$this->createWebUrl('meal'),
				'icon'	=>	'fa fa-credit-card'
			),
		);	
	}
	
	
   public function doMobileTest(){
		global $_W,$_GPC;
		$query = load()->object('query');
		$ordid = intval($_GPC['id']);
		
		$codepay = new codepay($this->settings['codepay']['id'],$this->settings['codepay']['key']);
		
		$order = $query->from($this->table['recharge'])->select(array('recharge_num','recharge_money'))->where(array('id'=>$ordid,'status'=>0,'recharge_user'=>$_SESSION['ids']))->get();
		if(empty($order)){
			
			message('订单无效');
		}
		$meal = $query->from($this->table['meal'])->select(array('gold','pay'))->where(array('id'=>$order['recharge_money']))->get();
		
		$notify_url = $_W['siteroot']."app/".$this->createMobileUrl('test2');
		$return_url = $_W['siteroot']."app/".$this->createMobileUrl('test3');
		
		$url = $codepay->pay($order['recharge_num'],$meal['pay'],$notify_url,$return_url,$ordid.'&'.$_SESSION['ids']);
		header("Location:$url");
		
		
	}
	public function doMobileTest2(){
		global $_W,$_GPC; 
		load()->func('logging');
		$query = load()->object('query');
		$codepay = new codepay($this->settings['codepay']['id'],$this->settings['codepay']['key']);
		$pay = $codepay->notify($_POST);
		
		if($pay){
			$ordid  = intval(explode('&',$pay['param'])[0]);
			$member  = intval(explode('&',$pay['param'])[1]);
			$id = $query->from($this->table['recharge'])->where(array('id'=>$ordid,'status'=>0,'recharge_user'=>$member))->getcolumn('recharge_money');

			if(empty($id)){
				logging_run('查询不到订单', $type = 'trace', $filename = 'codepay');
				exit('fail');
			}
			$data = array(
				'status'	=>	1,
			);
			$meal = $query->from($this->table['meal'])->select(array('gold','pay'))->where(array('id'=>$id))->get();
			//事务
			pdo_query("START TRANSACTION");
			//修改订单状态
			$res1 = pdo_update($this->table['recharge'],$data,array('id'=>$ordid));
			$sql = "update ".tablename($this->table['member'])."set member_score = member_score+".floatval($meal['gold'])." where member_id = :member_id";
			//加余额
			$res2 = pdo_query($sql,array(':member_id'=>$member));
			//三级分销
			$arr = fenxiao($this->table,$member);

			$res3=true;
			
			if(is_array($arr) && !empty($arr)){
				$arr_length = count($arr);
				for($i=0;$i<=$arr_length;$i++){
					//分销比例为空不返
					if(empty($this->settings['sale'.strval($i+1)])){
						continue;
					}
					//返现金额
					$reward = intval($this->settings['sale'.strval($i+1)])*floatval($meal['pay'])/100;
					$sql2 = "update ".tablename($this->table['member'])."set reward = reward+".floatval($reward)." where member_id = :member_id";
					
					if($arr[$i]){
						$res3 = pdo_query($sql2,array(':member_id'=>$arr[$i]));
					}
					
				}
				
			}
			
			
			if($res1 && $res2 && $res3){
				pdo_query("COMMIT"); 
				
				exit('ok');
				
			}else{
				pdo_query("ROLLBACK"); 
				logging_run('数据库写入失败,订单id'.$ordid, $type = 'trace', $filename = 'codepay');
				exit('fail');
			}
			
		}		
	}
	public function doMobileTest3(){
		
		
		message("支付成功",$this->createMobileUrl('krypton'),'success');
	}
}