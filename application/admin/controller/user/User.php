<?php
namespace app\admin\controller\user;
use think\Db;
use think\Request;
use app\admin\controller\AuthController;
use app\admin\model\User as userModel;
class User extends AuthController
{
    public function index(){
        return $this->fetch();
    }
    public function getlist(){
        $page=Request()->param('page','1');
        $limit=Request()->param('limit','15');
        $vip_id=Request()->param('vip_id','');
        $where=[
            'page'=>$page,
            'limit'=>$limit,
            'order'=>Request()->param('order','id'),
            'is_show'=>Request()->param('is_show',''),
            'phone'=>Request()->param('phone',''),
            'status_post'=>Request()->param('status_post',''),
            'status'=>Request()->param('status',''),
            'vip_id'=>$vip_id,
            'field'=>'*'
        ];
        $data=userModel::getList($where);
        return $data;
    }

    //审核身份
    public function shenhe(){
        $id=Request()->post('id');
        $user=Db::name('user')->find($id);
        $card_image=$user['card_image'];
        $card_image_arr=explode('|',$card_image);
        $this->assign([
            'user'=>$user,
            'card_image'=>$card_image_arr
        ]);
        return view();
    }
    //审核身份通过
    public function shenhe_succ(){
        $id=Request()->post('id');
        $user=Db::name('user')->where('id',$id)->update(['real_status'=>1]);
        $data=[
            'sort_title'=>'实名认证消息',
            'title'=>'认证通过',
            'text'=>'您的实名认证已经通过!',
            'create_time'=>time(),
            'uid'=>$id
        ];
        Db::name('information')->insert($data);

        if($user) return json(['status'=>'success','msg'=>'实名成功']);
    }
    //审核身份拒绝
    public function shenhe_err(){
        $id=Request()->post('id');
        $user=Db::name('user')->where('id',$id)->update(['real_status'=>3]);
        $data=[
            'sort_title'=>'实名认证消息',
            'title'=>'认证失败',
            'text'=>'您的实名认证未通过，请重新认证！',
            'create_time'=>time(),
            'uid'=>$id
        ];
        Db::name('information')->insert($data);
        if($user) return json(['status'=>'success','msg'=>'已拒绝']);
    }


    //删除用户
    public function delete(Request $re){
        $id=$re->post('id');
        $res=Db('user')->where('id',$id)->delete();
        if($res){
            return json(['status'=>'success','msg'=>'删除成功']);
        }else{
            return json(['status'=>'error','msg'=>'删除失败']);
        }
    }

    //显示隐藏
    public function isshow(){
        $post=$this->request->post();
        $res=usermodel::update($post);
        if($res){
            if($post['is_show']=='false'){
                $data=[
                    'sort_title'=>'账号冻结',
                    'title'=>'账号被冻结',
                    'text'=>'因违反平台有关规定，您的账号现已被限制发布，请联系管理员！',
                    'create_time'=>time(),
                    'uid'=>$post['id']
                ];
                Db::name('information')->insert($data);
            }
            if($post['is_show']=='true'){
                $data=[
                    'sort_title'=>'账号解冻',
                    'title'=>'账号已解冻',
                    'text'=>'账号已解冻，可正常进行发布！',
                    'create_time'=>time(),
                    'uid'=>$post['id']
                ];
                Db::name('information')->insert($data);
            }
            return json(['status'=>'success','msg'=>'操作成功']);
        }else{
            return json(['status'=>'success','msg'=>'操作失败']);
        }
    }

    //详情
    public function details(Request $re){
        $id=$re->post('id');
        $user=Db::name('user')->find($id);
        $this->assign('data',$user);
        return view();
    }

    //企业
    public function qiye(Request $re){
        $id=$re->param('id');
        $data=Db::name('enterprise')->where(['uid'=>$id,'is_on'=>0])->find();
        $this->assign('data',$data);
        return view();
    }
    //企业
    public function qiye_shenge(Request $re){
        $data=$re->param();
        $ent=Db::name('enterprise')->where(['uid'=>$data['id'],'is_on'=>0])->find();
        $res=Db::name('enterprise')->where('uid',$data['id'])->update(['is_on'=>$data['is_on']]);
        if($data['is_on']==1){
            if($ent['status']==1) Db::name('user')->where('id',$data['id'])->update(['is_qi'=>1]);
            if($ent['status']==2) Db::name('user')->where('id',$data['id'])->update(['is_qi'=>2]);
        }
        if($res) return json(['status'=>'success','msg'=>'操作成功']);
    }



    //查看积分
    public function integral(){
        $user_id=Request()->param('id');
        $data=Db::name('integral')->where('uid',$user_id)->select();
        $this->assign('data',$data);
        return view();
    }

}
