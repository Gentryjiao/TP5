<?php

namespace app\admin\controller\setting;

use app\admin\controller\AuthController;
use service\FormBuilder as Form;
use service\JsonService;
use service\UtilService as Util;
use service\JsonService as Json;
use think\Request;
use app\admin\model\system\SystemRole;
use think\Url;
use app\admin\model\system\SystemAdmin as AdminModel;
use app\admin\model\system\Admin_fujiaxinxi as Admin_fujiaxinxiModel;

/**
 * 管理员列表控制器
 * Class SystemAdmin
 * @package app\admin\controller\system
 */
class Config extends AuthController
{

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $config=Db('config')->find();
        $this->assign('config',$config);
        return $this->fetch();
    }

    public function update(Request $re){
        $data=$re->post();
        // return json([$data]);
        $res=Db('config')->where('id',$data['id'])->update($data);
        if($res){
            return json(['msg'=>'保存成功']);
        }else{
            return json(['msg'=>'保存失败']);
        }
    }
}
