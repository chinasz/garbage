<?php
$sql = <<<EOF
	drop table `ims_xin_course`;
	drop table `ims_xin_member`;
	drop table `ims_xin_goods`;
	drop table `ims_xin_goods_class`;
	drop table `ims_xin_order`;
	drop table `ims_xin_recharge`;
	drop table `ims_xin_recharge_meal`;
	drop table `ims_xin_game`;
	drop table `ims_xin_cash`;
EOF;
pdo_run($sql);