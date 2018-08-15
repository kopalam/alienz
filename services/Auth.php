<?php

	namespace app\services;
	use app\models\admin\AdminUser;
	use app\models\UserAuth;//权限规则表
	use app\models\UserLogs;//用户组明细表
    use app\models\UserSet;//用户组数据表名
    use app\models\Users;
    use app\models\UserOrder;//订单记录表
	use app\models\Cauth;

	// use Phalcon\Crypt; //加密类
	Class Auth{

        protected $_config = array(
            'AUTH_ON' => true, //认证开关
            'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
            'AUTH_GROUP' => 'app\models\UserSet', //用户组数据表名
            'AUTH_MIDDLE' => 'app\models\UserLogs', //用户组明细表
            'AUTH_RULE' => 'App\models\UserAuth', //权限规则表
           // 'AUTH_USER' => 'think_members'//用户信息表
        );
        public function __construct()
        {

        }

        //获得权限$name 可以是字符串或数组或逗号分割， uid为 认证的用户id， $or 是否为or关系，为true是， name为数组，只要数组中有一个条件通过则通过，如果为false需要全部条件通过。
        public function check($name, $uid,$relation='or') {
            if (!$this->_config['AUTH_ON'])
                return true;
            $authList = $this->getAuthList($uid);
            if (is_string($name)) {
                if (strpos($name, ',') !== false) {
                    $name = explode(',', $name);
                } else {
                    $name = array($name);
                }
            }
            $list = array(); //有权限的name
            foreach ($authList as $val) {
                if (in_array($val, $name))
                    $list[] = $val;
            }
            if ($relation=='or' and !empty($list)) {
                return true;
            }
            $diff = array_diff($name, $list);
            if ($relation=='and' and empty($diff)) {
                return true;
            }
            return false;
        }
        //获得用户组，外部也可以调用
        public function getGroups($uid) {
            static $groups = array();
            if (isset($groups[$uid]))
                return $groups[$uid];

            $Groups = new UserGroups();
            $userGroups = $Groups->getUserGroups($uid,$this->_config['AUTH_MIDDLE'],$this->_config['AUTH_GROUP']);
            $groups[$uid]=$userGroups?$userGroups:array();
            return $groups[$uid];
        }
        //获得权限列表,不让外部访问
        protected function getAuthList($uid) {
            static $_authList = array();
            if (isset($_authList[$uid])) {
               return $_authList[$uid];
            }
            if(isset($_SESSION['_AUTH_LIST_'.$uid])){
                return $_SESSION['_AUTH_LIST_'.$uid];
            }
            //读取用户所属用户组
            $groups = $this->getGroups($uid);
            $ids = array();
            foreach ($groups as $g) {
                $ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
            }
            $ids = array_unique($ids);
            if (empty($ids)) {
                $_authList[$uid] = array();
                return array();
            }
            //读取用户组所有权限规则
            //status=1 代表启用，0代表关闭
            $ids =  array_values($ids);
            $map=array(
                'id IN ({id:array}) AND status = 1',
                'bind' => [
                    'id' => $ids
                ],
               // 'status'=>0
            );
            $rules = $this->getRules($map);
            //循环规则，判断结果。
            $authList = array();
            foreach ($rules as $r) {
                if (!empty($r['condition'])) {
                    //条件验证
                    $user = $this->getUserInfo($uid);
                    $command = preg_replace('/\{(\w*?)\}/', '$user[\'\\1\']', $r['condition']);
                    //dump($command);//debug
                    @(eval('$condition=(' . $command . ');'));
                    if ($condition) {
                        $authList[] = $r['name'];
                    }
                } else {
                    //存在就通过
                    $authList[] = $r['name'];
                }
            }
            $_authList[$uid] = $authList;
            if($this->_config['AUTH_TYPE']==2){
                //session结果
                $_SESSION['_AUTH_LIST_'.$uid]=$authList;
            }
            return $authList;
        }
        //获得用户资料,根据自己的情况读取数据库
//        protected function getUserInfo($uid) {
//            static $userinfo=array();
//            if(!isset($userinfo[$uid])){
//                $userinfo[$uid]=M()->table($this->_config['AUTH_USER'])->find($uid);
//            }
//            return $userinfo[$uid];
//        }

        //根据条件获取规则
        public function getRules($map)
        {
            $rules = AuthRules::find($map)->toArray();
            return $rules;
        }

        public function checkAuth($userEmail,$userPasswd)
		{
			/*
				@查询AdminUsers表，验证用户登录信息是否正确，所属级别
				@使用password_verify()验证密码是否正确
				@如果用户密码正确，查询对应用户组权限
				@ 

			*/
			$parm 	=	['conditions'=>"email = '".$userEmail."'"];
			$verifiCation 	=	AdminUsers::findFirst( $parm );

			$checkResult 	=	empty(password_verify($userPasswd,$verifiCation->passwd))?0:1; //验证结果
			
			if($checkResult == 0)
				return 0;

			//$authResult 	=	$this->authRule($verifiCation->id);
			$authResult['userName'] 	=	$verifiCation->nickName;
            $authResult['uid'] 	=	$verifiCation->id;

			

			return $authResult;


			
		}

		public function authRule($uid)
		{
			/*
				查询中间表auth_middle
				@组合用户信息： 用户权限 group = 1，groupName = 管理员 ,uid = 1,token = kfljlasd;
			*/
			$parm 	=	['conditions'=>'uid = '.$uid.' and status = 0'];
			$authMiddle 	=	AuthMiddle::find( $parm )->toArray();

			$groupParm 	=	['conditions'=>'id = '.$authMiddle[0]['group_id']];	//管理员组表查询条件
			$authGroup 	= 	AuthGroup::find($groupParm)->toArray();
			$token 	=	uniqid().'_'.time().'_'.uniqid();
			$userData 	=	['groupId'=>$authMiddle[0]['group_id'],'groupName'=>$authGroup[0]['title'],'module'=>$authGroup[0]['module'],'uid'=>$uid,'token'=>$token];
			return $userData;

		}

		function checkUserVipInfo($uid)
	{
		/*

			查询用户vip级别，如果不存在，则创建，过期则返回错误
			返回json数据：体验时间 stime，etime，体验是否过期 outdate = true/false，
		*/
		$userVip 	=	ActivityVip::findFirst(['conditions'=>' uid = '.$uid]);

		$userVipData 	=	empty($userVip) ? 0 : 1;

		switch ($userVipData) {
			case 0:
					$createVip 	=	new ActivityVip();
					$createVip->uid 	=	$uid;
					$createVip->vip 	=	1; //体验vip
					$createVip->vip_level 	=	1;//vip级别默认为1，1为体验级别
					$createVip->vip_stime 	=	time();
					$createVip->vip_etime 	=	time()+86400; //体验期为1天
					$createVip->save();

					/*同时创建cauth，如果appid为0，使用平台默认的授权公众号*/
					$cauth 	=	new Cauth();
					$cauth->appid 	=	0;
					$cauth->access_token 	=	0;
					$cauth->dates 	=	time();
					$cauth->uid 	=	$uid;
					$cauth->cauth_iden	=	uniqid().time();
					$cauth->status 	=	0;
					$cauth->types 	=	1; 	

					$cauth->save();
					$iden 	=	$cauth->iden;
					if($cauth->appid == 0)
					{
						$defaultIden = Cauth::findFirst(1);
						$iden 	=	$defaultIden->cauth_iden;
					}
					$data = ['vip_etime'=>date('Y-m-d H:i:s',strtotime('+1 day')),'outdate'=>0,'iden'=>$iden];
				break;
			case 1 :
					if(time() > $userVip->vip_etime){
						$data 	=	['status'=>0,'outdate'=>1,'message'=>'体验期限已过,成为Vip免费畅享全部营销套件'];
					}else{
						/*查询cauth表，如果uid不存在或对应的appid等于0，则使用默认的平台公众号*/
						$findIden 	=	Cauth::findFirst(['conditions'=>'uid = '.$uid]);
							if($findIden->appid == 0 || !$findIden->uid)
							{
								$defaultIden = Cauth::findFirst(1);
								$iden 	=	$defaultIden->cauth_iden;
							}
						$data 	=	['status'=>0,'vip_etime'=>$userVip->vip_etime,'outdate'=>0,'iden'=>$iden];
					}
					break;
			default: 
					$data = ['status'=>1,'outdate' => 1,'message'=>'未知错误'];
				# code...
				break;
		}
		return $data;

	}

    }