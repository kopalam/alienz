<?php
/**
 * Created by PhpStorm.
 * User: kopa
 * Date: 2018/8/9
 * Time: 下午2:53
 */

namespace app\services;
use app\models\admin\AdminUser;
use app\models\Article;
use \app\models\Kinds;
use \app\models\KindsRoute;
use \app\models\Course;
use \app\models\CourseSet;
use app\models\score\ScoreMark;
use app\models\score\ScoreSet;
use \app\models\TeacherCourse;
use Yii;
use Carbon\Carbon;
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

    function kindsDisable($route_id)
    {
        /*
         * 通过routeid进行禁用
         * */

        $kindsRoute  =   KindsRoute::findOne($route_id);
        if(!$kindsRoute)
            throw new \Exception('不存在该id',1);

        $transaction = Yii::$app->db->beginTransaction();

        $kindsRoute->status = $kindsRoute->status==0 ? 1 :0;
        if($kindsRoute->save() == false){
            $transaction->rollBack();
            throw new \Exception('更新状态失败',30001);
        }

        $kindsId    =   $kindsRoute->parent_id == 0 ? $kindsRoute->kid :$kindsRoute->parent_id;

        $kinds  =   Kinds::findOne($kindsId);
        if(!$kinds)
            throw new \Exception('不存在分类id',1);
        $kinds->status = $kinds->status == 0 ? 1 : 0;
        if($kinds->save() == false){
            $transaction->rollBack();
            throw new \Exception('更新状态失败',30001);
        }
        $transaction->commit();
           return true;
    }

    function delete($table,$id)
    {
        $model  =   $this->model.$table;
        $reslut     =   $model::find()->where(['id'=>$id])->one();
        $reslut->delete();

        return true;
    }

    function kinds()
    {
        /*
         * 通过kinds_route读取对应分类
         * */
        $kindsRouteModel     =   $this->model.'KindsRoute';
        $routeData  =   $kindsRouteModel::find()->where('status = 0')->asArray()->all();
        $result     =   [];
        foreach ($routeData as $key =>$value){
            $kindsModel     =   $this->model.'Kinds';
            $kindsData  =   $kindsModel::findOne($value['kid']);
            if($value['parent_id'] == 0){

                $result[$key]['route_id']       =   $value['id'];
                $result[$key]['kid']    =   $kindsData->id;
                $result[$key]['kinds']  =   $kindsData->name;
                $result[$key]['image']  =   $kindsData->image;
                $result[$key]['parent']     =   0;
                $result[$key]['status']     =   $kindsData['status'];
            }else{
                $kindParent     =   $kindsModel::findOne($value['parent_id']);
                $result[$key]['route_id']       =   $value['id'];
                $result[$key]['parent_id']    =  $value['parent_id'];
                $result[$key]['parent']      = $kindParent->name;
                $result[$key]['image']=$kindParent->image;
                $result[$key]['kid']    =   $kindsData->id;
                $result[$key]['kinds']  =   $kindsData->name;
                $result[$key]['image']  =   $kindsData->image;
                $result[$key]['status']     =   $kindsData['status'];
            }

        }

        return $result;
    }

    function kindsInsert($data)
    {
        /*
         * 写入分类表
         * */
        $kinds  =   new Kinds();
        $kindsRoute     =   new KindsRoute();

        $parentId   = empty($data['parent_id']) ? 0 : $data['parent_id'];
        if($parentId!==0)
        {
            $findParent     =   Kinds::findOne($parentId);
            if(!$findParent)
                throw new \Exception('不存在该分类',1);
        }
        $transaction = Yii::$app->db->beginTransaction();
        $kinds->name    =   $data['name'];
        $kinds->image    =   $data['image'];
        $kinds->status  =   0;
        if($kinds->insert()==false){
            $transaction->rollBack();
            throw new \Exception('写入分类表失败',1);
        }

        $kindsRoute->kid    =   $kinds->id;
        $kindsRoute->parent_id  =   $parentId;
        $kindsRoute->status     =   0;
        if($kindsRoute->insert()==false){
            $transaction->rollBack();
            throw new \Exception('写入分类路由表失败',1);
        }
        $transaction->commit();
        return true;
    }

    function kindsEdit($data)
    {

        $kindsRoute     =   KindsRoute::findOne($data['route_id']);
        if(!$kindsRoute)
            throw new \Exception('该路由表不存在该id',1);

        $parentId   = empty($data['parent_id']) ? 0 : $data['parent_id'];
        if($parentId!==0)
        {
            $findParent     =   Kinds::findOne($parentId);
            if(!$findParent)
                throw new \Exception('不存在该分类',1);
        }

        $transaction = Yii::$app->db->beginTransaction();
        $kindsRoute->kid    =   $data['kid'];
        $kindsRoute->parent_id  =   $parentId;
        $kindsRoute->status     =   $kindsRoute->status;
        if($kindsRoute->save()==false){
            $transaction->rollBack();
            throw new \Exception('写入分类路由表失败',1);
        }

        $kinds  =   Kinds::findOne($data['kid']);
        if(!$kinds)
            throw new \Exception('不存在该分类',1);
        $kinds->name    =   $data['name'];
        $kinds->image    =   $data['image'];
        $kinds->status  =    $kinds->status;
        if($kinds->save()==false){
            $transaction->rollBack();
            throw new \Exception('写入分类表失败',1);
        }

        $transaction->commit();
        return true;
    }

//-------------------------------

    public function addArticle($data)
    {
        /*
         * 添加课程 course
         * 开始时间 course_set
         * 所属老师 teacher_course
         * 查找老师 admin_user
         * */


    }


    public function articleList($kid,$page)
    {
        /*
         * 查询分类下的对应文章
         * kid 分类id
         * */
        if(empty($page))
            $page     =   1;

        $size = 8;//一次读取20条信息
        $skip = (intval($page)-1)*$size;
        if(!empty($kid)){
            $article    =   Article::find()
                ->where(['kind_id'=>$kid,'status'=>0])
                ->limit($size)
                ->offset($skip)
                ->orderBy('id desc')
                ->asArray()->all();
        }else{
            $article    =   Article::find()
                ->where('status=0')
                ->limit($size)
                ->offset($skip)
                ->orderBy('id desc')
                ->asArray()->all();
        }

        $result     =   [];
        if(empty($article))
            throw new \Exception('该分类下还没有文章哦',1);

        foreach ($article as $key =>$value) {
            $result[$key]['id']     =   $value['id'];
            $result[$key]['title']     =   $value['title'];
            $result[$key]['content']     =   $value['content'];
            $result[$key]['kind_id']     =   $value['kind_id'];
            $result[$key]['kind_name']  =   Kinds::findOne($value['kind_id'])->name;
            $result[$key]['cover']     =   $value['cover'];
            $result[$key]['status']     =   $value['status'];
            $result[$key]['dates']     =   date('Y-d-d H:i:s',$value['dates']);
        }
        return $result;

    }

//-----------------------------------
    public function addCourse($data)
    {
        /*
           * 添加课程 course
           * 开始时间 course_set
           * 所属老师 teacher_course
           * 查找老师 admin_user
           * */

        $checkKid   =   Kinds::find()->where(['id'=>$data['kid']])->asArray()->one();
        if(!$checkKid)
            throw new \Exception('不存在该分类',1);
        $transaction = Yii::$app->db->beginTransaction();
        $course     =   new Course();
        $course->name   =   $data['name'];
        $course->content   =   $data['content'];
        $course->teacher_id   =   $data['teacher_id'];
        $course->cover   =   $data['cover'];
        $course->start   =   $data['start'];
        $course->price   =  $data['price'];
        $course->kid   =  $data['kid'];
        $course->status  =   0;
        if($course->save() == false)
        {
            $transaction->rollBack();
            throw new \Exception('写入课程表失败',1);
        }

        $courseSet   =   new CourseSet();
        $courseSet->course_id   =   $course->id;
        $courseSet->stime   =   $data['stime'];
        $courseSet->etime   =   $data['etime'];
        $courseSet->remark  =   $data['remark'];
        $courseSet->address  =   $data['address'];
        $courseSet->classes  =   $data['classes'];
        $courseSet->status  =   0;
        if($courseSet->insert() == false)
        {
            $transaction->rollBack();
            throw new \Exception('写入课程关联表失败',1);
        }

        $teacher    =   new TeacherCourse();
        $teacher->course_id     =   $course->id;
        $teacher->teacher_id    =   $data['teacher_id'];
        $teacher->kid   =   $data['kid'];
        $teacher->status    =   0;
        if($teacher->insert() == false)
        {
            $transaction->rollBack();
            throw new \Exception('写入教师关联表失败',1);
        }
        $transaction->commit();
        return true;
    }

    public function editCourse($data)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $course     =   Course::findOne($data['course_id']);
        if(!$course)
            throw new \Exception('不存在该课程',1);
        $course->name   =   $data['name'];
        $course->content   =   $data['content'];
        $course->teacher_id   =   $data['teacher_id'];
        $course->cover   =   $data['cover'];
        $course->start   =   $data['start'];
        $course->price   =  $data['price'];
        $course->status  =   0;
        if($course->save() == false)
        {
            $transaction->rollBack();
            throw new \Exception('写入课程表失败',1);
        }

        $courseSet   =   CourseSet::findOne(['course_id'=>$data['course_id']]);
        if(!$courseSet)
            throw new \Exception('不存在该设置',1);
        $courseSet->course_id   =   $data['course_id'];
        $courseSet->stime   =   $data['stime'];
        $courseSet->etime   =   $data['etime'];
        $courseSet->remark  =   $data['remark'];
        $courseSet->address  =   $data['address'];
        $courseSet->classes  =   $data['classes'];
        $courseSet->status  =   0;
        if($courseSet->save() == false)
        {
            $transaction->rollBack();
            throw new \Exception('写入课程关联表失败',1);
        }

        $teacher    =   TeacherCourse::findOne(['course_id'=>$data['course_id']]);
        if(!$teacher)
            throw new \Exception('不存在教师设置',1);
        $teacher->course_id     =  $data['course_id'];
        $teacher->teacher_id    =   $data['teacher_id'];
        $teacher->kid   =   $data['kid'];
        $teacher->status    =   0;
        if($teacher->save() == false)
        {
            $transaction->rollBack();
            throw new \Exception('写入教师关联表失败',1);
        }
        $transaction->commit();
        return true;
    }

    public function courseList($kid,$page)
    {
        /*
         * 分类下的课程列表
         * */
        if(empty($page))
            $page     =   1;

        $size = 8;//一次读取20条信息
        $skip = (intval($page)-1)*$size;

        $course   =   Course::find()
            ->where(['status'=>0,'kid'=>$kid])
            ->limit($size)
            ->offset($skip)
            ->orderBy('id desc')
            ->asArray()
            ->all();

        $result     =   [];
        foreach ($course as $k =>$val)
        {
            $result[$k]['cover']      =   $val['cover'];
            $result[$k]['courseName']   =   $val['name'];
            $result[$k]['course_id']   =   $val['id'];
            $result[$k]['start']    =   $val['start'];
            $result[$k]['price']    =   $val['price']/100;
            $result[$k]['status']    =   $val['status'];
            $teacher    =   AdminUser::findOne($val['teacher_id']);
            if (!$teacher)
                throw new \Exception("寻找不到课程计划");
            $result[$k]['teacherName']  =   $teacher->name;
        }
        return $result;
    }

    public function cList($page)
    {
        /*
         * 所有课程
         * */
        if(empty($page))
            $page     =   1;

        $size = 8;//一次读取20条信息
        $skip = (intval($page)-1)*$size;

        $course   =   Course::find()
            ->where(['status'=>0])
            ->limit($size)
            ->offset($skip)
            ->orderBy('id desc')
            ->asArray()
            ->all();

        $result     =   [];
        foreach ($course as $k =>$val)
        {
            $result[$k]['cover']      =   $val['cover'];
            $result[$k]['courseName']   =   $val['name'];
            $result[$k]['course_id']   =   $val['id'];
            $result[$k]['start']    =   $val['start'];
            $result[$k]['price']    =   $val['price']/100;
            $result[$k]['status']    =   $val['status'];
            $teacher    =   AdminUser::findOne($val['teacher_id']);
//            if (!$teacher)
//                throw new \Exception("寻找不到课程计划",1);
            $result[$k]['teacherName']  =   $teacher->name;
        }
        return $result;
    }

    public function courseDetail($course_id)
    {
        /*
         * 课程详情
         * */
        $course = Course::findOne($course_id);
        if (!$course)
            throw new \Exception("寻找不到此课程");
        $course = $course->toArray();
        $course['price']    =   $course['price']/100;



        $course_set = CourseSet::findOne(['course_id'=>$course_id]);
        if (!$course_set)
            throw new \Exception("寻找不到课程计划");
        $course_set = $course_set->toArray();
        $course_set['stime']    =   date('Y-m-d H:i:s',$course_set['stime']);
        $course_set['etime']    =   date('Y-m-d H:i:s',$course_set['etime']);
        $teacher    =   TeacherCourse::findOne(['course_id'=>$course_id]);
        if (!$teacher)
            throw new \Exception("寻找不到关联老师");
        $teacher = $teacher->toArray();
        $teacherName    =   AdminUser::findOne($teacher['teacher_id']);
        if (!$teacherName)
            throw new \Exception("寻找不到关联老师名字");
        $teacher['teacherName']     =   $teacherName->name;
        $result['course'] = $course;
        $result['course_set'] = $course_set;
        $result['teacher']  =   $teacher;

        return $result;
    }

    public function mark($uid)
    {
        /*
         * 设计理念
         * 读取 score_set中的sigin_in_score进行递增
         * 先检测表中当前时间的yesterday是否存在，是否已经打卡，如果已经打卡
         * 例如：sigin_in_score == 2，首次签到1个积分，第二天2+2，第三天3+2，第四天4+2，第五天5+2如此类推
         * 利用事物同步更新到 user_logs user_score中
         * */

        $scoreSet   =   ScoreSet::findOne(1);
        $defaultScore    =  1;//默认第一次签到为1积分
        $userMark   =   ScoreMark::find()->where(['uid'=>$uid])->orderBy('id')->asArray()->one();
        if(!$userMark)
        {
            $mark   =   new ScoreMark();
            $mark->uid  =   $uid;
            $mark->last_sign_time   =   time();
            $mark->total_day    =   1;
            if($userMark->save()==false)
                throw new \Exception('签到失败',1);
        }






    }


}