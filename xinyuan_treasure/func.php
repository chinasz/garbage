<?php
/**
*自定义表单验证
*
*/
	class form{
		protected  $data=array();
		protected  $rule=array();	/*['field'=>'rule1|rule2|...']*/
		protected  $message=array();
		public $error=array();
		
		public function __construct($data,$rule,$message){
			
			$this->data = $data;
			$this->rule = $rule;
			$this->message	= $message;
			
		}
		
		public function getRule(){
			
			foreach($this->rule as $formname=>$formrule){
				if(strpos($formrule,'|')){
					$this->rule[$formname] = explode('|',$formrule);
				}else{
					$this->rule[$formname] = $formrule;
				}
			}
			
			return $this;
				
		}
		
		public function getMessage($field,$rule,$rulesufix=''){
			if($this->$rule($this->data[$field],$rule,$rulesufix)){
				if($rulesufix ==''){
					$this->error[] = $this->message[$field.'.'.$rule];
				}else{
					
					$this->error[] = $this->message[$field.'.'.$rule.':'.$rulesufix];
				}
			}
			
			return $this;
		}
		
		public function check(){
			$this->getRule();
			
			foreach($this->rule as $key=>$rules){
				if(is_array($rules)){
				
					for($i=0;$i<count($rules);$i++){
						if(strpos($rules[$i],":")){
							$this->getMessage($key,explode(':',$rules[$i])[0],explode(':',$rules[$i])[1]);
						
						}else{
							$this->getMessage($key,$rules[$i]);
							
						}
					}
				}else{
					if(strpos($rules,":")){
						$this->getMessage($key,explode(':',$rules)[0],explode(':',$rules)[1]);
					
					}else{
						$this->getMessage($key,$rules);
						
					}
					
				}
			}
			
			return true;
			
			
		}
		public function required($val,$rule){
			
			
			return strval($val)=='';
			
			
		}
		
		
		public function maxlen($val,$rule,$suffix){
			if($val==""){
				return false;
			}else{
			
				return mb_strlen($val)> intval($suffix);
			
			}
		}
		
		
		public function getError(){
			
			return $this->error;
			
		}
	}
	
	/**
	*登陆
	*
	*/
	function login($table,$fid,$uniacid){

		$mc = load()->model('mc');
		$query = load()->object('query');
		unset($_SESSION['ids']);
		if(empty($_SESSION['ids'])){
			$mem = $query->from($table['member'])->select(array('member_id','is_del','member_avatar','member_nickname'))->where(array('member_openid'=>$_SESSION['openid']))->get();
			$member_id = $mem['member_id'];
			
			$time = date('Y-m-d H:i:s');
			if($member_id){
				$_SESSION['ids']	=	$member_id;
				$_SESSION['avatar']	=	$mem['member_avatar'];
				$_SESSION['nickname']	=	$mem['member_nickname'];
				pdo_update($table['member'],array('member_lasttime'=>$time),array('member_id'=>$member_id));
				
				
				if($mem['is_del'] == 0){
					
					unset($_SESSION['ids']);
					message('您被限制登陆');
				}
			}else{
				$member = mc_oauth_userinfo();
				$data = array(
					'member_nickname'	=>	$member['nickname'],
					'member_openid'		=>	$_SESSION['openid'],
					'member_score'		=>	0,
					'member_avatar'		=>	$member['avatar'],
					'member_fid'		=>	$fid,
					'member_firsttime'	=>	$time,
					'member_firstip'	=>	ip2long(CLIENT_IP),
					'member_lasttime'	=>	$time,
					'public_id'			=>	$uniacid
				);
				$res = pdo_insert($table['member'],$data);
				if(empty($res)){
					
					message('存储失败');
				}
				$_SESSION['ids'] = pdo_insertid();
				$_SESSION['avatar']	=	$member['avatar'];
				$_SESSION['nickname']	=	$member['nickname'];
			}
			
		}
		
	}
	
	/**
	*num
	*
	*/
	function orderNum($length=32){
		
		$time = date('YmdHis');
		$mictime = floor(microtime(true)*1000);
		
		$len = $length - strlen($time.$mictime);
		if($len>0){
			for($i=1;$i<=$len;$i++){
				if($i % 2 ==0){
					$suffix .= chr(rand(65,90));
					continue;
				}else{
					
					$prefix .= chr(rand(97,122));
					continue;
				}
				
				
			}
			
			
		}
		
		
		return $prefix.$time.$mictime.$suffix;
		
	}
	
	/**
	*获取文件后缀
	*
	*/
	
	function getext($path){
		$arr = pathinfo($path);
		$arr['extension'] = $arr['extension']=='jpg'?'jpeg':$arr['extension'];
		return $arr['extension'];
		
		
	}
	
	/**
	*十六进制转rbg
	*
	*/
	   function hex2rgba($color, $opacity = false, $raw = false) {
        $default = array('r'=>0,'b'=>0,'g'=>0);
        
        if(empty($color))
              return $default; 
        
        if ($color[0] == '#' ) {
            $color = substr( $color, 1 );
        }
        if (strlen($color) == 6) {
                $hex = array('r'=>$color[0] . $color[1],'b'=>$color[2] . $color[3],'g'=>$color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array('r'=>$color[0] . $color[0],'b'=>$color[1] . $color[1],'g'=>$color[2] . $color[2] );
        } else {
                return $default;
        }
 
        $rgb =  array_map('hexdec', $hex);
 
        return $rgb;
    }
	/**
	*分销
	*
	*/
	function fenxiao($table,$id){
		
		$sql = "select member_fid,member_id from ".tablename($table['member'])." where member_id =:member_id and is_del = 1";
		for($i=0;$i<=2;$i++){

			$id = pdo_fetchcolumn($sql,array(':member_id'=>$id));

			if($id == 0 && !$id){
				
				break;
			}else{
				
				$arr[$i]=$id;
				
			}
			
			
		}
		
		
		return $arr;	
	}
	/*
	*wechat browser
	*/
	function isWeichatBrowser() {
		if ( false !== strpos( $_SERVER[ 'HTTP_USER_AGENT' ], 'MicroMessenger' ) ) {
		  return true;
		}
		return false;
	}
	  