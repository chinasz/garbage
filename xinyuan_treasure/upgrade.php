<?php
    $field =array( 
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
        );
    $sql = '';
	foreach($field as $v){
		$sql .= db_table_create_sql(array('tablename'=>$v['table'],'fields'=>$v['field'],'indexes' => $v['indexs'], 'engine' => 'InnoDB','increment' => 1, 'charset' => 'utf8_general_ci')).PHP_EOL;
		
	}
    pdo_run($sql);