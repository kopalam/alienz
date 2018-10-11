<?php
/**
 * Created by PhpStorm.
 * User: kopa
 * Date: 2018/8/9
 * Time: 上午11:23
 */

namespace app\services\admin;
use app\models\admin\AdminUser;

class Adminajax
{
    private $request;
    private $response;

    function Login($telephone,$passwd)
    {
        $result     =  AdminUser::findOne(['telephone'=>$telephone]);
        if(!$result)
            throw new \Exception('不存在该用户',10002);

        $result  =   !(password_verify($passwd,$result->passwd)) ? 0 :1;
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