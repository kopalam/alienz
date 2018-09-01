<?php

namespace app\controllers\addons\kinds;

use app\models\Article;
use app\models\Kinds;
use app\services\Auth;
use app\services\test;
use app\services\Utils;
use Codeception\Lib\Connector\Yii2;
use yii\web\Controller;

class KindsController extends Controller
{
    /**
     * 展示所有分类
     * @throws \Throwable
     */
    public function actionIndex()
    {
        $request = \Yii::$app->request;
        $token = $request->post('token');
        $kinds = Kinds::find()
            ->select(['id','name','status'])
            ->asArray()
            ->all();
        $result['status'] = 0;
        $result['data'] = $kinds;
        Utils::apiDisplay($result);

    }

    public function actionKind()
    {
        $request = \Yii::$app->request;
        $token = $request->post('token');
        $articles = Article::find()
            ->where(['kind_id'=>$request->post('kind_id')])
            ->asArray()
            ->all();
        $result['status'] = 0;
        $result['data'] = $articles;
        Utils::apiDisplay($result);
    }

    /**
     * 新建分类
     * @throws \Throwable
     */
    public function actionInsert()
    {
        $request = \Yii::$app->request;
        $token = $request->post('token');
        $uid = $request->post('uid');
        try{
            $auth = new Auth();
            $res = $auth->check("admin",$uid);
            if (!$res)
                throw new \Exception("你没有编辑分类的权限！");

            $values = [
                'name' => $request->post('name'),
                'status' => $request->post('status'),
            ];
            $kind = new Kinds(['scenario' => 'insert']);
            $kind->attributes = $values;
            if ($kind->validate()) {
                // 所有输入数据都有效 all inputs are valid
                if ($kind->save()==false)
                    throw new \Exception("新增分类失败");
            } else {
                // 验证失败：$errors 是一个包含错误信息的数组
                throw new \Exception("输入的信息有误");
            }

            //$data = ["message" => "新增文章成功"];
            $result['status'] = 0;
            $result['message'] = "新增分类成功";

        }catch (\Exception $e){
            $result['status'] = 1;
            $result['message'] = $e->getMessage();
            Utils::apiDisplay($result);
        }
        Utils::apiDisplay($result);
    }

    /**
     * 更新文章
     * @throws \Throwable
     */
    public function actionUpdate()
    {
        $request = \Yii::$app->request;
        $token = $request->post('token');
        $uid = $request->post('uid');
        try{
            $auth = new Auth();
            $res = $auth->check("admin",$uid);
            if (!$res)
                throw new \Exception("你没有修改分类的权限！");

            $kind = Kinds::findOne($request->post('id'));
            $kind->scenario = 'update';
            $values = [
                'name' => $request->post('name'),
                'status'=>$request->post('status'),
            ];

            $kind->attributes = $values;
            if ($kind->validate()) {
                // 所有输入数据都有效 all inputs are valid
                if ($kind->update()==false)
                    throw new \Exception("修改分类失败");

            } else {
                // 验证失败：$errors 是一个包含错误信息的数组
                throw new \Exception("输入的信息有误");
            }


            //$data = ["message" => "新增文章成功"];
            $result['status'] = 0;
            $result['message'] = "修改分类成功";

        }catch (\Exception $e){
            $result['status'] = 1;
            $result['message'] = $e->getMessage();
            Utils::apiDisplay($result);
        }
        Utils::apiDisplay($result);
    }

    /**
     * 禁用或者解禁文章
     * @throws \Throwable
     */
    public function actionAble()
    {
        $request = \Yii::$app->request;
        $token = $request->post('token');
        $uid = $request->post('uid');
        try{
            $auth = new Auth();
            $res = $auth->check("admin",$uid);
            if (!$res)
                throw new \Exception("你没有修改分类的权限！");

            $kind = Kinds::findOne($request->post('id'));
            $kind->scenario = 'able';
            $values = [
                'status'=>$kind->status==$kind::STATUS_ACTIVE?$kind::STATUS_INACTIVE:$kind::STATUS_ACTIVE,
            ];

            $kind->attributes = $values;
            if ($kind->validate()) {
                // 所有输入数据都有效 all inputs are valid
                if ($kind->update()==false)
                    throw new \Exception("修改分类失败");

            } else {
                // 验证失败：$errors 是一个包含错误信息的数组
                throw new \Exception("输入的信息有误");
            }


            //$data = ["message" => "新增文章成功"];
            $result['status'] = 0;
            $result['message'] = "修改分类成功";

        }catch (\Exception $e){
            $result['status'] = 1;
            $result['message'] = $e->getMessage();
            Utils::apiDisplay($result);
        }
        Utils::apiDisplay($result);
    }

}
