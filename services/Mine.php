<?php
/**
 * Created by PhpStorm.
 * User: kopa
 * Date: 2018/9/20
 * Time: 下午2:53
 * 个人中心 业务查询
 */

namespace app\services;
use app\models\admin\AdminUser;
use \app\models\Kinds;
use \app\models\KindsRoute;
use \app\models\Course;
use \app\models\CourseSet;
use app\models\MyCourse;
use app\models\score\ScoreLog;
use app\models\score\UserScore;
use \app\models\TeacherCourse;
use Yii;

class Mine
{

    function __construct()
    {
        $this->model = trim('app\models\ ');
    }

  public function myScore($uid)
  {
      /*
       * 我的积分总数
       * */
      $userScore    =   UserScore::find()->where(['uid'=>$uid])->asArray()->one();
      if(!$userScore)
          throw new \Exception('目前没有记录积分',1);
      return $userScore;
  }

  public function myScoreList($uid)
  {
      /*
       * 我的积分明细
       * */
      $list     =   ScoreLog::find()->where(['uid'=>$uid,'status'=>0])->asArray()->all();
      $result = [];
      foreach ($list as $key=>$value) {
          $result[$key]['uid']  =   $value['uid'];
          $result[$key]['score']    =   $value['score'];
          $result[$key]['type'] =   $value['type'] =='invite'   ?'邀请好友':'签到';
          $result[$key]['dates']    =   date('Y-m-d H:i:s',$value['dates']);
      }
      return $result;
  }

  public function courseSearch($uid)
  {
      /*
       * 课程查询
       * */
        $courseList     =   MyCourse::find()->where(['uid'=>$uid])->asArray()->all();
        $result     =   [];
        if(empty($courseList))
            throw new \Exception('还没有报名课程哦',1);
        foreach($courseList as $key=>$value)
        {
            //查找对应课程的名称列表
            $course = Course::find()->where(['id'=>$value['course_id']])->asArray()->one();
            if(empty($course))
                throw new \Exception('查找课程出错了',1);
            $result[$key]['course_id']  =   $value['course_id'];
            $result[$key]['name']   =   $course['name'];
            $result[$key]['cover']  =   $course['cover'];
            $result[$key]['content']  =   $course['content'];
        }

        return $result;

  }

  public function courseDetail($course_id)
  {
      /*
       * 课程详情
       * */
      $course = Course::find()->where($course_id)->one();
      if (!$course)
          throw new \Exception("寻找不到此课程");
      $course = $course->toArray();
      $course['price']    =   $course['price']/100;


      $course_set = CourseSet::find()->where(['course_id'=>$course_id])->one();
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

}