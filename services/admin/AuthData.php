<?php
/**
 * Created by PhpStorm.
 * User: kopa
 * Date: 2018/8/9
 * Time: 上午11:23
 * 权限管理业务类
 */

namespace app\services\admin;
use app\models\admin\AdminUser;

class AuthData
{

    function __construct()
    {
        $this->model    =   trim('\app\models\ ');
    }

    function authList()
    {
        /*
         * 查询当前权限分类
         * */
        $activityVip    =   $this->model.'ActivityVipSet';
        $authData   =   $activityVip::find(['status'=>0]);
        if(!$authData)
            throw new \Exception('还没有创建用户权限',1);
        return $authData;

    }

    function createAuth($data)
    {
        $activityVip    =   $this->model.'ActivityVipSet';
        $authData   =   new $activityVip;

        $authData->name    =   $data['name'];
        $authData->title   =   $data['title'];
        $authData->price   =   0;
        $authData->date    =   time();
        $authData->status  =   0;
        $authData->pay     =   0;
        $authData->type     =   1;

        if($authData->insert() == false)
            throw new \Exception('创建失败',1);
        return true;

    }

    function editAuth($data)
    {
        $activityVip    =   $this->model.'ActivityVip';
        $authData   =   $activityVip::findOne(['id'=>$data]);
        if(!$authData)
            throw new \Exception('不存在该权限，无法编辑',1);
        $authData->name    =   $data['name'];
        $authData->title   =   $data['title'];
        $authData->price   =   0;
        $authData->date    =   time();
        $authData->status  =   $authData->status;
        $authData->pay     =   0;
        $authData->type     =   1;

        if($authData->save() == false)
            throw new \Exception('编辑失败',1);
        return true;

    }

}