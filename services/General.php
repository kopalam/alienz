<?php
/**
 * Created by PhpStorm.
 * User: kopa
 * Date: 2018/8/9
 * Time: 下午2:53
 */

namespace app\services;


class General
{

    function __construct()
    {
        $this->model = trim('app\models\ ');
    }

    function disable($table,$id)
    {
        $model  =   $this->model.$table;
        $result     =   $model::findOne($id);
        $check  =   is_object($result) ? 0 : 1;
        $result->status     =   $result->status == 0 ? 1: 0;
        if($result->save() == false)
            throw new \Exception('更新状态失败',30001);

        return true;
    }

    function delete($table,$id)
    {
        $model  =   $this->model.$table;
        $reslut     =   $model::find()->where(['id'=>$id])->one();
        $reslut->delete();

        return true;
    }

}