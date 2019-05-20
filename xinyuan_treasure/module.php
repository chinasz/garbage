<?php
/**
 * xin_treasure模块定义
 *
 * @author 心缘心工作室
 * @url
 */
defined('IN_IA') or exit('Access Denied');
include_once 'func.php';
class Xinyuan_treasureModule extends WeModule {

	public function settingsDisplay($settings){
		global $_W, $_GPC;
		/* */

		pageauth($_W['current_module']['name'],$_GPC['do']);

		/* */
		if(checksubmit()){

			$data = $_GPC['data'];
			if(!empty($data['web_menu_tem'])){
				foreach ($data['web_menu_tem'] as $key => $value) {
					for($i=0;$i<count($value);$i++){
						$arr[$i][$key] = $value[$i];
					}
				
				}
				$data['web_menu'] = $arr;
			}
			unset($data['web_menu_tem']);


			if(!empty($data['cert1'])){
				$cert1 = fopen(MODULE_ROOT.'/cert/'.md5('cert'.$_W['uniaccount']['uniacid']).'.pem','w');
				fwrite($cert1, $data['cert1']);
				fclose($cert1);
			}
			
			if(!empty($data['cert2'])){
				$cert2 = fopen(MODULE_ROOT.'/cert/'.md5('key'.$_W['uniaccount']['uniacid']).'.pem','w');
				fwrite($cert2, $data['cert2']);
				fclose($cert2);
			}
			unset($data['cert1']);
			unset($data['cert2']);
			
			if(!empty($data['poster'])){
				
				$data['poster'] = json_decode(htmlspecialchars_decode($data['poster']),true);
			}
			if (!$this->saveSettings($data)) {
				message('保存信息失败','','error');
			} else {
				message('保存信息成功','','success');
			}
		}

		load()->func('tpl');
		//var_dump($settings);
		include $this->template('setting');

	}

}