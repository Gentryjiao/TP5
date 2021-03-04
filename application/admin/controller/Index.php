<?php

namespace app\admin\controller;

use app\admin\model\store\StoreProduct;
use app\admin\model\system\SystemConfig;
use app\admin\model\system\SystemMenus;
use app\admin\model\system\SystemRole;
use app\admin\model\order\StoreOrder as StoreOrderModel;//订单
use app\admin\model\user\UserExtract as UserExtractModel;//分销
use app\admin\model\user\User as UserModel;//用户
use app\admin\model\store\StoreProductReply as StoreProductReplyModel;//评论
use app\admin\model\store\StoreProduct as ProductModel;//产品
use app\core\util\SystemConfigService;
use FormBuilder\Json;
use think\DB;

/**
 * 首页控制器
 * Class Index
 * @package app\admin\controller
 *
 */
class Index extends AuthController
{
    public function index()
    {
        // Db('system_menus')->query("INSERT INTO `skt`.`eb_system_menus`( `pid`, `icon`, `menu_name`, `module`, `controller`, `action`, `params`, `sort`, `is_show`, `access`) VALUES ( 481, '', '规格管理展示页', 'admin', 'store.storeProduct', 'index', '[]', 0, 1, 1)");
        // $a=Db('system_menus')->count();
        // echo $a;die;
        //获取当前登录后台的管理员信息
        $adminInfo = $this->adminInfo->toArray();
        $roles  = explode(',',$adminInfo['roles']);
        $site_logo = SystemConfig::getOneConfig('menu_name','site_logo');
        if(!empty($site_logo)){
            $site_logo=$site_logo->toArray();
        }
//        dump(SystemMenus::menuList());
//        exit();
        $this->assign([
            'menuList'=>SystemMenus::menuList(),
            'site_logo'=>json_decode($site_logo['value'],true),
            'new_order_audio_link'=>str_replace('\\','/',SystemConfigService::get('new_order_audio_link')),
            'role_name'=>SystemRole::where('id',$roles[0])->field('role_name')->find()
        ]);
        return $this->fetch();
    }
    //后台首页内容
    public function main()
    {
        // $this->redirect('/admin/store.product/index');
    }
}


