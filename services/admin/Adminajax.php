<?php
/**
 * Created by PhpStorm.
 * User: kopa
 * Date: 2018/8/9
 * Time: 上午11:23
 */

namespace app\services\admin;
use app\models\admin\AdminUser;
<<<<<<< HEAD
use app\services\Auth;
=======
>>>>>>> 0412f7d675ad9361ea1f7d65cd3dd3f7d45b664d

class Adminajax
{
    private $request;
    private $response;

<<<<<<< HEAD
    function Login($name,$passwd)
    {
        $result     =  AdminUser::find()->where(['name'=>$name])->asArray()->one();
        if(!$result)
            throw new \Exception('不存在该用户',10002);

        $checkPasswd  =   !(password_verify($passwd,$result['passwd'])) ? 1 :0;
        if($checkPasswd==1)//密码错误
            throw new \Exception('账号或密码不正确，请重新试试',1);

        //查询对应用户权限并返回
        $auth   =   new Auth();
        $result['auth'] =   $auth->check('管理员,导师,付费学员,普通会员',$result['id']);
        echo json_encode($result);exit();
=======
    function Login($telephone,$passwd)
    {
        $result     =  AdminUser::findOne(['telephone'=>$telephone]);
        if(!$result)
            throw new \Exception('不存在该用户',10002);

        $result  =   !(password_verify($passwd,$result->passwd)) ? 0 :1;
>>>>>>> 0412f7d675ad9361ea1f7d65cd3dd3f7d45b664d
        return $result;
    }

    function Create($telephone,$passwd)
    {
        $result     =  AdminUser::findOne(['telephone'=>$telephone]);
        if($result)
            throw new \Exception('电话号码已存在',10003);
        $data   =   new AdminUser();
        $data->telephone     =   $telephone;
        $data->passwd   =   $passwd;
        if($data->insert() == false)
            throw new \Exception('注册用户失败',10001);

        $result =   ['status'=>true,'uid'=>$data->id];
        return $result;
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