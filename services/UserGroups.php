<?php
namespace app\services;

use yii;
class UserGroups
{

    public function getUserGroups($uid,$authMiddle,$authGroup)
    {

        $user_groups = (new yii\db\Query())
                    ->column("m.uid,m.user_set_id,g.user_set_id,g.type,g.status,g.rules")
                    ->from()
                    ->join($authGroup,'m.user_set_id = g.user_set_id','g')
                    ->where('m.uid = :uid',['uid'=>$uid])
                    ->addParams([':uid' => $uid])
                    ->andWhere('g.status = 1')
                    ->asArray()->all();
//        $user_groups = $this->modelsManager->createBuilder()
//            ->columns("m.uid,m.vip_id,g.vip_id,g.type,g.status,g.rules")
//            ->addfrom($authMiddle,'m')
//            ->join($authGroup, 'm.vip_id = g.vip_id','g')
//            ->where("m.uid = :uid:",["uid" => $uid])
//            ->andwhere('g.status = 0')
//            ->getQuery()
//            ->execute()
//            ->toArray();

        return $user_groups;
    }
}