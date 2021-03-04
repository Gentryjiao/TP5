<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/11
 */

namespace app\admin\model;
use traits\ModelTrait;
use basic\ModelBasic;

/**
 * 产品管理 model
 * Class StoreProduct
 * @package app\admin\model\store
 */
class Image extends ModelBasic
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
        return compact('count','data','code');
    }
        /**
     * @param $where
     * @return array
     */
    public static function systemPage($where,$isAjax=false){
        $model = new self;
        if($where['type']!='')$model = $model->where('type',$where['type']);
        if($isAjax===true){
            return $model;
        }
    }
}