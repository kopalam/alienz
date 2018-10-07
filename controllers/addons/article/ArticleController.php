<?php

namespace app\controllers\addons\article;

use app\models\Article;
use app\services\Auth;
use app\services\General;
use app\services\Utils;
use Codeception\Lib\Connector\Yii2;
use yii\web\Controller;

class ArticleController extends Controller
{
    /**
     * 展示文章标题
     * @throws \Throwable
     */
    public function actionIndex()
    {
        /*
         * 课程列表
         * */
        $request = \Yii::$app->request;
        $token = $request->post('token');
        $data['kid']  = $request->post('kid');
        try{
            $service    =   new General();
            $result    =   $service->articleList($data['kid']);
        }catch (\Exception $e){
            $result['status'] = 1;
            $result['message'] = $e->getMessage();
            Utils::apiDisplay($result);
        }
        Utils::apiDisplay($result);

    }

    public function actionArticle()
    {
        $request    =   \Yii::$app->request;
        $token  =   $request->post('token');
        $uid    =   $request->post('uid');
        $handle     =   $request->post('handle');
        $data['kid']    =   $request->post('kid');//分类id
        $values = [
            'title' => $request->post('title'),
            'content' => $request->post('content'),
            'dates' => time(),
            'kind_id' => $request->post('kind_id'),
            'status'=>$request->post('status'),
        ];
        $article = new Article(['scenario' => 'insert']);
        $article->attributes = $values;
        if ($article->validate()) {
            // 所有输入数据都有效 all inputs are valid
            if ($article->save()==false)
                throw new \Exception("新增文章失败");

        } else {
            // 验证失败：$errors 是一个包含错误信息的数组
            throw new \Exception("输入的信息有误");
        }
    }

    public function actionRead()
    {
        $request = \Yii::$app->request;
        $token = $request->post('token');

        $article = Article::findOne($request->post('id'));
        try{

            if (!$article)
                throw new \Exception("文章不存在");
            $article = $article->attributes;
            $article['dates'] = date("Y-m-d H:i:s",$article['dates']);
            $result['status'] = 0;
            $result['data'] = $article;
        }catch (\Exception $e){
            $result['status'] = 1;
            $result['message'] = $e->getMessage();
            Utils::apiDisplay($result);
        }
        Utils::apiDisplay($result);


    }

    /**
     * 新建文章
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
                throw new \Exception("你没有发表文章的权限！");

            $values = [
                'title' => $request->post('title'),
                'content' => $request->post('content'),
                'dates' => time(),
                'kind_id' => $request->post('kind_id'),
                'status'=>$request->post('status'),
            ];
            $article = new Article(['scenario' => 'insert']);
            $article->attributes = $values;
            if ($article->validate()) {
                // 所有输入数据都有效 all inputs are valid
                if ($article->save()==false)
                    throw new \Exception("新增文章失败");

            } else {
                // 验证失败：$errors 是一个包含错误信息的数组
                throw new \Exception("输入的信息有误");
            }

            //$data = ["message" => "新增文章成功"];
            $result['status'] = 0;
            $result['message'] = "新增文章成功";

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
                throw new \Exception("你没有修改文章的权限！");

            $article = Article::findOne($request->post('id'));
            $article->scenario = 'update';
            $values = [
                'title' => $request->post('title'),
                'content' => $request->post('content'),
                'dates' => time(),
                'kind_id' => $request->post('kind_id'),
                'status'=>$request->post('status'),
            ];

            $article->attributes = $values;
            if ($article->validate()) {
                // 所有输入数据都有效 all inputs are valid
                if ($article->update()==false)
                    throw new \Exception("修改文章失败");

            } else {
                // 验证失败：$errors 是一个包含错误信息的数组
                throw new \Exception("输入的信息有误");
            }


            //$data = ["message" => "新增文章成功"];
            $result['status'] = 0;
            $result['message'] = "修改文章成功";

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
                throw new \Exception("你没有修改文章的权限！");

            $article = Article::findOne($request->post('id'));
            $article->scenario = 'able';
            $values = [
                'status'=>$article->status==$article::STATUS_ACTIVE?$article::STATUS_INACTIVE:$article::STATUS_ACTIVE,
            ];

            $article->attributes = $values;
            if ($article->validate()) {
                // 所有输入数据都有效 all inputs are valid
                if ($article->update()==false)
                    throw new \Exception("修改文章失败");

            } else {
                // 验证失败：$errors 是一个包含错误信息的数组
                throw new \Exception("输入的信息有误");
            }


            //$data = ["message" => "新增文章成功"];
            $result['status'] = 0;
            $result['message'] = "修改文章成功";

        }catch (\Exception $e){
            $result['status'] = 1;
            $result['message'] = $e->getMessage();
            Utils::apiDisplay($result);
        }
        Utils::apiDisplay($result);
    }

}
