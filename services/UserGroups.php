<?php
namespace app\services;

use yii;
class UserGroups
{

    public function getUserGroups($uid)
    {

        $user_groups = (new yii\db\Query())
                    ->select(["m.uid","m.vip_id","g.vip_id","g.type","g.status","g.rules"])
                    ->from(['m' => 'activity_vip'])
                    ->leftJoin(['g' => 'activity_vip_set'], 'm.vip_id = g.vip_id')
                    ->where(['m.uid'=>$uid])
                    ->andWhere('g.status = 0')
                    ->all();
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