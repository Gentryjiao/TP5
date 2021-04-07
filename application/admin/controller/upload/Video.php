<?php
namespace app\admin\controller\upload;
use think\Controller;
class Video extends Controller
{
    public function index(){
        if($this->request->isPost()){
            $data=$this->request->post();
            return $data;

        }else{
            return $this->fetch();
        }
    }
}