<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/11
 */

namespace app\admin\model;
use traits\ModelTrait;
use basic\ModelBasic;
use think\Db;

/**
 * 用户管理 model
 * Class StoreProduct
 * @package app\admin\model\store
 */
class User extends ModelBasic
{
    use ModelTrait;
    /*
     * 异步获取分类列表
     * @param $where
     * @return array
     */
    public static function getList($where){
        $data=($data=self::systemPage($where,true)->page((int)$where['page'],(int)$where['limit'])->order($where['order'])->select()) && count($data) ? $data->toArray() :[];
        $count=self::systemPage($where,true)->count();
        $code=0;

        //会员套餐
        return compact('count','data','code');

    }
        /**
     * @param $where
     * @return array
     */
    public static function systemPage($where,$isAjax=false){
        $model = new self;
        if($where['is_show']!='')$model = $model->where('is_show',$where['is_show']);
        if($where['phone']!='')$model = $model->where('phone',$where['phone']);
        if($where['vip_id']!='')$model = $model->where('vip_id',$where['vip_id'])->where('vip_expire_time','>',time());
        if($where['status_post']!='')$model = $model->where('status_post',$where['status_post']);
        if($where['status']!='')$model = $model->where('status',$where['status']);
        if($isAjax===true){
            return $model;
        }
    }
}