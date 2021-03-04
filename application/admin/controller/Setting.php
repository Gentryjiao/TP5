<?php

namespace app\admin\controller;
use think\Db;
use think\Request;
use app\admin\controller\AuthController;
use app\admin\model\Banner as BannerModel;
use service\UtilService as Util;

/**
 * TODO 附件控制器
 * Class Images
 * @package app\admin\controller\widget
 */
class Setting extends AuthController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        return view();
    }
    public function getlist(){
        $where = Util::getMore([
            ['is_show',$this->request->param('is_show','')],
            ['page',1],
            ['limit',20],
            ['order','sort']
        ]);
        $data=BannerModel::getList($where);
        return $data;
    }
    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $data=$request->post();
        // return $data;
        //验证  唯一规则： 表名，字段名，排除主键值，主键名
        $validate = new \think\Validate([
            ['title', 'require', '标题不能为空'],
            ['image', 'require', '请选择图片'],
        ]);
        //验证部分数据合法性
        if (!$validate->check($data)) {
            $this->error('提交失败：' . $validate->getError());
        }
        $res=BannerModel::set($data);
        return json(['code'=>200,'msg'=>'添加成功']);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $this->assign('data',BannerModel::get($id));
        return view('create');
    }
    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update1(Request $request, $id)
    {
        $data=$request->post();
        //验证  唯一规则： 表名，字段名，排除主键值，主键名
        $validate = new \think\Validate([
            ['title', 'require', '标题不能为空'],
            ['image', 'require', '请选择图片'],
        ]);
        //验证部分数据合法性
        if (!$validate->check($data)) {
            $this->error('提交失败：' . $validate->getError());
        }
        BannerModel::edit($data,$id);
        return json(['code'=>200,'msg'=>'修改成功']);
    }


    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        $data=$request->post();
        if(empty($data['value'])) return json(['code'=>400,'msg'=>'值不能为空']);
        if(Db('banner')->where('id',$id)->update([$data['field']=>$data['value']])){
            $msg=['code'=>200,'msg'=>'修改成功'];
        }else{
            $find=Db('work_type')->where('id',$id)->find();
            $msg=['code'=>400,'msg'=>'修改失败'];
        }
        return json($msg);
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        if(!$id) return json(['code'=>400,'msg'=>'数据不存在']);
        if(!BannerModel::be(['id'=>$id])) return json(['code'=>400,'msg'=>'产品数据不存在']);
        if(!BannerModel::delstoreProduct($id)){
            return json(['code'=>400,'msg'=>'删除失败']);
        }else{
            return json(['code'=>200,'msg'=>'删除成功']);
        }
    }
}
