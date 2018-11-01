<?php
/**
 * Created by PhpStorm.
 * User: kopa
 * Date: 2018/8/9
 * Time: 上午11:23
 */

namespace app\services\admin;
use app\models\ActivityVip;
use app\models\ActivityVipSet;
use app\models\admin\AdminUser;
use app\services\Auth;
use Yii;

class Adminajax
{
    private $request;
    private $response;

    function Login($telephone,$passwd)
    {
        $result     =  AdminUser::find()->where(['telephone'=>$telephone])->asArray()->one();
        if(!$result)
            throw new \Exception('不存在该用户',10002);

        $checkPasswd  =   !(password_verify($passwd,$result['passwd'])) ? 1 :0;
        if($checkPasswd==1)//密码错误
            throw new \Exception('账号或密码不正确，请重新试试',1);

        //查询对应用户权限并返回
        $auth   =   new Auth();
        $result['auth'] =   $auth->check('管理员,导师,付费学员,普通会员',$result['id']);
        echo json_encode($result);exit();
        return $result;
    }

    function Create($name,$passwd,$telephone,$auth)
    {
        /*
         * 创建用户的同时，把用户uid写入activity_vip表
         * */
        $transaction = Yii::$app->db->beginTransaction();
        $result     =  AdminUser::find()->where(['telephone'=>$telephone])->asArray()->one();
        if(!empty($result['telephone']))
            throw new \Exception('电话号码已存在',10003);
        $data   =   new AdminUser();
        $data->telephone     =   $telephone;
        $data->name     =   $name;
        $data->passwd   =   $passwd;
        if($data->insert() == false){

            $transaction->rollBack();
            throw new \Exception('注册用户失败',10001);
        }
//        echo ActivityVipSet::findOne($auth)->name;exit();

        //写入activity_vip表
        $setVip     =   new ActivityVip();
        $setVip->uid    =   $data->id;
        $setVip->vip_id     =   $auth;
        $setVip->vip    =   ActivityVipSet::findOne($auth)->name;
        $setVip->status     =   0;
        $setVip->vip_stime    =   time();
        $setVip->vip_etime    =   0;
        if($setVip->insert() == false){
            $transaction->rollBack();
            throw new \Exception('写入vip表失败',10001);
        }
        $transaction->commit();
        $res =   ['status'=>true,'data'=>['uid'=>$data->id,'vip_id'=>$setVip->id]];

        return $res;
    }

    function Edit($uid,$telephone,$name,$passwd)
    {
        $result     =   AdminUser::findOne($uid);
        if(!$result)
            throw new \Exception('不存在该用户',10002);
        $result->telephone  =   $telephone;
        $result->name   =   $name;
        $result->passwd     =   $passwd;
        if($result->save() == false)
            throw new \Exception('更新信息失败');
        return true;
    }



}