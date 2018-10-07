<?php
/**
 * Created by PhpStorm.
 * User: linyh
 * Date: 2018/8/17
 * Time: 9:52
 */
namespace app\controllers\addons\course;

use app\models\Course;
use app\models\CourseSet;
use app\models\SignUp;
use app\services\Utils;
use yii\bootstrap\Button;
use Yii;
use EasyWeChat\Factory;
use yii\filters\VerbFilter;
use yii\web\Controller;

class CourseController extends Controller
{

    private $config = [
        // 必要配置
        'app_id'             => 'xxxx',
        'mch_id'             => 'your-mch-id',
        'key'                => 'qwertyuiopzxcvbnmasdfghjkl123456',   // API 密钥

        // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
        'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
        'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！

        'notify_url'         => '默认的订单回调地址',     // 你也可以在下单时单独设置来想覆盖它
    ];
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter:: className(),
                'actions' => [
                    'index' => ['post'],            //只允许get方式访问
                    'create' => ['post'],          //只允许用post方式访问
                    'update' => ['post']
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $request = Yii::$app->request;
        $token = $request->post('token');
        $course_id = $request->post('course_id');
        try{
            $courses = Course::find()
                ->with([
                'cset' => function ($query) {
                    $query->andWhere(['status' => 0]);
                },
            ])->asArray()->all();
            Utils::apiDisplay($courses);
        }catch (\Exception $e){
            $result['status'] = 1;
            $result['message'] = $e->getMessage();
            Utils::apiDisplay($result);
        }
        //Utils::apiDisplay($result);
    }

    public function actionInsert()
    {
        $request = Yii::$app->request;
        $token = $request->post('token');

        try{
            $course = new Course();
            $course_set = new CourseSet();
            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $course->name = $request->post('name');
                $course->content = $request->post('content');
                $course->status = $request->post('status');
                $course->teacher_id = $request->post('teacher_id');
                $course->cover = $request->post('cover');
                $course->price = $request->post('price')*100;
                $course->start = $request->post('start');
                if ($course->save()==false)
                    throw new \Exception("发生未知错误");

                $uservale = json_decode($request->post('course_set'),TRUE);
//                $uservale=array(
//                    '0'=>array('1','1','159839292','1598329292'),
//                    '1'=>array('0','1','1598767292','159835639292'),
//                    '2'=>array('0','1','159739292','15983569292'),
//                    '3'=>array('1','1','15985349292','156569839292'),
//                );//测试数据值

                $key=['status','course_id','stime','etime'];//测试数据键


                $res= Yii::$app->db->createCommand()->batchInsert(CourseSet::tableName(), $key, $uservale)->execute();//执行批量添加
                if (!$res)
                    throw new \Exception("发生未知错误");
                $transaction->commit();
                $result['status'] = 0;
                $result['message'] = "插入成功";
            } catch(\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch(\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        }catch (\Exception $e){
            $result['status'] = 1;
            $result['message'] = $e->getMessage();
            Utils::apiDisplay($result);
        }
        Utils::apiDisplay($result);
    }
    public function actionList()
    {
        $request = Yii::$app->request;
        $token = $request->post('token');
        $course_id = $request->post('course_id');
        try{
            $course = Course::findOne($course_id);
            if (!$course)
                throw new \Exception("寻找不到此课程");
            $course = $course->toArray();
            $course_set = CourseSet::find()->where(['course_id'=>$course_id])->asArray()->all();
            $result['course'] = $course;
            $result['course_set'] = $course_set;
        }catch (\Exception $e){
            $result['status'] = 1;
            $result['message'] = $e->getMessage();
            Utils::apiDisplay($result);
        }
        Utils::apiDisplay($result);
    }

    public function actionUpdate()
    {
        $request = Yii::$app->request;
        $token = $request->post('token');
        $course_id = $request->post('course_id');
            try {
                $course = Course::findOne($course_id);
                if (!$course)
                    throw new \Exception("寻找不到此课程");
                $db = Yii::$app->db;
                $transaction = $db->beginTransaction();
                $course->name = $request->post('name');
                $course->content = $request->post('content');
                $course->status = $request->post('status');
                $course->teacher_id = $request->post('teacher_id');
                $course->cover = $request->post('cover');
                $course->price = $request->post('price')*100;
                $course->start = $request->post('start');
                if ($course->update(true)==false)
                    throw new \Exception("发生未知错误");

                if ($request->post('delete_set_id')){
                    //清除相同id
                    $ids = array_unique(explode(",",$request->post('delete_set_id')));
                    $ids = implode(",",$ids);

                    if (CourseSet::deleteAll("id in ($ids) and course_id = ".$course_id)==false)
                        throw new \Exception("发生未知错误稍后重试");
                }

                $course_set = json_decode($request->post('course_set'),TRUE);
                $new_set = array();
                foreach ($course_set as $key=> $c){
                    if(!isset($c['id'])){
                        $new_set[] = $c;
                    }else{
                        $c_s_update = CourseSet::findOne($c['id']);
                        $c_s_update->status = $c['status'];
                        $c_s_update->course_id = $course_id;
                        $c_s_update->stime = $c['stime'];
                        $c_s_update->etime = $c['etime'];
                        if ($c_s_update->save()==false)
                            throw new \Exception("发生未知错误");
                    }
                }
                if (count($new_set)>0){
                    $key=['status','course_id','stime','etime'];
                    $res= Yii::$app->db->createCommand()->batchInsert('course_set', $key, $new_set)->execute();//执行批量添加
                    if (!$res)
                        throw new \Exception("发生未知错误");
                }
                $transaction->commit();
                $result['status'] = 0;
                $result['message'] = "更新成功";
            } catch (\Exception $e) {
                $transaction->rollBack();
                $result['status'] = 1;
                $result['message'] = $e->getMessage();
            } catch (\Throwable $e) {
                $transaction->rollBack();
                $result['status'] = 1;
                $result['message'] = $e->getMessage();
            }
        Utils::apiDisplay($result);
    }

    public function actionSignUp()
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
        $user = Users::findOne($uid);
        try{
            $courser = Course::findOne($course_id);
            if (!$courser)
                throw new \Exception('查询不到课程信息');

            $payResult = $app->order->unify([
                'body' => '异域舞团课程支付',
                'out_trade_no' => $trade_sn,
                'total_fee' => $courser->price,
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

    public function actionGetcourse()
    {
        $request = Yii::$app->request->post();
        $token = $request['token'];
        $uid = $request['uid'];
        try{
            $courses = SignUp::find()
                ->where(['uid'=>$uid,'status'=>0])
                ->with([
                'course_set' => function ($query) {
                    $query->andWhere(['status' => 0]);
                },
            ])->asArray()->all();
            foreach ($courses as $key =>$value){
                foreach ($courses[$key]['course_set'] as $k =>$c){
                    $courses[$key]['course_set'][$k]['stime'] = date('Y-m-d H:i:s',$c['stime']);
                    $courses[$key]['course_set'][$k]['etime'] = date('Y-m-d H:i:s',$c['etime']);
                }
            }
            $result['status'] = 0;
            $result['courses'] = $courses;
        }catch (\Exception $e){
            $result['status'] = 1;
            $result['message'] = $e->getMessage();
            Utils::apiDisplay($result);
        }
        Utils::apiDisplay($result);

    }

    public function actionMest()
    {

        $res =  Yii::$app->db->createCommand()->upsert('kinds', [
            'id' => 1,
            'name' => 'Front page',
            'status' => '0',
        ])->execute();
    }


}