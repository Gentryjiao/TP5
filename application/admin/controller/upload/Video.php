<?php
namespace app\admin\controller\upload;
use think\Controller;
class Video extends Controller
{
    public function index(){
        if($this->request->isPost()){

        }else{
            return $this->fetch();
        }
    }
}