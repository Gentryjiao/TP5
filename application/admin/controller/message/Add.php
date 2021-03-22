<?php
namespace app\admin\controller\Message;
use think\Controller;
use think\Db;
class Add extends Controller
{
    public function index(){
        if($this->request->isPost()){
            $data=$this->request->param();
            return $data;
        }else{
            return $this->fetch();
        }
    }
}