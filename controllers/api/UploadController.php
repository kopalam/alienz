<?php

namespace app\controllers\api;

use Yii;
use yii\web\Controller;
use app\services\Upload;
use app\services\Utils;


class UploadController extends Controller
{
   /*
    * 图片上传
    * */
    public function actionUploadimg()
    {
        try{
            if(empty($_FILES))
                exit('出错了');

            $upload = new Upload();
            $info   =   $upload->getImage();
            $imgUrl     =   '/uploads'.$info['dirName'].'/'.$info['fileName'];
            Utils::apiDisplay(['status'=>0,'data'=>$imgUrl]);
        }catch(Exception $e){
            $data['status']  = 1;
            $data['message'] = $e->getMessage();
            Utils::apiDisplay( $data );
        }
    }


}
