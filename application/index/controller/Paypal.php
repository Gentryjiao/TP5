<?php


namespace app\api\controller;
use think\Controller;

class Paypal extends Controller
{
    public function index(){
        return $this->fetch();
    }

}