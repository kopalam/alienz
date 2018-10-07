<?php

namespace app\controllers\api;

use app\models\MyCourse;
use app\models\PayOrder;
use SebastianBergmann\CodeCoverage\Util;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\User;
use app\models\SignUp;
use app\services\Utils;
use app\services\Auth;
use app\models\Course;
use app\models\Sitemode;
use EasyWeChat\Factory;
use EasyWeChat\Payment\Order;



class PayController extends Controller
{

    private $config = [
        // 必要配置
        'app_id'             => 'wx351b9b52203fd817',
        'mch_id'             => '1512767781',
        'key'                => 'qwertyuiopzxcvbnmasdfghjkl123456',   // API 密钥
        'notify_url'         => '/',     // 你也可以在下单时单独设置来想覆盖它
    ];

    public function actionCreateorder()
    {
        $request = Yii::$app->request;
        $token = $request->post('token');
        $uid = $request->post('uid');
        $course_id = $request->post('course_id');
        $code = '';
        for($i=1;$i<=3;$i++){
            $code .= chr(rand(97,122));
        }
        $trade_sn = strtoupper(time().$code);
        $app = Factory::payment($this->config);

        $user = User::findOne($uid);
        try{
            $courser = Course::findOne($course_id);
            if (!$courser)
                throw new \Exception('查询不到课程信息');

            $payResult = $app->order->unify([
                'body' => '异域舞团课程支付',
                'out_trade_no' => $trade_sn,
                'total_fee' => $courser->price/100,
                'notify_url' => '/', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                'trade_type' => 'JSAPI',
                'openid' => $user->openId,
            ]);
            $jssdk = $app->jssdk;
            $json = $jssdk->bridgeConfig($payResult['prepay_id'],false);
            $result['status'] = 0;
            $result['pay'] = $json;
            $result['trade_sn']     =   $trade_sn;
        }catch (\Exception $e){
            $result['status'] = 1;
            $result['message'] = $e->getMessage();
            Utils::apiDisplay($result);
        }
        Utils::apiDisplay($result);
    }

    public function actionPaysuccess()
    {
        $request = Yii::$app->request;
        $token = $request->post('token');
        $uid = $request->post('uid');
        $tradeSn    =   $request->post('trade_sn');
        $courseId   =   $request->post('course_id');
        $price  =   $request->post('price');

        try{
            if(empty($tradeSn) || empty($courseId))
                throw new \Exception('支付码或课程不能为空',1);

            $transaction = Yii::$app->db->beginTransaction();
            $payOrder   =   new PayOrder();
            $payOrder->uid  =   $uid;
            $payOrder->price    =   $price;
            $payOrder->trade_sn     =   $tradeSn;
            $payOrder->course_id    =   $courseId;
            $payOrder->dates    =   time();
            if($payOrder->save() == false)
            {
                $transaction->rollBack();
                throw new \Exception('保存资料失败',1);
            }
            $userCourse     =   new MyCourse();
            $userCourse->course_id = $courseId;
            $userCourse->uid    =   $uid;
            if($userCourse->save() == false)
            {
                $transaction->rollBack();
                throw new \Exception('课程保存失败',1);
            }
            $transaction->commit();
            Utils::apiDisplay(['message'=>'报名成功啦!','status'=>0]);
        }catch (\Exception $e){
            $result['status'] = 1;
            $result['message'] = $e->getMessage();
            Utils::apiDisplay($result);
        }
    }


}
