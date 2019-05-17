<?php
	global $_W,$_GPC;
	//商品分类
	
	/* */

	pageauth($_W['current_module']['name'],$_GPC['do']);

	/* */

	$op_arr	=	array('display','new','edit','del');
	$op	=	in_array($_GPC['op'],$op_arr)?$_GPC['op']:'display';
	$query	=	load()->object('query');
	load()->func('tpl');
	
	//$public_id
	$public_id = $_W['uniaccount']['uniacid'];
	
	$field = array('class_id','class_name','class_thumb','class_sort','class_status');
	
	$class_list = $query->from($this->table['goods_class'])->select($field)->where(array('public_id'=>$public_id))->getall();
	
	//验证规则
	$rule = array(
		'class_name'	=>	'required|maxlen:50',
		'class_fid'		=>	'required',
		'class_thumb'	=>	'required',
		'class_status'	=>	'required'
	);
	$message=array(
		'class_name.required'	=>	'分类名不能为空',
		'class_name.maxlen:50'	=>	'分类名不能超过50个字',
		'course_text.required'	=>	'内容不能为空',
		'is_show.required'		=>	'请选择是否显示'
	);
	$data	=array(
		'class_name'=>trim($_GPC['gclass_name']),
		'class_fid'	=>	intval($_GPC['gclass_parent']),
		'class_thumb'=>	trim($_GPC['gclass_thumb']),
		'class_sort'=>	intval($_GPC['gclass_sort']),
		'class_status'=>intval($_GPC['gclass_status'])
	);
	
	//
	
	$classbyid = array();
	
	switch($op){
		
		case 'display':
			$_W['page']['title'] = '分类管理';
			
			include $this->template('manage_goods_type');
			
			break;
		case 'new':
			$_W['page']['title'] = '分类管理-添加分类';
			$data['add_time']=date('Y-m-d H:i:s');
			$data['public_id']	= $public_id;
			$form = new form($data,$rule,$message);
			
			//表单提交
			if(checksubmit()){
				if($form->check()){
					$error = $form->getError();
					if(!empty($error)){
						
						message($error[0],'','info');
					}else{
						
						$res = pdo_insert($this->table['goods_class'],$data);
						
						if(!empty($res)){
							
							message('添加成功',$this->createWebUrl('gclass',array('op'=>'display')),'success');
						}else{
							message('添加失败','','error');
						}
						
					}
					
				}
			}
			include $this->template('goods_class');
			break;
		case 'edit':
			$_W['page']['title'] = '分类管理-编辑分类';
			$id = intval($_GPC['id']);
			$field = array('class_name','class_thumb','class_sort','class_status','class_fid');
			
			$classbyid = $query->from($this->table['goods_class'])->where(array('class_id'=>$id,'public_id'=>$public_id))->select($field)->get();
			
			if(empty($classbyid))message('不存在的分类',$this->createWebUrl('gclass',array('op'=>'display')),'error');
			
			if(checksubmit()){
				
				if($classbyid == $data){
					message('内容未改变','','info');
				}
				$form = new form($data,$rule,$message);
				if($form->check()){
					$error = $form->getError();
					if(!empty($error)){
						message($error[0],'','info');
					}
					$res = pdo_update($this->table['goods_class'], $data, array('class_id' =>$id));
					if(!empty($res)){
						message('修改成功','','success');
					}else{
						message('修改失败','','error');
					}
				}
			}
			
			include $this->template('goods_class');
			break;
		case 'del':
			$id = intval($_GPC['id']);
			if(token()==$_GPC['token']){
				$field = array('class_name','class_thumb','class_sort','class_status','class_fid');
			
				$classbyid = $query->from($this->table['goods_class'])->where(array('class_id'=>$id,'public_id'=>$public_id))->select($field)->get();
				
				if(empty($classbyid))message('不存在的分类',$this->createWebUrl('gclass',array('op'=>'display')),'error');
				
				$res = pdo_delete($this->table['goods_class'], array('class_id' => $id));
				if (!empty($res)) {
					message('删除成功',$this->createWebUrl('gclass',array('op'=>'display')),'success');
				}
				
			}else{
				message('删除失败','','error');
				
			}
			
			break;
		default:
			
	}
