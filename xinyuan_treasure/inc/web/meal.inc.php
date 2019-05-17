<?php
	global $_W,$_GPC;

	/* */

	pageauth($_W['current_module']['name'],$_GPC['do']);

	/* */

	$_W['page']['title'] = '充值套餐';
	$public_id = $_W['uniaccount']['uniacid'];
	$query = load()->object('query');
	
	if($_W['isajax']){
		if(checksubmit()){
			if($_GPC['op'] == 'edit'){
				
				$name	=	trim($_GPC['name']);
				$val	=	trim($_GPC['val']);
				$id 	=	intval($_GPC['id']);
				
				if(!($id && $name && $val)){
					$return['code'] = 1;
					$return['error']= '参数错误';
					
					echo json_encode($return);
					return;
					
				}
				$sql = 'update '.tablename($this->table['meal']).' set '.$name.' = :value where id = :id';
				
				
				$res = pdo_query($sql,array(':value'=>$val,':id'=>$id));
				
				if(!empty($res)){
					
					$return['code'] = 0;
					$return['data'] = array('newval'=>$val);
					echo json_encode($return);
					return;
				}else{
					$return['code'] = 1;
					$return['error']= '操作失败';
					
					echo json_encode($return);
					return;
				}

			}elseif($_GPC['op'] == 'add'){
				
				$rule = array(
					'gold'	=>	'required',
					'pay'	=>	'required'
				);
				$message=array(
					'gold.required'	=>	'套餐不能忘为空',
					'pay.required'	=>	'内容不能为空',
				);
				$data = array(
					'gold'	=>	intval($_GPC['gold']),
					'pay'	=>	floatval($_GPC['pay']),
				);
				$form = new form($data,$rule,$message);
				if($form->check()){
					$error = $form->getError();
					if(!empty($error)){
						$return['code'] = 1;
						$return['error']= $error[0];
						
						echo json_encode($return);
						return;
						
					}else{
						
						$data['add_time'] = date("Y-m-d H:i:s");
						$data['public_id'] = $public_id;
						
						$res = pdo_insert($this->table['meal'],$data);
						
						if(!empty($res)){
							$return['code'] = 0;
							
							echo json_encode($return);
							return;
							
						}else{
							$return['code'] = 1;
							$return['error']= '操作失败';
							
							echo json_encode($return);
							return;	
						}	
					}
					
					
				}
				
			}elseif($_GPC['op'] == 'del'){
				$id = intval($_GPC['id']);
				$id = $query->from($this->table['meal'])->where(array('id'=>$id,'public_id'=>$public_id))->getcolumn('id');
				if($id){
					
					$result = pdo_delete($this->table['meal'], array('id' => $id));
					
					if(!empty($result)){
					$return['code'] = 0;
						
						echo json_encode($return);
						return;
						
					}else{
						$return['code'] = 1;
						$return['error']= '操作失败';
						
						echo json_encode($return);
						return;	
					}	
					
				}else{
					
					$return['code'] = 1;
					$return['error']= '不存在的内容';
					
					echo json_encode($return);
					return;	
				}
				
			}
			
		}else{
			$return['code'] = 1;
			$return['error']= 'request fail';
			echo json_encode($return);
			return;
			
		}
	

	}else{
		$meal_list = $query->from($this->table['meal'])->select(array('id','gold','pay'))->where(array('public_id'=>$public_id))->orderBy('gold')->getall();

		include $this->template('meal');
	}