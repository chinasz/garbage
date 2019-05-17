<?php
	/*
	*商品管理
	*
	*
	*/

	global $_W,$_GPC;

	/* */

	pageauth($_W['current_module']['name'],$_GPC['do']);

	/* */

	$query = load()->object('query');
	$op_arr = array('display','new','edit','del');
	$op = in_array($_GPC['op'],$op_arr)?$_GPC['op']:'display';
	//公众号id
	$public_id = $_W['uniaccount']['uniacid'];
	//验证规则
	$rule = array(
		'goods_name'	=>	'required|maxlen:50',
		'goods_class'	=>	'required',
		'goods_thumb'	=>	'required',
		'goods_title'	=>	'required',
		'goods_description'	=>	'required',
		'goods_price'	=>	'required',
		'goods_score'	=>	'required',
		'goods_oprice'	=>	'required',
		'is_real'		=>	'required',
		'goods_stock'	=>	'required',
		'goods_status'	=>	'required',
	);
	$message=array(
		'goods_name.required'	=>	'商品名不能为空',
		'goods_name.maxlen:50'	=>	'商品名不能超过50个字',
		'goods_class.required'	=>	'商品分类不能为空',
		'goods_thumb.required'	=>	'商品缩略图不能为空',
		'goods_title.required'	=>	'商品简介不能为空',
		'goods_description.required'=>'商品详情不能为空',
		'goods_price.required'	=>	'商品价格不能为空',
		'goods_score.required'	=>	'商品积分不能为空',
		'goods_oprice.required'	=>	'商品原价不能为空',
		'is_real.required'		=>	'商品类型不能为空',
		'goods_stock.required'	=>	'商品库存不能为空',
		'goods_status.required'		=>	'请选择是否显示'
	);
	$data	=array(
		'goods_name'=>trim($_GPC['goods_name']),
		'goods_class'	=>	intval($_GPC['goods_class']),
		'goods_thumb'=>	trim($_GPC['goods_thumb']),
		'goods_title'=>	trim($_GPC['goods_title']),
		'goods_description'	=>	trim($_GPC['goods_description']),
		'goods_price'=>	floatval($_GPC['goods_price']),
		'goods_score'=>	intval($_GPC['goods_score']),
		'goods_oprice'=>floatval($_GPC['goods_oprice']),
		'is_real'=>intval($_GPC['is_real']),
		'goods_stock'=>intval($_GPC['goods_stock']),
		'goods_status'=>intval($_GPC['goods_status']),
		'goods_sort'=>intval($_GPC['goods_sort'])
	);
	
	
	switch($op){
		case 'display':
			$_W['page']['title'] = '商品管理';
			
			$keyword = trim($_GPC['keyword']);
			$class_id = intval($_GPC['cid']);
			$goods_status = intval($_GPC['status']);
			
			
			$field = array('g.goods_id','g.goods_name','g.goods_thumb','g.is_real','c.class_name','g.goods_price','g.goods_score','g.goods_oprice','g.goods_stock','g.goods_status','g.goods_sort');
			$where = array('g.public_id'=>$public_id);
			
			if(checksubmit()){
				if(!empty($keyword)){$where['g.goods_name like'] = '%'.$keyword.'%';}
				if($class_id >0){$where['g.goods_class'] = $class_id;}
				if(in_array($goods_status,array(0,1))){ $where['g.goods_status'] = $goods_status;}
			}
			
			$goods_list = $query->from($this->table['goods'],'g')->innerjoin($this->table['goods_class'],'c')->on('g.goods_class','c.class_id')->select($field)->where($where)->getall();
			
			include $this->template('manage_good');
			break;
		case 'new':
			$_W['page']['title'] = '商品管理-添加商品';
			
			$class_list = $query->from($this->table['goods_class'])->where(array('public_id'=>$public_id))->select('class_id','class_name')->getall();
			if(empty($class_list))message('请先添加分类',$this->createWebUrl('gclass'),'info');
			
			if(checksubmit()){
			
				$data['add_time'] = date('Y-m-d H:i:s');
				$data['public_id']= $public_id;
				$form = new form($data,$rule,$message);
				
				if($form->check()){
					$error = $form->getError();
					
					if(!empty($error)){
						
						message($error[0],'','info');
					}else{
						
						$res = pdo_insert($this->table['goods'], $data);
						
						if(!empty($res)){
							message('添加成功',$this->createWebUrl('goods',array('op'=>'display')),'success');
							
						}else{
							
							message('添加失败','','error');
						}
					}	
					
				}
				
			}
			
			
			include $this->template('goods');
			break;
			
		case 'edit':
			$_W['page']['title'] = '商品管理-编辑商品';
			$goods_id = intval($_GPC['goods_id']);
			
			$class_list = $query->from($this->table['goods_class'])->select('class_id','class_name')->getall();
			$field =array('goods_name','goods_class','goods_thumb','goods_title','goods_description','goods_price','goods_score','goods_oprice','is_real','goods_stock','goods_status','goods_sort');
			
			$goods = $query->from($this->table['goods'])->select($field)->where(array('goods_id'=>$goods_id,'public_id'=>$public_id))->get();
			
			if(empty($goods))message('不存在的商品','','error');
			$form = new form($data,$rule,$message);
			
			if(checksubmit()){
				
				if($form->check()){
					$error = $form->getError();
					if(!empty($error)){
						
						message($error[0],'','info');
					}else{
						
						$res = pdo_update($this->table['goods'], $data,array('goods_id'=>$goods_id));
						
						if(!empty($res)){
							message('编辑成功','','success');
							
						}else{
							
							
							message('编辑失败','','error');
						}
					}	
					
				}
			}
			include $this->template('goods');
			
			
			break;
		case 'del':
			$goods_id = intval($_GPC['goods_id']);
			$id = $query->from($this->table['goods'])->where(array('goods_id'=>$goods_id,'public_id'=>$public_id))->getcolumn('goods_id');
			if($id){
				$res = pdo_delete($this->table['goods'],array('goods_id'=>$goods_id,'public_id'=>$public_id));
				
				if(!empty($res)){
					message('删除成功',$this->createWebUrl('goods',array('op'=>'display'),'success'));
				}else{
					
					message('删除失败','','error');
				}
			}else{
				message('不存在的商品','','error');
			}
			
			break;
		default:
			
			
		
		
		
	}
	
	
	