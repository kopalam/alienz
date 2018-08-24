<?php
/**
 * Created by PhpStorm.
 * User: kopa
 * Date: 2018/8/9
 * Time: 下午2:53
 */

namespace app\services;

Class Upload{
    public $uploadName; //文件名
    public $uploadTmpName;//临时文件名
    public $uploadFinalName; //最终文件名
    public $uploadTargetDir='../web/uploads';//最终文件夹
    public $uploadTargetFile;//最终路径
    public $uploadFileType;//文件类型
    public $allowUploadType = ['image/png','image/jpeg','image/jpg','image/gif'];

    public $uploadFileSize;//上传文件大小
    public $allowUploadedMaxsize    =   100000000;//上传文件最大值

    public function __construct()
    {
        $this->uploadName   =   $_FILES['image']['name'];
        $this->uploadFileType   =   $_FILES['image']['type'];
        $this->uploadTmpName    =   $_FILES['image']['tmp_name'];
        $this->uploadFileSize   =   $_FILES['image']['size'];
    }

    public function getImage()
    {
        if(!$this->isAllowFile($this->uploadFileType))
            throw new \Exception('不允许上传该类型',10001);
        if(!$this->isAllowSize($this->uploadFileSize))
            throw new \Exception('文件超出限制',10001);

        $dirName    =   $this->createUploadDir();//创建文件夹
        $newFileName    =   uniqid().'.png';//新文件名称
        $this->uploadTargetFile = $dirName.'/'.$newFileName;
        if(!move_uploaded_file($this->uploadTmpName,$this->uploadTargetFile))
            throw new \Exception('文件类型上传失败',10001);

        return [
            'path'=>$this->uploadTargetFile,
            'dirName'=>str_replace($this->uploadTargetDir,"",$dirName),
            'fileName'=>$newFileName
        ];
    }

    protected function isAllowFile($file_type)
    {
        // $info = pathinfo($file_name);
        return in_array($file_type, $this->allowUploadType) ? true : false;
        // return true;

    }

    protected function isAllowSize($size)
    {
        return $size < $this->allowUploadedMaxsize ? true : false;
    }

    protected function createUploadDir()
    {
        $dir_name = $this->uploadTargetDir . "/" . date("YmdH");
        if (!is_dir($dir_name)) {
            mkdir($dir_name, 0777, true);
            chmod($dir_name, 0777);
        }
        return $dir_name;
    }

}