<?php
	global	$_W,$_GPC;
	load()->func('file');
	load()->library('qrcode/phpqrcode');
	$query = load()->object('query');
	$filepath = ATTACHMENT_ROOT;
	$width = 320;
	$height= 504;
	$fontfile = MODULE_ROOT.'/static/font/FZDHTK.TTF';
	$member = $query->from($this->table['member'])->select(array('member_nickname','member_avatar'))->where(array('member_id'=>$_SESSION['ids']))->get();
	$public_id = $_W['uniaccount']['uniacid'];

	if(!is_dir(MODULE_ROOT.'/static/sz_qr')){
		mkdirs(MODULE_ROOT.'/static/sz_qr');

	}

	//生成qr
	$qr = MODULE_ROOT.'/static/sz_qr/sz_qr'.$_SESSION['ids'].'.png';

	$qrurl = '';
	if(!file_is_image($qr) || !file_exists($qr)){
		
		QRcode::png($_W['siteroot']."app/".$this->createMobileUrl('home',array('fid'=>$_SESSION['ids'])),$qr,'L',5,1);
	}
	//缓存
	if(empty(cache_load('poster_bg')) || empty(cache_load('poster'))){

		cache_write('poster_bg',$this->settings['poster_bg']);
		cache_write('poster',$this->settings['poster']);	
	}
	
	
	$bg = $filepath.cache_load('poster_bg');
	$poster_data = cache_load('poster');
	$resoure = imagecreatetruecolor($width,$height);
	$white = imagecolorallocate($resoure,255,255,255);
	imagefill($resoure,0,0,$white);


	//bg
	if(file_is_image($bg) && file_exists($bg)){
		list($bgwidth,$bgheight) = getimagesize($bg);
		$imgfunc = 'imagecreatefrom'.getext($bg);
		
		$bgres = $imgfunc($bg);
		imagecopyresized($resoure,$bgres,0,0,0,0,$width,$height,$bgwidth,$bgheight);
	}
	
	foreach($poster_data as $v){
		$poster_tem[$v['type']] = array_map(function($v){
			
			return str_replace("px","",$v);
		},$v);
	}
	unset($poster_data);
	$poster = $poster_tem;

	//code
	$code	=	$_SESSION['ids'];
	$code_data = $poster['code'];
	if(!empty($code_data)){
	$codehex2color = hex2rgba($code_data['color']);
	$codecolor = imagecolorallocate($resoure,$codehex2color['r'],$codehex2color['b'],$codehex2color['g']);
	imagefttext($resoure,$code_data['size'],0,$code_data['left'],$code_data['top'],$codecolor,$fontfile,$code);
	}
	//name
	$name = $member['member_nickname'];
	$name_data = $poster['name'];
	if(!empty($name_data)){
	$namehex2color = hex2rgba($name_data['color']);
	$namecolor = imagecolorallocate($resoure,$namehex2color['r'],$namehex2color['b'],$namehex2color['g']);
	imagefttext($resoure,$name_data['size'],0,$name_data['left'],$name_data['top'],$namecolor,$fontfile,$name);
	}
	//qr
	list($qrwidth,$qrheight) = getimagesize($qr);
	
	$qr_img = imagecreatefrompng($qr);
	
	$qr_data = $poster['qr'];
	if(!empty($qr_data)){
	imagecopyresized($resoure,$qr_img,$qr_data['left'],$qr_data['top'],0,0,$qr_data['width'],$qr_data['height'],$qrwidth,$qrheight);

	}

	//avatar
	$avatar = $_W['siteroot']."web/index.php?c=utility&a=wxcode&do=image&attach=".urlencode($member['member_avatar']);
	list($avatarwidth,$avatarheight) = getimagesize($avatar);
	$avatar_img = imagecreatefromstring(file_get_contents($avatar));
	$avatar_data = $poster['img'];
	imagecopyresized($resoure,$avatar_img,$avatar_data['left'],$avatar_data['top'],0,0,$avatar_data['width'],$avatar_data['height'],$avatarwidth,$avatarheight);
	imagedestroy($avatar_img);
	
	//show
	header('Content-Type: image/jpeg');
	imagejpeg($resoure);
	imagedestroy($resoure);
	imagedestroy($bgres);
	imagedestroy($qr_img);