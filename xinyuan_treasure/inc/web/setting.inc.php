<?php
	/**
	*基础设置
	*
	*/
	global $_W,$_GPC;

	/* */

	pageauth($_W['current_module']['name'],$_GPC['do']);

	/* */
	$_W['page']['title'] = '基础设置';
	//公众号id
	$public_id = $_W['uniaccount']['uniacid'];
	
	$op_arr = array('edit','del','new','display');

	$op = in_array($_GPC['op'],$op_arr)?$_GPC['op']:'display';
	$query = load()->object('query');
	load()->func('tpl');
	
	//验证
	
	
	$rule = array(
		'course_title'	=>	'required|maxlen:50',
		'course_text'	=>	'required',
		'is_show'		=>	'required'
	);
	$message=array(
		'course_title.required'	=>	'标题不能为空',
		'course_title.maxlen:50'	=>	'标题不能超过50个字',
		'course_text.required'	=>	'内容不能为空',
		'is_show.required'		=>	'请选择是否显示'
	);
	
	
	
	switch($op){
		case 'display':
			
			$course_list=$query->from($this->table['course'])->select(array('course_id','course_title', 'course_sort','is_show'))->where(array('public_id'=>$public_id))->orderby('course_sort', 'DESC')->getall();

			include $this->template('base_set');
			break;
		case 'edit':
			$_W['page']['title'] ='编辑-新手教程';	
			$id = intval($_GPC['course_id']);
			
			$course_column = $query->from($this->table['course'])->where('course_id',$id)->select(array('course_title','course_text','course_sort','is_show'))->where(array('public_id'=>$public_id))->get();
			
			if(!$course_column)message('不存在的内容',$this->createWebUrl('setting'),'error');
			
			if(checksubmit()){
				$data=array(
					'course_title'	=>	trim($_GPC['course_title']),
					'course_text'	=>	trim($_GPC['course_text']),
					'course_sort'	=>	intval($_GPC['course_sort']),
					'is_show'		=>	intval($_GPC['course_status'])
				);
				$form = new form($data,$rule,$message);
				
				if($form->check()){
					
					$error = $form->getError();
					
					if($error){
						
						message($error[0],$redirect = '', $type = 'warning');
						
					}else{
						
						if($data == $course_column){
							message('内容未改变','','info');
						}else{
							pdo_update($this->table['course'],$data,array('course_id'=>$id));
							message('修改成功',$this->createWebUrl('setting',array('op'=>'edit','course_id'=>$id)));
							
						}
					}
				}
				
			}
			
			
			include $this->template('course');
			break;
		case 'new':
			$_W['page']['title'] ='新增-新手教程';
			
			if(checksubmit()){
				$data=array(
					'course_title'	=>	trim($_GPC['course_title']),
					'course_text'	=>	trim($_GPC['course_text']),
					'course_sort'	=>	intval($_GPC['course_sort']),
					'is_show'		=>	intval($_GPC['course_status']),
					'public_id'		=>	$public_id
				);
				
				$form = new form($data,$rule,$message);
				
				if($form->check()){
					
					$error = $form->getError();
					
					if($error){
						
						message($error[0],$redirect = '', $type = 'warning');
						
					}else{
						$res = pdo_insert($this->table['course'], $data);
						
						if($res){
							message('新增成功',$redirect = $this->createWebUrl('setting',array('op'=>'display')), $type = 'success');
						}else{
							message('插入失败',$redirect = $this->createWebUrl('setting',array('op'=>'new')), $type = 'error');
						}
					}
				}
				
			}
			
			include $this->template('course');
			break;
		case 'del':
			$_W['page']['title'] ='删除-新手教程';
			$id = intval($_GPC['course_id']);
			
			$course_column = $query->from($this->table['course'])->where(array('course_id'=>$id,'public_id'=>$public_id))->select(array('course_title','course_text','course_sort','is_show'))->get();
			
			if(!$course_column)message('不存在的内容',$this->createWebUrl('setting'),'error');
			
			$res = pdo_delete($this->table['course'],array('course_id'=>$id));
			if (!empty($res)) {
				message('删除成功',$this->createWebUrl('setting'),'success');
			}
			
			break;
		default:
			break;
	}
	