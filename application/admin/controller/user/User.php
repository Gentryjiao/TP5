<?php

namespace app\admin\controller\user;
use think\Db;
use think\Request;
use app\admin\controller\AuthController;
use service\UtilService as Util;
use app\admin\model\user\User as UserModel;
/**
 * TODO 附件控制器
 * Class Images
 * @package app\admin\controller\widget
 */
class User extends AuthController
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

    public function getlist(){
        $where = Util::getMore([
            ['is_show',$this->request->post('is_show','')],
            ['phone',$this->request->post('phone','')],
            ['page',$this->request->post('page',1)],
            ['limit',$this->request->post('limit',20)],
            ['order','id','desc'],
            ['name',$this->request->post('name','')],
            ['field','nickname,image,sex,phone,create_time,login_time,is_show,id,is_on,lv,user_jifen,group_jifen,code,pcode,zsname,baozhengjin,yve'],
        ]);
        $data=UserModel::getList($where);
        return $data;
    }

    public function jihuo(Request $re){
        $id=$re->post('id');
        $user=Db('user')->where('id',$id)->field('is_on,pcode')->find();
        if($user['is_on']==0){
            $pid=$this->creep($user['pcode']);
            if($pid==0){
                return json(['status'=>'error','msg'=>'父级尚未进入架构树']);
            }
            $res=Db('user')->where('id',$id)->update(['is_on'=>1,'baozhengjin'=>300,'pid'=>$pid,'lv'=>1]);
            if($res){
                Db('user_details')->insert(['date'=>date('Y-m-d'),'use'=>'用户激活','money'=>'+300','user_id'=>$id]);
                return json(['status'=>'success','msg'=>'激活成功']);
            }else{
                return json(['status'=>'error','msg'=>'激活失败']);
            }
        }
    }

    //寻找子级最少的用户 进行分配
    public function creep($pcode){
        if(empty($pcode))return 0;
        $user=Db('user')->where('code',$pcode)->where(['is_on'=>'1'])->find();
        if(empty($user))return 0;
        $puser=Db('user')->where('pid','IN',$user['id'])->order('id')->select();
        if(count($puser)<3){
            return $user['id'];
        }
        $arr=[];
        foreach($puser as $k=>$v){
            $arr[]=$v['id'];
        }
        return $this->down($arr);
    }

    //递归
    public function down($id){
        foreach($id as $v){
            $count[$v]=Db('user')->where('pid',$v)->count();
        }
        foreach($count as $k=>$v){
            if($v!=3){
                if($v==0){
                    return $k;
                }
            }
        }
        foreach($count as $k=>$v){
            if($v!=3){
                if($v==1){
                    return $k;
                }
            }
        }
        foreach($count as $k=>$v){
            if($v!=3){
                if($v==2){
                    return $k;
                }
            }
        }
        $arr=[];
        $data=Db('user')->where('pid','IN',$id)->field('id')->order('id')->select();
        foreach($data as $k=>$v){
            $arr[]=$v['id'];
        }
        return $this->down($arr);
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
        $data = Util::getMore([
            ['value',$request->post('value','')],
            ['field',$request->post('field')],
        ]);
        if($data['value']=='') return json(['status'=>'error','msg'=>'值不能为空']);
        $edit=[
            $data['field']=>$data['value'],
        ];
        if($data['field']=='lv'){
            $is_on=UserModel::get($id)['is_on'];
            if($is_on=='1'){
                $lv=Db('user_lv')->where('level',$data['value'])->field('user_min,group_min')->find();
                if($lv){
                    $edit['user_jifen']=$lv['user_min'];
                    $edit['group_jifen']=$lv['group_min'];
                }else{
                    return json(['status'=>'error','msg'=>'没有对应的等级']);
                }
            }else{
                return json(['status'=>'error','msg'=>'请先激活该用户']);
            }
            
        }
        if(UserModel::edit($edit,$id)){
            return json(['status'=>'success','msg'=>'操作成功']);
        }else{
            return json(['status'=>'error','msg'=>'操作失败']);
        }
    }

    public function delete(Request $re){
        $id=$re->post('id');
        $user=Db('user')->where('id',$id)->where('is_on','1')->count();
        if($user==1){
            return json(['status'=>'error','msg'=>'已激活的用户不可删除!']);
        }
        $res=Db('user')->where('id',$id)->delete();
        if($res){
            return json(['status'=>'success','msg'=>'删除成功']);
        }else{
            return json(['status'=>'error','msg'=>'删除失败']);
        }
    }
}
