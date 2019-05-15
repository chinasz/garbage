<?php
	$field =array( 
		array(
			'table'	=>	'ims_xin_course',
			'field'	=>	array(
				'course_id' =>array(
					'name'	=>	'course_id',
					'type' 	=>	'int',
					'increment'	=> 'AUTO_INCREMENT',
				),
				'course_title'	=>	array(
					'name'	=>	'course_title',
					'type'	=>	'varchar',
					'length'=>	500,
					'default'=>''
				),
				'course_text'	=>	array(
					'name'	=>	'course_text',
					'type'	=>	'varchar',
					'length'=>	500,
					'default'=>	'',
				),
				'course_sort'	=>	array(
					'name'	=>	'course_sort',
					'type'	=>	'int',
					'default'=>	0,
				),
				'is_show'		=>	array(
					'name'	=>	'is_show',
					'type'	=>	'int',
					'default'=>	1	
				),
				'public_id'	=>	array(
					'name'	=>	'public_id',
					'type'	=>	'int',
				)
			),
			'indexs'	=>	array(
				array('fields' => array('course_id'), 'type' => 'primary'),
				array('fields' => array('course_sort'), 'type' => 'index','name'=>'sort')
			)
		),
		array(
			'table'		=>	'ims_xin_member',
			'field'		=>	array(
				'member_id'	=>	array(
					'name'	=>	'member_id',
					'type' 	=>	'int',
					'increment'	=> 'AUTO_INCREMENT',
				),
				'member_nickname'	=>	array(
					'name'	=>	'member_nickname',
					'type'	=>	'varchar',
					'length'=>	500,
					'default'=>	''
				),
				'member_openid'	=>	array(
					'name'	=>	'member_openid',
					'type'	=>	'varchar',
					'length'=>	50,
					
				),
				'member_score'	=>	array(
					'name'	=>	'member_score',
					'type'	=>	'float',
				),
				'member_avatar'	=>	array(
					'name'	=>	'member_avatar',
					'type'	=>	'varchar',
					'length'=>	500,
					'default'=>	'',	
				),
				'member_fid'	=>	array(
					'name'	=>	'member_fid',
					'type'	=>	'int',
				),
				'member_firsttime'=> array(
					'name'	=>	'member_firsttime',
					'type'	=>	'datetime',
				),
				'member_firstip'=>array(
					'name'	=>	'member_firstip',
					'type'	=>	'bigint'
				),
				'member_lasttime'=>array(
					'name'	=>	'member_lasttime',
					'type'	=>	'datetime',
				),
				'reward'	=>	array(//分销赏金
					'name'	=>	'reward',
					'type'	=>	'float',
					'length'=>	'10,2',
					'default'=>	0
				
				),
				'is_del'	=>	array(
					'name'	=>	'is_del',
					'type'	=>	'int',
					'length'=>	1,
					'default'=>	1	
				),
				'public_id'	=>	array(
					'name'	=>	'public_id',
					'type'	=>	'int',
				)
			),
			'indexs'	=>	array(
				array('fields'=>array('member_id'),'type'=>'primary'),
				array('fields'=>array('member_openid'),'type'=>'unique','name'=>'unique_openid'),
				array('fields'=>array('member_fid'),'type'=>'index','name'=>'fid_index'),
			)
		),
		array(
			'table'		=>	'ims_xin_goods',
			'field'		=>	array(
				'goods_id'	=>	array(
					'name'	=>	'goods_id',
					'type' 	=>	'int',
					'increment'	=> 'AUTO_INCREMENT',	
				),
				'good_name'	=>	array(
					'name'	=>	'goods_name',
					'type'	=>	'varchar',
					'length'=>	50,
					
				),
				'goods_class'=>	array(
					'name'	=>	'goods_class',
					'type'	=>	'int',
					
				),
				'goods_thumb'=>array(
					'name'	=>	'goods_thumb',//缩略图
					'type'	=>	'varchar',
					'length'=>	500,
				),
				'goods_img'	=>	array(
					'name'	=>	'goods_img',//图片
					'type'	=>	'varchar',
					'length'=>	500,
				),
				'goods_title'=>	array(
					'name'	=>	'goods_title',//简介
					'type'	=>	'varchar',
					'length'=>	500,
				),
				'goods_description'=>array(
					'name'	=>	'goods_description',//商品详情
					'type'	=>	'text',
				),
				'goods_price'=>array(
					'name'	=>	'goods_price',
					'type'	=>	'float',
					'length'=>	'10,2',
				),
				'goods_score'=>array(
					'name'	=>	'goods_score',
					'type'	=>	'int',
				),
				'goods_oprice'=>array(
					'name'	=>	'goods_oprice',
					'type'	=>	'float',
					'length'=>	'10,2',	
				),
				'is_real'	=>	array(//0虚拟1实体
					'name'	=>	'is_real',
					'type'	=>	'int',
					'length'=>	1,	
				),
				'goods_stock'=>	array(
					'name'	=>	'goods_stock',
					'type'	=>	'int',
					'default'=>	0,
				),
				'add_time'	=>	array(
					'name'	=>	'add_time',
					'type'	=>	'datetime',	
				),
				'update_time'=>	array(
					'name'	=>	'update_time',
					'type'	=>	'datetime',
				),
				'goods_status'=> array(
					'name'	=>	'goods_status',
					'type'	=>	'int',
					'default'=>	1,
				),
				'goods_sort'=>array(
					'name'	=>	'goods_sort',
					'type'	=>	'int',
					'default'=>	0,
				
				),
				'public_id'	=>	array(
					'name'	=>	'public_id',
					'type'	=>	'int',
				)
			),
			'indexs'	=>	array(
				array('fields'=>array('goods_id'),'type'=>'primary'),
				array('fields'=>array('goods_class'),'type'=>'index','name'=>'goods_class'),
				array('fields'=>array('goods_sort'),'type'=>'index','name'=>'goods_sort'),
			)
		
		
		),
		array(
			'table'	=>	'ims_xin_goods_class',
			'field'	=>	array(
				'class_id'	=>	array(
					'name'	=>	'class_id',
					'type'	=>	'int',
					'increment'	=> 'AUTO_INCREMENT'
				),
				'class_name'=>	array(
					'name'	=>	'class_name',
					'type'	=>	'varchar',
					'length'=>	500,
				),
				'class_fid'	=>	array(
					'name'	=>	'class_fid',//上级分类
					'type'	=>	'int',
					'default'=>	0,
				),
				'class_thumb'=>	array(
					'name'	=>	'class_thumb',
					'type'	=>	'varchar',
					'length'=>	500,
				),
				'class_sort'=>	array(
					'name'	=>	'class_sort',
					'type'	=>	'int',
					'default'=>	0,
				),
				'class_status'=> array(
					'name'	=>	'class_status',
					'type'	=>	'int',
					'default'=>	1,
				),
				'update_time'=>	array(
					'name'	=>	'update_time',
					'type'	=>	'datetime',
				),	
				'add_time'	=>	array(
					'name'	=>	'add_time',
					'type'	=>	'datetime',
				),
				'public_id'	=>	array(
					'name'	=>	'public_id',
					'type'	=>	'int',
				)
			),
			'indexs'	=>	array(
				array('fields'=>array('class_id'),'type'=>'primary'),
				array('fields'=>array('class_sort'),'type'=>'index','name'=>'class_sort'),
				array('fields'=>array('class_status'),'type'=>'index','name'=>'class_status'),
			)
		),
		array(
			'table' => 'ims_xin_order',
			'field'	=>	array(
				'order_id'	=>	array(
					'name'	=>	'order_id',
					'type'	=>	'int',
					'increment'=> 'AUTO_INCREMENT',
				
				),
				'order_num'	=>	array(	//内部订单号
					'name'	=>	'order_num',
					'type'	=>	'varchar',
					'length'=>	'32',
				),
				'order_type' =>	array(	//订单类型
					'name'	=>	'order_type',
					'type'	=>	'int',	
				),
				'order_goods' =>array(
					'name'	=>	'order_goods',
					'type'	=>	'int',
				),
				'order_fee'	=>	array(	//订单价格
					'name'	=>	'order_fee',
					'type'	=> 	'float',
					'length'=>	'10,2',
				),
				'order_remark'=> array(
					'name'	=>	'order_remark',
					'type'	=>	'varchar',
					'length'=>	500,
				),
				'order_user'	=>	array(
					'name'	=>	'order_user',
					'type'	=>	'int',
					
				),
				'order_actual'	=>	array(	//实付金额
					'name'	=>	'order_actual',
					'type'	=>	'float',
					'length'=>	'10,2'	
				),
				'order_poster'	=>	array(	//运费
					'name'	=>	'order_poster',
					'type'	=>	'float',
					'length'=>	'10,2',
				),
				'order_addr'	=>	array(//地址
					'name'	=>	'order_addr',
					'type'	=>	'varchar',
					'length'=>	'500',
					'default'=>	'',
				),
				'order_receiver'=>array(//收件人
					'name'	=>	'order_receiver',
					'type'	=>	'varchar',
					'length'=>	'50',
					'default'=>	'',
				),
				'order_phone'	=>array(//收件人手机
					'name'	=>	'order_phone',
					'type'	=>	'char',
					'length'=>	'11',
					'default'=>	'',
				),
				'order_express'	=>	array(//快递
					'name'	=>	'order_express',
					'type'	=>	'varchar',
					'length'=>	500,
					'default'=> '',
				),
				'order_status'	=>	array(	//订单状态 1.已完成 2.已发货 3.已付款 4.未付款 5.交易关闭
					'name'	=>	'order_status',
					'type'	=>	'int',
					
				),
				'order_method'	=>	array(	//支付方式 1.积分余额
					'name'	=>	'order_method',
					'type'	=>	'int',
				),
				'add_time'	=>	array(
					'name'	=>	'add_time',
					'type'	=>	'datetime',
				
				),
				'public_id'	=>	array(
					'name'	=>	'public_id',
					'type'	=>	'int',
				)
			),
			'indexs'	=>	array(
				array('fields'=>array('order_id'),'type'=>'primary'),
				array('fields'=>array('add_time'),'type'=>'index','name'=>'order_add_time'),
				array('fields'=>array('order_status'),'type'=>'index','name'=>'order_status'),
				array('fields'=>array('order_user'),'type'=>'index','name'=>'order_user'),
			)
		
		),
		array(
			'table'	=>	'ims_xin_recharge',//充值
			'field'	=>	array(
				'id'	=>	array(
					'name'	=>	'id',
					'type'	=>	'int',
					'increment'=> 'AUTO_INCREMENT',
				),
				'recharge_num'	=>array(
					'name'	=>	'recharge_num',
					'type'	=>	'varchar',
					'length'=>	32
				),
				'recharge_user'=>	array(
					'name'	=>	'recharge_user',
					'type'	=>	'int',
				),
				'recharge_money'=>	array(
					'name'	=>	'recharge_money',
					'type'	=>	'int',
					'length'=>	1,
				),
				'recharge_pay'	=>	array(
					'name'	=>	'recharge_pay',
					'type'	=>	'float',
					'length'=>	'10,2',
				),
				'payment'	=>	array(
					'name'	=>	'payment',
					'type'	=>	'varchar',
					'length'=>	50
				),
				'remark'	=>	array(
					'name'	=>	'remark',
					'type'	=>	'varchar',
					'length'=>	500
				
				),
				'add_time'	=>	array(
					'name'	=>	'add_time',
					'type'	=>	'datetime',
				
				),
				'stauts'	=>	array(
					'name'	=>	'status',
					'type'	=>	'int',
					'default'=>	0,
				
				),
				'public_id'	=>	array(
					'name'	=>	'public_id',
					'type'	=>	'int',
				),
			
			),
			'indexs'	=>	array(
				array('fields'=>array('id'),'type'=>'primary'),
				array('fields'=>array('add_time'),'type'=>'index','name'=>'recharge_add_time'),
				array('fields'=>array('status'),'type'=>'index','name'=>'status')
			)
		
		),
		array(
			'table'	=>	'ims_xin_recharge_meal',//充值套餐
			'field'	=>	array(
				'id'	=>	array(
					'name'	=>	'id',
					'type'	=>	'int',
					'increment'=> 'AUTO_INCREMENT',
				),	
				'gold'	=>	array(
					'name'	=>	'gold',
					'type'	=>	'int',
				),
				'pay'	=>	array(
					'name'	=>	'pay',
					'type'	=>	'float',
					'length'=>	'10,2'	
				),
				'add_time'=>array(
					'name'	=>	'add_time',
					'type'	=>	'datetime',
				),
				'public_id'	=>	array(
					'name'	=>	'public_id',
					'type'	=>	'int',
				)
			
			),
			'indexs'	=>	array(
				array('fields'=>array('id'),'type'=>'primary')
			)
		),
		// array(
			// 'table'	=>	'ims_xin_receive',
			// 'field'	=>	array(
				// 'id'	=>	array(
					// 'name'	=>	'id',
					// 'type'	=>	'int',
					// 'increment'=> 'AUTO_INCREMENT',
				// ),
				// 'receive_user'	=>	array(
					// 'name'	=>	'receive_user',
					// 'type'	=>	'int',
					
				// ),
				// 'receive_name'	=>	array(	//收货人
					// 'name'	=>	'receive_name',
					// 'type'	=>	'varchar',
					// 'length'=>	50,
				// ),
				// 'receive_phone'	=>	array(
					// 'name'	=>	'receive_phone',
					// 'type'	=>	'bigint',
					// 'length'=>	11,
				// ),
				// 'receive_addr'	=>	array(
					// 'name'	=>	'receive_addr',
					// 'type'	=>	'varchar',
					// 'length'=>	500,
				// ),
				// 'add_time'	=>	array(
					// 'name'	=>	'add_time',
					// 'type'	=>	'datetime',
				// ),
				// 'public_id'	=>	array(
					// 'name'	=>	'public_id',
					// 'type'	=>	'int',
				// ),
			// ),
			// 'indexs'	=>	array(
				// array('fields'=>array('id'),'type'=>'primary'),
				// array('fields'=>array('receive_user'),'type'=>'index','name'=>'receive_user'),
			// )
		// ),
		array(
			'table'	=>	'ims_xin_reward_log',//分销对照表
			'field'	=>	array(
				'id'	=>	array(
					'name'	=>	'id',
					'type'	=>	'int',
					'increment'=> 'AUTO_INCREMENT',
				),
				'from_user'	=>array(
					'name'	=>	'from_user',
					'type'	=>	'int',
				),
				'to_user'	=>	array(//上级
					'name'	=>	'to_user',
					'type'	=>	'int',
				),
				'level'		=>	array(//分销等级
					'name'	=>	'level',
					'type'	=>	'int',
				),
				'scale'		=>	array(//分销比例
					'name'	=>	'scale',
					'type'	=>	'int',

				),
				'recharge'	=>	array(//充值金额
					'name'	=>	'recharge',
					'type'	=>	'float',
					'length'=>	'10,2',
				),
				'money'		=>	array(//分销金额
					'name'	=>	'money',
					'type'	=>	'float',
					'length'=>	'10,2',
				),
				'add_time'	=>	array(
					'name'	=>	'add_time',
					'type'	=>	'datetime'
				),

			),
			'indexs'		=>	array(
				array('fields'=>array('id'),'type'=>'primary'),
			)

		),
		array(
			'table'	=>	'ims_xin_game',//游戏记录
			'field'	=>	array(
				'game_id'	=>	array(
					'name'	=>	'game_id',
					'type'	=>	'int',
					'increment'=> 'AUTO_INCREMENT',
				),
				'game_num'	=>	array(
					'name'	=>	'game_num',
					'type'	=>	'varchar',
					'length'=>	'32',
				),
				'game_mchnum'=>	array(
					'name'	=>	'game_mchnum',//企业打款单号
					'type'	=>	'varchar',
					'length'=>	'64',
					'default'=>	''
				),
				'game_spend'=>	array(//游戏金额
					'name'	=>	'game_spend',
					'type'	=>	'int',
					'default'=>	0
				),
				'game_profit'	=>	array(//游戏收益
					'name'	=>	'game_profit',
					'type'	=>	'int',
				),
				'game_user'	=>	array(
					'name'	=>	'game_user',
					'type'	=>	'int',
				),
				'game_second'=>	array(
					'name'	=>	'game_second',//次数
					'type'	=>	'int',
				),
				'game_option'=>	array(
					'name'	=>	'game_option',//选择
					'type'	=>	'int',//1小2大3单4双
					'length'=>	1
				),
				'game_time'	=>	array(//下单时间
					'name'	=>	'game_time',
					'type'	=>	'datetime',
				),
				'game_result'=>	array(
					'name'	=>	'game_result',//1输2赢
					'type'	=>	'int',
					'length'=>	1,
					'default'=>	0
				),
				'game_over_time'=>array(
					'name'	=>	'game_over_time',
					'type'	=>	'datetime',
				),
				'public_id'	=>	array(
					'name'	=>	'public_id',
					'type'	=>	'int',
				),
				
			),
			'indexs'		=>	array(
				array('fields'=>array('game_id'),'type'=>'primary'),
				array('fields'=>array('game_time'),'type'=>'index','name'=>'game_time'),
			)
		
		
		),
		array(
			'table'	=>	'ims_xin_cash',//提现
			'field'	=>	array(
				'id'	=>	array(
					'name'	=>	'id',
					'type'	=>	'int',
					'increment'=> 'AUTO_INCREMENT',
				),
				'cash_num' =>	array(
					'name'	=>	'cash_num',
					'type'	=>	'varchar',
					'length'=>	32,
				),
				'cash_three_num'=>array(
					'name'	=>	'cash_three_num',
					'type'	=>	'varchar',
					'length'=>	'50',
					'default'=>	'',
				
				),
				'cash_user'=>	array(
					'name'	=>	'cash_user',
					'type'	=>	'int',
				
				),
				'cash_fee'	=>	array(//手续费率
					'name'	=>	'cash_fee',
					'type'	=>	'float',
					'length'=>	'10,2'
				),
				'cash_money'=>	array(//提现金额
					'name'	=>	'cash_money',
					'type'	=>	'float',
					'length'=>	'10,2',
					
				),
				'cash_status'=>	array(//提现状态 1.已提现 2.待审核 3.提现失败
					'name'	=>	'cash_status',
					'type'	=>	'int',
				
				),
				'cash_msg'	=>	array(//商户打款返回
					'name'	=>	'cash_msg',
					'type'	=>	'varchar',
					'length'=>	'500',
				),
				'cash_time'	=>	array(
					'name'	=>	'cash_time',
					'type'	=>	'datetime'
				),
				'cash_otime'=>	array(
					'name'	=>	'cash_otime',
					'type'	=>	'datetime',
				)
				
			
			),
			'indexs'=>	array(
				array('fields'=>array('id'),'type'=>'primary'),
				array('fields'=>array('cash_user'),'type'=>'index','name'=>'cash_user'),
				array('fields'=>array('cash_status'),'type'=>'index','name'=>'cash_status'),
			)
		),
	);
	
	
	$sql = '';
	foreach($field as $v){
		$sql .= db_table_create_sql(array('tablename'=>$v['table'],'fields'=>$v['field'],'indexes' => $v['indexs'], 'engine' => 'InnoDB','increment' => 1, 'charset' => 'utf8_general_ci')).PHP_EOL;
		
	}
	$sql .= "ALTER TABLE  `ims_xin_goods` CHANGE  `update_time`  `update_time` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;".PHP_EOL;
	$sql .= "ALTER TABLE  `ims_xin_goods_class` CHANGE  `update_time`  `update_time` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;".PHP_EOL;
	//pdo_debug();
	pdo_run($sql);
		