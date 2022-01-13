<?php

namespace app\api\controller;
use think\Db;
class Poster extends Base
{
    /**
     * 创建分享图片
     */
    public function createShareImg(){
        $bg1='./poster/poster1.png';
        $huiyuan='./poster/huiyuan.png'; //会员
        $daili='./poster/daili.png'; //代理
        $hong='./poster/hong.png'; //红箭头
        $lv='./poster/lv.png'; //绿箭头

        $userInfo = $this->userInfo;
        $header = $userInfo->avatar;//头像
        $info = Db::name('user_report')->where('user_id',$userInfo->id)->find();
        $userReportInfos = Db::name('visitors_record')->where('user_id',$userInfo->id)->order('id desc')->select();
        if(empty($userReportInfos)) returndata(1,'无最新上称记录!');
        $userReportInfo=$userReportInfos[0];

        if(!empty($duibishijian[1])){
            $duibishijian=date('m-d h:i',$duibishijian[1]['create_time']); //对比时间
            $tizhongbh=$userReportInfo['weight']-$userReportInfos[1]['weight']; //体重变化
            $tizhibh=$userReportInfo['fat']-$userReportInfos[1]['fat']; //体脂变化
        }else{
            $duibishijian='无上称时间';
            $tizhongbh=00.0;
            $tizhibh=00.0;
        }

        // 获取背景图尺寸
        list($bg_w,$bg_h) = getimagesize($bg1);
        // 创建画图
        $img = @imagecreatetruecolor($bg_w,$bg_h);
        // 填充画布背景颜色
        $img_bg_color = imagecolorallocate($img,255,255,255);
        imagefill($img,0,0,$img_bg_color);
        // 将背景图填充到画布
        $bg_img = $this->getImgReource($bg1);
        imagecopyresized($img,$bg_img,0,0,0,0,$bg_w,$bg_h,$bg_w,$bg_h);

        //填空体重箭头
        if (!empty($duibishijian[1]) && $tizhongbh!=0){
            $tizhongbh>0?$jt=$hong:$jt=$lv;
            $qrcode = $this->getImgReource($jt);
            imagecopyresized($img,$qrcode,430,313,0,0,20,20,30,30);
        }

        //填空体脂箭头
        if (!empty($duibishijian[1]) && $tizhibh!=0) {
            $tizhibh > 0 ? $jt = $hong : $jt = $lv;
            $qrcode = $this->getImgReource($jt);
            imagecopyresized($img, $qrcode, 540, 313, 0, 0, 20, 20, 30, 30);
        }

         //填空用户会员图标
        if($userInfo->identity_id>1){
            $userInfo->identity_id==2?$logo = $huiyuan:$logo = $daili;
            $qrcode = $this->getImgReource($logo);
            list($qr_w,$qr_h) = getimagesize($logo);
            imagecopyresized($img,$qrcode,164,137,0,0,$qr_w,$qr_h,$qr_w,$qr_h);
        }

        // 填充用户头像
        $headerpath='.'.parse_url($header)['path'];
        if(file_exists($headerpath)) {
            $qrcode = $this->getImgReource($headerpath);
            list($user_w, $user_h) = getimagesize($headerpath);
            imagecopyresized($img, $qrcode, 70, 100, 0, 0, 90, 90, $user_w, $user_h);
        }else{
            $filename='./file/images/headimg/';
            $file = crabImage($header,$filename,$userInfo->id);
            if(file_exists($file['save_path'])) {
                $qrcode = $this->getImgReource($file['save_path']);
                list($user_w, $user_h) = getimagesize($file['save_path']);
                imagecopyresized($img, $qrcode, 70, 100, 0, 0, 90, 90, $user_w, $user_h);
            }
        }

        $word=[
            [
                'name'=>'用户昵称',
                'title'=>$userInfo->nickname,
                'color'=>[255,255,255],
                'font'=>'./font/siyuansongti.ttf',
                'size'=>28,
                'position'=>[170,133]
            ],
            [
                'name'=>'年龄',
                'title'=>date('Y')-date('Y',strtotime($info['user_birthday'])).'岁',
                'color'=>[255,255,255],
                'font'=>'./font/siyuansongti.ttf',
                'size'=>20,
                'position'=>[268,178]
            ],
            [
                'name'=>'身高',
                'title'=>$info['user_height'].'cm',
                'color'=>[255,255,255],
                'font'=>'./font/siyuansongti.ttf',
                'size'=>20,
                'position'=>[352,178]
            ],
            [
                'name'=>'上称时间',
                'title'=>date('m-d h:i',$userReportInfo['create_time']).' 最新上称',
                'color'=>[105,227,227],
                'font'=>'./font/siyuansongti.ttf',
                'size'=>15,
                'position'=>[76,290]
            ],
            [
                'name'=>'对比时间',
                'title'=>'对比 '.$duibishijian,
                'color'=>[105,227,227],
                'font'=>'./font/siyuansongti.ttf',
                'size'=>15,
                'position'=>[387,290]
            ],
            [
                'name'=>'体重',
                'title'=>number_format($userReportInfo['weight'],1).'kg',
                'color'=>[51,51,51],
                'font'=>'./font/siyuansongti.ttf',
                'size'=>29,
                'position'=>[110,364]
            ],
            [
                'name'=>'体重变化',
                'title'=>number_format(abs($tizhongbh),1).'kg',
                'color'=>[51,51,51],
                'font'=>'./font/siyuansongti.ttf',
                'size'=>18,
                'position'=>[380,380]
            ],
            [
                'name'=>'体脂变化',
                'title'=>number_format(abs($tizhibh),1).'kg',
                'color'=>[51,51,51],
                'font'=>'./font/siyuansongti.ttf',
                'size'=>18,
                'position'=>[480,380]
            ],
            [
                'name'=>'BMI',
                'title'=>number_format($userReportInfo['bmi'],2),
                'color'=>[255,255,255],
                'font'=>'./font/fangsongcuti.ttf',
                'size'=>34,
                'position'=>[64,530]
            ],
            [
                'name'=>'体脂率',
                'title'=>number_format($userReportInfo['fat'],1).'%',
                'color'=>[255,255,255],
                'font'=>'./font/fangsongcuti.ttf',
                'size'=>34,
                'position'=>[258,530]
            ],
            [
                'name'=>'体脂等级',
                'title'=>number_format($userReportInfo['fat_level'],1).'%',
                'color'=>[255,255,255],
                'font'=>'./font/fangsongcuti.ttf',
                'size'=>34,
                'position'=>[444,530]
            ],
            [
                'name'=>'肌肉量',
                'title'=>number_format($userReportInfo['muscle_mass'],1).'%',
                'color'=>[255,255,255],
                'font'=>'./font/fangsongcuti.ttf',
                'size'=>34,
                'position'=>[64,682]
            ],
            [
                'name'=>'骨量',
                'title'=>number_format($userReportInfo['bone_mass'],1).'kg',
                'color'=>[255,255,255],
                'font'=>'./font/fangsongcuti.ttf',
                'size'=>34,
                'position'=>[268,682]
            ],
        ];
        //填写文字
        foreach($word as $k=>$v){
            $size = $v['title'];
            $font_color = ImageColorAllocate($img,$v['color'][0],$v['color'][1],$v['color'][2]); //字体颜色
            $font_ttf =  realpath($v['font']);
            imagettftext($img,$v['size'],0,$v['position'][0],$v['position'][1],$font_color,$font_ttf,$size);
        }

        $filename='file/images/'.date('Y-m-d').'/';
        if(!is_dir($filename)){
            mkdir($filename,0777,true);
        }
        $name=time().rand(1111,9999).'.png';
        $file=$filename.$name;
        $res = imagejpeg($img,$file,90);
        imagedestroy($img);
        if($res){
            returndata(0,'生成成功',splicingDomain($file));
        }else{
            returndata(1,'生成失败');
        }
    }
    /**
     * 获取图像文件资源
     * @param string $file
     * @return resource
     */
    protected function getImgReource($file){
        $file_ext = pathinfo($file,PATHINFO_EXTENSION);
        switch ($file_ext){
            case 'jpg':
            case 'jpeg':
                $img_reources = @imagecreatefromjpeg($file);
                break;
            case 'png':
                $img_reources = @imagecreatefrompng($file);
                break;
            case 'gif':
                $img_reources = @imagecreatefromgif($file);
                break;
        }
        return  $img_reources;
    }
    /**
     * 缩放图片
     * @param string $img
     * @param string $file
     * @param number $th_w
     * @param number $th_h
     * @return boolean|string;
     */
    protected function thumbImg($img,$file='./file/images/headimg',$th_w=82,$th_h=82){
        //给图像加大1像素的边框
        $new_th_h = $th_h + 4;
        $new_th_w = $th_w + 4;
        // 获取大图资源及图像大小
        list($max_w,$max_h) = getimagesize($img);
        if($max_w < $th_w || $max_h < $th_h) return false;
        $max_img = $this->getImgReource($img);
        //新建真色彩画布

        $min_img = @imagecreatetruecolor($new_th_w,$new_th_h);
        $bg_color = ImageColorAllocate($min_img,255,255,255);
        imagefill($min_img,0,0,$bg_color);
//         imagesavealpha($min_img,true);
        imagecolortransparent($min_img,$bg_color);
        imagecopyresampled($min_img,$max_img,2,2,0,0,$th_w,$th_h,$max_w,$max_h);
        //输出图像到文件
        $min_img_path = $file . 'thunm_'.time().'.png';
        imagepng($min_img,$min_img_path);
        if(!is_file($min_img_path)){
            return false;
        }
        //释放空间
        imagedestroy($max_img);
        imagedestroy($min_img);
        return $min_img_path;
    }

}