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
use app\services\General;
use app\services\Utils;
use SebastianBergmann\CodeCoverage\Util;
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
        'key'                => 'key-for-signature',   // API 密钥

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

    public function actionCoursehandle()
    {
        /*
         * 课程设置
         * */
        $request    =   Yii::$app->request;
        $token = $request->post('token');
        $uid    =   $request->post('uid');
        $handle     =   $request->post('handle');
        $data['name']   =   $request->post('name');
        $data['content']    =   $request->post('content');
        $data['teacher_id']     =   $request->post('teacher_id');
        $data['cover'] =    $request->post('cover');
        $data['price']  =   $request->post('price');
        $data['start']  =   $request->post('start');
        $data['stime']  =   $request->post('stime');
        $data['etime']  =   $request->post('etime');
        $data['kid']    =   $request->post('kid');
        $data['remark'] =   $request->post('remark');
        $data['address'] =   $request->post('address');
        $data['classes'] =   $request->post('classes');
        $data['course_id']  =   $request->post('course_id');

<<<<<<< HEAD

=======
>>>>>>> 0412f7d675ad9361ea1f7d65cd3dd3f7d45b664d
        try{
            $data['price'] =    !empty($data['price']) ?  $data['price']*100 : 0 ;
            $service    =   new General();
            switch ($handle)
            {
                case 'create':
                    $result     = $service->addCourse($data);
                    break;
                case 'edit':
                    $result     = $service->editCourse($data);
                    break;
                case 'disable':
                    $result     = $service->disable('Course',$data['course_id']);
                    break;
                default:
<<<<<<< HEAD
                    $result =   ['status'=>1,'message'=>$handle];
=======
                    $result =   ['status'=>1,'message'=>'出错了'];
>>>>>>> 0412f7d675ad9361ea1f7d65cd3dd3f7d45b664d
                    break;
            }
            Utils::apiDisplay(['status'=>0,'data'=>$result]);
        }catch (\Exception $e){
            $result['status'] = 1;
            $result['message'] = $e->getMessage();
            Utils::apiDisplay($result);
        }
    }

    public function actionDetail()
    {
        $request = Yii::$app->request;
        $token = $request->post('token');
        $course_id = $request->post('course_id');
        $page   =   $request->post('page');
        $kid    =   $request->post('kid');
        try{
           $service     =   new General();
           $result  =   $service->courseDetail($course_id);
            Utils::apiDisplay(['status'=>0,'data'=>$result]);
        }catch (\Exception $e){
            $result['status'] = 1;
            $result['message'] = $e->getMessage();
            Utils::apiDisplay($result);
        }

    }

    public function actionList()
    {
        /*
         * 分类下的课程列表
         * */
        $request = Yii::$app->request;
        $token = $request->post('token');
        $page   =   $request->post('page');
        $kid    =   $request->post('kid');
        try{
            $service     =   new General();
            $result  =   $service->courseList($kid,$page);
            Utils::apiDisplay(['status'=>0,'data'=>$result]);
        }catch (\Exception $e){
            $result['status'] = 1;
            $result['message'] = $e->getMessage();
            Utils::apiDisplay($result);
        }

    }

    public function actionCourselist()
    {
        /*
         * 课程列表
         * 读取所有课程明细
         * */
        $request    =   Yii::$app->request;
        $page   =   $request->post('page');

        $service    =   new General();
        $result     =   $service->cList($page);

        Utils::apiDisplay(['status'=>0,'data'=>$result]);
    }



}