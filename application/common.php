<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 敏感词过滤
 *
 * @param  string
 * @return string
 */
use Firebase\JWT\JWT;
function sensitive_words_filter($str)
{
    if (!$str) return '';
    $file = ROOT_PATH. PUBILC_PATH.'/static/plug/censorwords/CensorWords';
    $words = file($file);
    foreach($words as $word)
    {
        $word = str_replace(array("\r\n","\r","\n","/","<",">","="," "), '', $word);
        if (!$word) continue;

        $ret = preg_match("/$word/", $str, $match);
        if ($ret) {
            return $match[0];
        }
    }
    return '';
}

// $url 是请求的链接
// $postdata 是传输的数据，数组格式
function curl_post( $url, $postdata ) {
    $header = array(
        'Accept: application/json',
        'Content-Type: application/json',
        'X-Postmark-Server-Token:504a38b4-ee03-41f6-b021-703bccda5b53'
    );

    //初始化
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 0);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    // 超时设置
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);

    // 超时设置，以毫秒为单位
    // curl_setopt($curl, CURLOPT_TIMEOUT_MS, 500);

    // 设置请求头
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE );
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE );

    $postdata=json_encode($postdata);
    //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
    //执行命令
    $data = curl_exec($curl);

    // 显示错误信息
    if (curl_error($curl)) {
        print "Error: " . curl_error($curl);
    } else {
        // 打印返回的内容
        var_dump($data);
        curl_close($curl);
    }
}

/**
 * @param string $text 翻译原文
 * @param string $source 源语言
 * @param string $target 目标语言
 * @return string
 */
function api($text = '', $source = 'zh-CN', $target = 'en')
{
    require_once ROOT_PATH . 'vendor/googleTranslate/GoogleTranslate.php';
    $trans = new \GoogleTranslate();
    if (!$text) {
        $text = 'GoogleTranslate';
    }
    $result = $trans->translate($source, $target, $text, $type = 'cn');
    return $result;
}

/*
 * 判断手机或者pc
 * return true/phone false/pc
 * */
function isMobile()
{
    $useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $useragent_commentsblock = preg_match('|\(.*?\)|', $useragent, $matches) > 0 ? $matches[0] : '';
    function CheckSubstrs($substrs, $text)
    {
        foreach ($substrs as $substr)
            if (false !== strpos($text, $substr)) {
                return true;
            }
        return false;
    }

    $mobile_os_list = array('Google Wireless Transcoder', 'Windows CE', 'WindowsCE', 'Symbian', 'Android', 'armv6l', 'armv5', 'Mobile', 'CentOS', 'mowser', 'AvantGo', 'Opera Mobi', 'J2ME/MIDP', 'Smartphone', 'Go.Web', 'Palm', 'iPAQ');
    $mobile_token_list = array('Profile/MIDP', 'Configuration/CLDC-', '160×160', '176×220', '240×240', '240×320', '320×240', 'UP.Browser', 'UP.Link', 'SymbianOS', 'PalmOS', 'PocketPC', 'SonyEricsson', 'Nokia', 'BlackBerry', 'Vodafone', 'BenQ', 'Novarra-Vision', 'Iris', 'NetFront', 'HTC_', 'Xda_', 'SAMSUNG-SGH', 'Wapaka', 'DoCoMo', 'iPhone', 'iPod');

    $found_mobile = CheckSubstrs($mobile_os_list, $useragent_commentsblock) ||
        CheckSubstrs($mobile_token_list, $useragent);

    if ($found_mobile) {
        return true;
    } else {
        return false;
    }
}

    /**
 * 上传路径转化,默认路径 UPLOAD_PATH
 * $type 类型
 */
function makePathToUrl($path,$type = 2)
{
    $path =  DS.ltrim(rtrim($path));
    switch ($type){
        case 1:
            $path .= DS.date('Y');
            break;
        case 2:
            $path .=  DS.date('Y').DS.date('m');
            break;
        case 3:
            $path .=  DS.date('Y').DS.date('m').DS.date('d');
            break;
    }
    if (is_dir(ROOT_PATH.UPLOAD_PATH.$path) == true || mkdir(ROOT_PATH.UPLOAD_PATH.$path, 0777, true) == true) {
        return trim(str_replace(DS, '/',UPLOAD_PATH.$path),'.');
    }else return '';

}

// 过滤掉emoji表情
function filterEmoji($str)
{
    $str = preg_replace_callback(    //执行一个正则表达式搜索并且使用一个回调进行替换
        '/./u',
        function (array $match) {
            return strlen($match[0]) >= 4 ? '' : $match[0];
        },
        $str);
    return $str;
}

//可逆加密
 function encrypt($data, $key) {
     $prep_code = serialize($data);
     $block = mcrypt_get_block_size('des', 'ecb');
     if (($pad = $block - (strlen($prep_code) % $block)) < $block) {
         $prep_code .= str_repeat(chr($pad), $pad);
     }
     $encrypt = mcrypt_encrypt(MCRYPT_DES, $key, $prep_code, MCRYPT_MODE_ECB);
     return base64_encode($encrypt);
 }

//可逆解密
 function decrypt($str, $key) {
     $str = base64_decode($str);
     $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
     $block = mcrypt_get_block_size('des', 'ecb');
     $pad = ord($str[($len = strlen($str)) - 1]);
     if ($pad && $pad < $block && preg_match('/' . chr($pad) . '{' . $pad . '}$/', $str)) {
         $str = substr($str, 0, strlen($str) - $pad);
     }
     return unserialize($str);
 }

//替换一部分字符
/**
 * @param $string 需要替换的字符串
 * @param $start 开始的保留几位
 * @param $end 最后保留几位
 * @return string
 */
function strReplace($string,$start,$end)
{
    $strlen = mb_strlen($string, 'UTF-8');//获取字符串长度
    $firstStr = mb_substr($string, 0, $start,'UTF-8');//获取第一位
    $lastStr = mb_substr($string, -1, $end, 'UTF-8');//获取最后一位
    return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($string, 'utf-8') -1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;

}


/**
 * 发送HTTP请求方法
 * @param  string $url    请求URL
 * @param  array  $params 请求参数
 * @param  string $method 请求方法GET/POST
 * @return array  $data   响应数据
 */
function httpCurl($url, $params, $method = 'POST', $header = array(), $multi = false){
    date_default_timezone_set('PRC');
    $opts = array(
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER     => $header,
        CURLOPT_COOKIESESSION  => true,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIE         =>session_name().'='.session_id(),
    );
    /* 根据请求类型设置特定参数 */
    switch(strtoupper($method)){
        case 'GET':
            // $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
            // 链接后拼接参数  &  非？
            $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
            break;
        case 'POST':
            //判断是否传输文件
            $params = $multi ? $params : http_build_query($params);
            $opts[CURLOPT_URL] = $url;
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = $params;
            break;
        default:
            throw new Exception('不支持的请求方式！');
    }
    /* 初始化并执行curl请求 */
    $ch = curl_init();
    curl_setopt_array($ch, $opts);
    $data  = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    if($error) throw new Exception('请求发生错误：' . $error);
    return  $data;
}
/**
 * 微信信息解密
 * @param  string  $appid  小程序id
 * @param  string  $sessionKey 小程序密钥
 * @param  string  $encryptedData 在小程序中获取的encryptedData
 * @param  string  $iv 在小程序中获取的iv
 * @return array 解密后的数组
 */
function decryptData( $appid , $sessionKey, $encryptedData, $iv ){
    $OK = 0;
    $IllegalAesKey = -41001;
    $IllegalIv = -41002;
    $IllegalBuffer = -41003;
    $DecodeBase64Error = -41004;
 
    if (strlen($sessionKey) != 24) {
        return $IllegalAesKey;
    }
    $aesKey=base64_decode($sessionKey);
 
    if (strlen($iv) != 24) {
        return $IllegalIv;
    }
    $aesIV=base64_decode($iv);
 
    $aesCipher=base64_decode($encryptedData);
 
    $result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
    $dataObj=json_decode( $result );
    if( $dataObj  == NULL )
    {
        return $IllegalBuffer;
    }
    if( $dataObj->watermark->appid != $appid )
    {
        return $DecodeBase64Error;
    }
    $data = json_decode($result,true);
 
    return $data;
}
 

function define_str_replace($data)
{
    return str_replace(' ','+',$data);
}

// 添加域名
function addavatarUrl($data)
{
    if (substr($data, 0, 4) != 'http') {
        $data = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].$data;
    }
    return $data;
}

// 获取 随机浮点
function randomFloat($min = 0, $max = 1, $num = 2) {
    $number = $min + mt_rand() / mt_getrandmax() * ($max - $min);
    return round($number, $num);
}

/**
 * 替换手机号码中间四位数字
 * @param  [type] $str [description]
 * @return [type]      [description]
 */
function hide_phone($str){
    $resstr = substr_replace($str,'****',3,4);
    return $resstr;
}

/**
 * 生成二维码
 * @param  string $content 二维码内容
 * @return string          二维码保存路径
 */
function userimg($content){

    //引入phpqrcode类库文件
    vendor('phpqrcode.phpqrcode');
    $value = $content;         //二维码内容
    $errorCorrectionLevel = 'L';  //容错级别
    $matrixPointSize = 8;      //生成图片大小

    // 判断是否有这个文件夹  没有的话就创建一个
    if(!is_dir("qrcode")){
        // 创建文件加
        mkdir("qrcode");
    }

    // 设置二维码图片名称，以及存放的路径
    $filename = 'qrcode/'.time().rand(10000,9999999).'.png';

    // 使用类库生成二维码
    QRcode::png($value,$filename , $errorCorrectionLevel, $matrixPointSize, 2);

    // 　//如果需要转换成base64数据，解开下面这行注释即可
    // 　$image_data = chunk_split(base64_encode(fread(fopen($filename, 'r'), filesize($filename))));
    return $filename;
}

// 成功返回
function json_success($code,$msg,$arr=[]){
    return json_encode(['code'=>$code,'status_code'=>'success','msg'=>$msg,'datas'=>$arr],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
}
// 失败返回
function json_error($code,$msg,$arr=[]){
    return json_encode(['code'=>$code,'status_code'=>'error','msg'=>$msg,'datas'=>$arr],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
}

//生成token
function createToken($data = "", $exp_time = 0, $scopes = "")
{
    //JWT标准规定的声明，但不是必须填写的；
    //iss: jwt签发者
    //sub: jwt所面向的用户
    //aud: 接收jwt的一方
    //exp: jwt的过期时间，过期时间必须要大于签发时间
    //nbf: 定义在什么时间之前，某个时间点后才能访问
    //iat: jwt的签发时间
    //jti: jwt的唯一身份标识，主要用来作为一次性token。
    //公用信息
    try {
        $key = 'huang';
        $time = time(); //当前时间
        $token['iss'] = 'Jouzeyu'; //签发者 可选
        $token['aud'] = ''; //接收该JWT的一方，可选
        $token['iat'] = $time; //签发时间
        $token['nbf'] = $time+3; //(Not Before)：某个时间点后才能访问，比如设置time+30，表示当前时间30秒后才能使用
        if ($scopes) {
            $token['scopes'] = $scopes; //token标识，请求接口的token
        }
        if (!$exp_time) {
            $exp_time = 8400054325;//默认=2小时过期
        }
        $token['exp'] = $time + $exp_time; //token过期时间,这里设置2个小时
        if ($data) {
            $token['uid'] = $data; //自定义参数
        }

        $json = JWT::encode($token, $key);
        //Header("HTTP/1.1 201 Created");
        //return json_encode($json); //返回给客户端token信息
        return $json; //返回给客户端token信息

    } catch (\Firebase\JWT\ExpiredException $e) {  //签名不正确
        $returndata['code'] = "104";//101=签名不正确
        $returndata['msg'] = $e->getMessage();
        $returndata['data'] = "";//返回的数据
        return json_encode($returndata); //返回信息
    } catch (Exception $e) {  //其他错误
        $returndata['code'] = "199";//199=签名不正确
        $returndata['msg'] = $e->getMessage();
        $returndata['data'] = "";//返回的数据
        return json_encode($returndata); //返回信息
    }
}
//校验
function checkToken($jwt)
{
    $key = 'huang';
    try {
        JWT::$leeway = 60;//当前时间减去60，把时间留点余地
        $decoded = JWT::decode($jwt, $key, ['HS256']); //HS256方式，这里要和签发的时候对应
        $arr = (array)$decoded;

        $returndata['code'] = "200";//200=成功
        $returndata['msg'] = "成功";//
        $returndata['data'] = $arr;//返回的数据
        return json_encode($returndata,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); //返回信息

    } catch (\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
        //echo "2,";
        //echo $e->getMessage();
        $returndata['code'] = "101";//101=签名不正确
        $returndata['msg'] = $e->getMessage();
        $returndata['data'] = "";//返回的数据
        return json_encode($returndata); //返回信息
    } catch (\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
        //echo "3,";
        //echo $e->getMessage();
        $returndata['code'] = "102";//102=签名不正确
        $returndata['msg'] = $e->getMessage();
        $returndata['data'] = "";//返回的数据
        return json_encode($returndata); //返回信息
    } catch (\Firebase\JWT\ExpiredException $e) {  // token过期
        //echo "4,";
        //echo $e->getMessage();
        $returndata['code'] = "103";//103=签名不正确
        $returndata['msg'] = $e->getMessage();
        $returndata['data'] = "";//返回的数据
        return json_encode($returndata); //返回信息
    } catch (Exception $e) {  //其他错误
        //echo "5,";
        //echo $e->getMessage();
        $returndata['code'] = "199";//199=签名不正确
        $returndata['msg'] = $e->getMessage();
        $returndata['data'] = "";//返回的数据
        return json_encode($returndata); //返回信息
    }
    //Firebase定义了多个 throw new，我们可以捕获多个catch来定义问题，catch加入自己的业务，比如token过期可以用当前Token刷新一个新Token
}

// 解密token
function check($token){
    $jwt = $token;
    // $jwt = input("token");  //上一步中返回给用户的token
    $key = "huang";  //上一个方法中的 $key 本应该配置在 config文件中的
    $info = JWT::decode($jwt,$key,["HS256"]); //解密jwt
    return $info;
}

/**
 * 二维数组根据某个字段排序
 * @param array $array 要排序的数组
 * @param string $keys   要排序的键字段
 * @param string $sort  排序类型  SORT_ASC     SORT_DESC
 * @return array 排序后的数组
 */
function arraySort($array, $keys, $sort = SORT_DESC) {
    $keysValue = [];
    foreach ($array as $k => $v) {
        $keysValue[$k] = $v[$keys];
    }
    array_multisort($keysValue, $sort, $array);
    return $array;
}

/*
 * 根据生日计算年龄
 * @param $birthday 月/日/年
 * */
function birthday($birthday){
    list($month,$day,$year) = explode("/",$birthday);
    $year_diff = date("Y") - $year;
    $month_diff = date("m") - $month;
    $day_diff  = date("d") - $day;
    if ($day_diff < 0 || $month_diff < 0)
        $year_diff--;
    return $year_diff;
}

/**
+----------------------------------------------------------
 * 功能：计算两个日期相差 年 月 日
+----------------------------------------------------------
 * @param  date     $date1 起始日期
 * @param  date     $date2 截止日期日期
+----------------------------------------------------------
 * @return array
+----------------------------------------------------------
 */
function DiffDate($date1, $date2) {
    if (strtotime($date1) > strtotime($date2)) {
        $ymd = $date2;
        $date2 = $date1;
        $date1 = $ymd;
    }
    list($m1, $d1, $y1) = explode('/', $date1);
    list($m2, $d2, $y2) = explode('/', $date2);
    $y = $m = $d = $_m = 0;
    $math = ($y2 - $y1) * 12 + $m2 - $m1;
    $y = round($math / 12);
    $m = intval($math % 12);
    $d = (mktime(0, 0, 0, $m2, $d2, $y2) - mktime(0, 0, 0, $m2, $d1, $y2)) / 86400;
    if ($d < 0) {
        $m -= 1;
        $d += date('j', mktime(0, 0, 0, $m2, 0, $y2));
    }
    $m < 0 && $y -= 1;
//    return $y;
    return array($y, $m, $d);
}

/**
 * 校验日期格式是否合法
 * @param string $date
 * @param array $formats
 * @return bool
 */
function isDateValid($date, $formats = array('Y-m-d', 'Y/m/d')) {

    $unixTime = strtotime($date);
    if(!$unixTime) { //无法用strtotime转换，说明日期格式非法
        return false;
    }

    //校验日期合法性，只要满足其中一个格式就可以
    foreach ($formats as $format) {
        if(date($format, $unixTime) == $date) {
            return true;
        }
    }

    return false;
}


/**
 * 获取当前ip地址信息
 * @return string $ip
 * */
function getip() {
    static $ip = '';
    $ip = $_SERVER['REMOTE_ADDR'];
    if(isset($_SERVER['HTTP_CDN_SRC_IP'])) {
        $ip = $_SERVER['HTTP_CDN_SRC_IP'];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        foreach ($matches[0] AS $xip) {
            if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                $ip = $xip;
                break;
            }
        }
    }
    return $ip;
}


/**
 * 通过ip获取当前位置信息
 * @param string $ip
 * @return array
 * */
function curl_get($ip,$ak='Gym2KVPOIWu8taayxggc8yT102egQnYL'){
    $url='http://api.map.baidu.com/location/ip?ip='.$ip.'&ak='.$ak;
    $header = array(
        'Accept: application/json',
    );
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 0);
    // 超时设置,以秒为单位
    curl_setopt($curl, CURLOPT_TIMEOUT, 1);

    // 超时设置，以毫秒为单位
    // curl_setopt($curl, CURLOPT_TIMEOUT_MS, 500);

    // 设置请求头
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    //执行命令
    $data = curl_exec($curl);

    // 显示错误信息
    if (curl_error($curl)) {
        print "Error: " . curl_error($curl);
    } else {
        // 打印返回的内容
        curl_close($curl);
        return json_decode($data,true);
    }
}

/**
 * md5判断
 * @access  public
 * @param   string      $w     字符
 * @return  bool
 */
function is_md5($w){
    return preg_match("/^[a-f0-9]{32}$/", $w);
}

/**
 * IP判断
 * @access  public
 * @param   string      $ip     IP地址
 * @return  bool
 */
function is_ip($ip) {
    return preg_match("/^([0-9]{1,3}\.){3}[0-9]{1,3}$/", $ip);
}

/**
 * 手机号判断
 * @access  public
 * @param   string      $mb     手机号
 * @return  bool
 */
function is_mobile($mb) {
    return preg_match("/^1[3|4|5|6|7|8|9]{1}[0-9]{9}$/", $mb);
}
function curlPost( $url, $postdata = '') {
    $header = array(
        'Accept: application/json',
    );
    header("Content-Type: text/html; charset=utf-8");
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36');
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE );
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE );
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);

    $data = json_decode(curl_exec($curl),true);
    curl_close ( $curl );

    return $data;
}
/**
 * @desc 根据两点间的经纬度计算距离
 * @param float $lat 纬度值
 * @param float $lng 经度值
 */

function getDistance($lat1, $lng1, $lat2, $lng2)
{
    $earthRadius = 6367000; //approximate radius of earth in meters

    $lat1 = ($lat1 * pi() ) / 180;

    $lng1 = ($lng1 * pi() ) / 180;

    $lat2 = ($lat2 * pi() ) / 180;

    $lng2 = ($lng2 * pi() ) / 180;

    $calcLongitude = $lng2 - $lng1;

    $calcLatitude = $lat2 - $lat1;

    $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);

    $stepTwo = 2 * asin(min(1, sqrt($stepOne)));

    $calculatedDistance = $earthRadius * $stepTwo;

    $range=round($calculatedDistance,2);

    if($range>1000){
        $range = round($range/1000,2)."km";
    }else{
        $range  =   $range.'m';
    }
    return $range;

}
/**
 * 生成指定长度的随机字符
 * @access  public
 * @param   int       $l     指定长度
 * @param   string    $c     源字符集
 * @return  bool
 */
function Random($l,$c = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz') {
    $h = '';
    $m = strlen($c) - 1;
    for($i = 0; $i < $l; $i++){$h .= $c[mt_rand(0, $m)];}
    return $h;
}

/**
 * 返回字符串长度
 * @access  public
 * @param   string    $str     源字符串
 * @return  bool
 */
function word_count($str) {
    if(function_exists('mb_strlen')) return mb_strlen($str, 'utf8');
    return strlen($str);
    //下面待确定
    $str = convert($str, 'utf8', 'gbk');
    $length = strlen($str);
    $count = 0;
    for($i = 0; $i < $length; $i++) {
        $t = ord($str[$i]);
        if($t > 127) $i++;
        $count++;
    }
    return $count;
}

/**
 * 判断字符是否合法 只允许：数字 汉字 英文字母 下划线
 * @access  public
 * @param   string    $str     源字符串
 * @return  bool
 */
function is_clean($str) {
    return preg_match("/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u", $str);
}

/**
 * 生成密码
 * @access  public
 * @param   string      $p     密码
 * @param   string      $s     密钥
 * @return  string
 */
function SetPassword($p,$s){
    return md5((is_md5($p) ? md5($p) : md5(md5($p))).$s);
}

/**
 * 生成验证码
 * @access  public
 * @param   int      $t     1字母数字混合 2表示纯数字
 * @param   int      $l     验证码长度
 * @return  imgs
 */
function Verify($t=1,$l=5){
    $Verify = new \verify\Verify();
    $Verify->length = $l;
    if($t==2) $Verify->codeSet = '12356896298153058995659852126';
    $Verify->entry();
}

/**
 * 核对验证码
 * @access  public
 * @param   string      $c    字符
 * @return  bool
 */
function VerifyCheck($c){
    $verify = new \verify\Verify();
    return $verify->check($c);
}

/**
 * 返回组信息 return
 * @access  public
 * @param   string      $m     信息字符
 * @param   int         $s     状态值
 * @param   int         $d     数组信息
 * @return  array
 */
function ReturnMsg($m,$s = -1,$d = []){
    $m = $m ? $m : '无提示信息！';
    $rs = ['status'=>$s,'msg'=>$m];
    if(!empty($d)) $rs['data'] = $d;
    return $rs;
}

/**
 * 返回通过URL异步请求的数据格式
 * @access  public
 * @param   string      $rs     数据源
 * @return  array
 */
function VData($rs){
    if(!is_array($rs)) $rs = $rs->toArray();
    $c = 0;$m = '';
    if(isset($rs['total'])){//分页模式
        if($rs['total']<=0){$c = 1;$m = '未找到相关数据！';}
    }else{
        $r['data'] = $rs;
        $r['total'] = count($rs);
        if($r['total']<=0){$c = 1;$m = '未找到相关数据！';}
        $rs = $r;
    }
    $rs = ['code'=>$c,'msg'=>$m,'count'=>$rs['total'],'data'=>$rs['data']];
    return $rs;
}

/**
 * 终止信息
 * @access  public
 * @param   string      $m     信息字符
 * @param   int         $s     状态值
 * @param   int         $d     数组信息
 * @return  json
 */
function ExitMsg($m,$s = -1,$d = []){
    exit(json_encode(ReturnMsg($m,$s,$d)));
}

/**
 * 获取系统配置数据（缓存模式）
 * @param   int     $reset    是否重置缓存
 * @return  array
 */
function VSetting($reset=0){
    $rs = cache('VSETTING');
    if(!$rs || $reset){
        $rv = Db::name('setting')->field('item_key,item_value')->select();
        $rs = [];
        foreach ($rv as $v){
            $rs[$v['item_key']] = $v['item_value'];
        }
        cache('VSETTING',$rs,31536000);
    }
    return $rs;
}

/**
 * 获取指定的全局配置
 * @param   int         $k     键名
 * @param   int         $v     数据 为NULL时销毁该键 为空时获取该键值 否则赋值该键并返回
 * @return  array
 */
function VConfig($k,$v = ''){
    $gkey = 'VGLOBALS';
    if(is_null($v)){
        if(array_key_exists($gkey,$GLOBALS) && array_key_exists($k,$GLOBALS[$gkey])){
            unset($GLOBALS[$gkey][$k]);
        }
    }else if($v === ''){
        if(array_key_exists($gkey,$GLOBALS)){
            $conf = $GLOBALS[$gkey];
            $ks = explode(".",$k);
            for($i=0,$c=count($ks);$i<$c;$i++){
                if(is_array($conf) && array_key_exists($ks[$i],$conf)){
                    $conf = $conf[$ks[$i]];
                }else{
                    return null;
                }
            }
            return $conf;
        }
    }else{
        return $GLOBALS[$gkey][$k] = $v;
    }
    return null;
}

/**
 * 字符过滤
 * @param   string      $s       目标字符
 * @param   int         $t       过滤类型
 * @return  string
 */
function strip_sql($s, $t = 1) {
    if(is_array($s)) {
        return array_map('strip_sql', $s);
    } else {
        if($t) {
            $p = 'wt_';
            $s = preg_replace("/\/\*([\s\S]*?)\*\//", "", $s);
            $s = preg_replace("/0x([a-f0-9]{2,})/i", '0&#120;\\1', $s);
            $s = preg_replace_callback("/(select|update|replace|delete|drop)([\s\S]*?)(".$p."|from)/i", 'strip_wd', $s);
            $s = preg_replace_callback("/(load_file|substring|substr|reverse|trim|space|left|right|mid|lpad|concat|concat_ws|make_set|ascii|bin|oct|hex|ord|char|conv)([^a-z]?)\(/i", 'strip_wd', $s);
            $s = preg_replace_callback("/(union|where|having|outfile|dumpfile|".$p.")/i", 'strip_wd', $s);
            return $s;
        } else {
            return str_replace(array('&#95;','&#100;','&#101;','&#103;','&#105;','&#109;','&#110;','&#112;','&#114;','&#115;','&#116;','&#118;','&#120;'), array('_','d','e','g','i','m','n','p','r','s','t','v','x'), $s);
        }
    }
}

/**
 * 字符过滤
 * @param   string       $m       目标字符
 * @return  string
 */
function strip_wd($m) {
    if(is_array($m) && isset($m[1])) {
        $wd = substr($m[1], 0, -1).'&#'.ord(strtolower(substr($m[1], -1))).';';
        if(isset($m[3])) return $wd.$m[2].$m[3];
        if(isset($m[2])) return $wd.$m[2].'(';
        return $wd;
    }
    return '';
}

/**
 * 字符转换
 * @param   string       $s       目标字符
 * @return  string
 */
function dhtmlspecialchars($s) {
    if(is_array($s)) {
        return array_map('dhtmlspecialchars', $s);
    } else {
        $s = htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
        return str_replace('&amp;', '&', $s);
    }
}

/**
 * 数字格式转换
 * @param    float       $v      数值
 * @param    int         $p      小数点后位数
 * @param    bool        $s      是否格式化为字符串
 * @return   float
 */
function dround($v, $p = 2, $s = false) {
    $v = round(floatval($v), $p);
    if($s) $v = sprintf('%.'.$p.'f', $v);
    return $v;
}

/*========= 文件操作相关 ==========*/
if(!function_exists('file_put_contents')) {
    define('FILE_APPEND', 8);
    function file_put_contents($file, $string, $append = '') {
        $mode = $append == '' ? 'wb' : 'ab';
        $fp = @fopen($file, $mode) or exit("Can not open $file");
        flock($fp, LOCK_EX);
        $stringlen = @fwrite($fp, $string);
        flock($fp, LOCK_UN);
        @fclose($fp);
        return $stringlen;
    }
}

/**
 * 获取扩展名
 * @param    string       $filename      文件路径串
 * @return   string
 */
function file_ext($filename) {
    if(strpos($filename, '.') === false) return '';
    $ext = strtolower(trim(substr(strrchr($filename, '.'), 1)));
    return preg_match("/^[a-z0-9]{1,10}$/", $ext) ? $ext : '';
}

function file_vname($name) {
    if(strpos($name, '/') === false) return str_replace(array(' ', '\\', ':', '*', '?', '"', '<', '>', '|', "'", '$', '&', '%', '#', '@'), array('-', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''), $name);
    $tmp = explode('/', $name);
    $str = '';
    foreach($tmp as $k=>$v) {
        $str .= ($k ? '/' : '').file_vname($v);
    }
    return $str;
}

/**
 * 文件、数据下载
 * @param    string       $file          文件路径
 * @param    string       $filename      文件名
 * @param    string       $data          下载的数据
 * @return   string
 */
function file_down($file, $filename = '', $data = '') {
    if(!$data && !is_file($file)) exit;
    $filename = $filename ? $filename : basename($file);
    $filetype = file_ext($filename);
    $filesize = $data ? strlen($data) : filesize($file);
    ob_end_clean();
    @set_time_limit(0);
    header("Content-type: text/html; charset=utf-8");
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) {
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
    } else {
        header('Pragma: no-cache');
    }
    header('Expires: '.gmdate('D, d M Y H:i:s').' GMT');
    header('Content-Encoding: none');
    header('Content-Length: '.$filesize);
    header('Content-Disposition: attachment; filename='.$filename);
    header('Content-Type: '.$filetype);
    if($data) { echo $data; } else { readfile($file); }
    exit;
}

function file_list($dir, $fs = array()) {
    $files = glob($dir.'/*');
    if(!is_array($files)) return $fs;
    foreach($files as $file) {
        if(is_dir($file)) {
            $fs = file_list($file, $fs);
        } else {
            $fs[] = $file;
        }
    }
    return $fs;
}

function file_copy($from, $to) {
    dir_create(dirname($to));
    if(is_file($to) && VT_FMOD) @chmod($to, VT_FMOD);
    if(strpos($from, DT_PATH) !== false) $from = str_replace(DT_PATH, DT_ROOT.'/', $from);
    if(@copy($from, $to)) {
        if(VT_FMOD) @chmod($to, VT_FMOD);
        return true;
    } else {
        return false;
    }
}

/**
 * 写入文件
 * @param   string       $f      文件名（可带路径）
 * @param   string       $d      文件内容
 * @return  int
 */
function file_put($f, $d) {
    dir_create(dirname($f));
    if(@$fp = fopen($f, 'wb')) {
        flock($fp, LOCK_EX);
        $l = fwrite($fp, $d);
        flock($fp, LOCK_UN);
        fclose($fp);
        if(VT_FMOD) @chmod($f, VT_FMOD);
        return $l;
    } else {
        return false;
    }
}

/**
 * 函数把整个文件读入一个字符串中
 * @param  string    $filename   文件路径
 * @return string
 */
function file_get($filename) {
    return @file_get_contents($filename);
}

/**
 * 删除文件
 * @param   string       $filename      文件名
 * @return  bool
 */
function file_del($filename) {
    if(VT_FMOD) @chmod($filename, VT_FMOD);
    return is_file($filename) ? @unlink($filename) : false;
}

/**
 * 路径规范处理
 * @param   string       $dirpath      目录路径（可多层）
 * @return  string
 */
function dir_path($dirpath) {
    $dirpath = str_replace('\\', '/', $dirpath);
    if(substr($dirpath, -1) != '/') $dirpath = $dirpath.'/';
    return $dirpath;
}

/**
 * 创建目录
 * @param   string       $path       目录路径（可多层）
 * @return  bool
 */
function dir_create($path) {
    if(is_dir($path)) return true;
    $dir = str_replace(VT_ROOT, '', $path);
    $dir = dir_path($dir);
    $temp = explode('/', $dir);
    $cur_dir = VT_ROOT;
    $max = count($temp) - 1;
    for($i = 0; $i < $max; $i++) {
        $cur_dir .= $temp[$i].'/';
        if(is_dir($cur_dir)) continue;
        @mkdir($cur_dir);
        if(VT_FMOD) @chmod($cur_dir, VT_FMOD);
    }
    return is_dir($path);
}

function dir_chmod($dir, $mode = '', $require = 0) {
    if(!$require) $require = substr($dir, -1) == '*' ? 2 : 0;
    if($require) {
        if($require == 2) $dir = substr($dir, 0, -1);
        $dir = dir_path($dir);
        $list = glob($dir.'*');
        foreach($list as $v) {
            if(is_dir($v)) {
                dir_chmod($v, $mode, 1);
            } else {
                @chmod(basename($v), $mode);
            }
        }
    }
    if(is_dir($dir)) {
        @chmod($dir, $mode);
    } else {
        @chmod(basename($dir), $mode);
    }
}

function dir_copy($fromdir, $todir) {
    $fromdir = dir_path($fromdir);
    $todir = dir_path($todir);
    if(!is_dir($fromdir)) return false;
    if(!is_dir($todir)) dir_create($todir);
    $list = glob($fromdir.'*');
    foreach($list as $v) {
        $path = $todir.basename($v);
        if(is_file($path) && !is_writable($path)) {
            if(VT_FMOD) @chmod($path, VT_FMOD);
        }
        if(is_dir($v)) {
            dir_copy($v, $path);
        } else {
            @copy($v, $path);
            if(VT_FMOD) @chmod($path, VT_FMOD);
        }
    }
    return true;
}

/**
 * 删除文件夹
 * @param   string       $dir      目录路径
 * @return  bool
 */
function dir_delete($dir) {
    $dir = dir_path($dir);
    if(!is_dir($dir)) return false;
    $dirs = VT_ROOT.'file/';
    if(substr($dir, 0, 1) == '.' || strpos($dir, $dirs) === false) die("Cannot Remove System DIR $dir");
    $list = glob($dir.'*');
    if($list) {
        foreach($list as $v) {
            is_dir($v) ? dir_delete($v) : @unlink($v);
        }
    }
    return @rmdir($dir);
}

function get_file($dir, $ext = '', $fs = array()) {
    $files = glob($dir.'/*');
    if(!is_array($files)) return $fs;
    foreach($files as $file) {
        if(is_dir($file)) {
            if(is_file($file.'/index.php') && is_file($file.'/config.inc.php')) continue;
            $fs = get_file($file, $ext, $fs);
        } else {
            if($ext) {
                if(preg_match("/\.($ext)$/i", $file)) $fs[] = $file;
            } else {
                $fs[] = $file;
            }
        }
    }
    return $fs;
}

function is_write($file) {
    if(DT_WIN) {
        if(substr($file, -1) == '/') {
            if(is_dir($file)) {
                $file = $file.'writeable-test.tmp';
                if(@$fp = fopen($file, 'a')) {
                    flock($fp, LOCK_EX);
                    fwrite($fp, 'OK');
                    flock($fp, LOCK_UN);
                    fclose($fp);
                    $tmp = file_get_contents($file);
                    unlink($file);
                    if($tmp == 'OK') return true;
                }
                return false;
            } else {
                dir_create($file);
                if(is_dir($file)) return is_write($file);
                return false;
            }
        } else {
            if(@$fp = fopen($file, 'a')) {
                fclose($fp);
                return true;
            }
            return false;
        }
    } else {
        return is_writeable($file);
    }
}
/*========= 文件操作 END ==========*/

/**
 * 数据重构
 * @param   string       $sql      备份数据
 * @return  array
 */
function sql_split($sql) {
    /** /
    $db_charset = 'utf8';
    $rs = Db::query("SELECT VERSION() as v");
    if($db_charset) $sql = $rs[0]['v'] > '4.1' ? preg_replace("/TYPE=(MyISAM|InnoDB|HEAP|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=".$db_charset, $sql) : preg_replace("/( DEFAULT CHARSET=[^; ]+)?/", '', $sql);
    /**/
    if(config('database.prefix') != 'wt_') $sql = str_replace('wt_', config('database.prefix'), $sql);
    $sql = str_replace("\r", "\n", $sql);
    $sql = str_replace("; \n", ";\n", $sql);
    $ret = array();
    $num = 0;
    $queriesarray = explode(";\n", trim($sql));
    unset($sql);
    foreach($queriesarray as $query) {
        $ret[$num] = '';
        $queries = explode("\n", trim($query));
        $queries = array_filter($queries);
        foreach($queries as $query) {
            $str1 = substr($query, 0, 1);
            if($str1 != '#' && $str1 != '-') $ret[$num] .= $query;
        }
        $num++;
    }
    return $ret;
}

/**
 * 数据导入
 * @param   string       $sql      备份数据
 * @return  bool
 */
function sql_execute($sql) {
    $sqls = sql_split($sql);
    if(is_array($sqls)) {
        foreach($sqls as $sql) {
            if(trim($sql) != '') Db::query($sql);
        }
    } else {
        Db::query($sqls);
    }
    return true;
}

/**
 * 地区查询
 * @param   int         $areaid       地区ID
 * @param   string      $str          分隔符
 * @param   int         $deep         查找深度
 * @param   int         $start        查找开始
 * @return  bool
 */
function area_pos($areaid, $str = ' &raquo; ', $deep = 0, $start = 0) {
    $areaid = intval($areaid);
    if(!$areaid) return '';
    $AREA = cache('VAREAS');
    $arrparentid = $AREA[$areaid]['arrparentid'] ? explode(',', $AREA[$areaid]['arrparentid']) : array();
    $arrparentid[] = $areaid;
    $pos = '';
    if($deep) $i = 1;
    $j = 0;
    foreach($arrparentid as $areaid) {
        if(!$areaid || !isset($AREA[$areaid])) continue;
        if($j++ < $start) continue;
        if($deep) {
            if($i > $deep) continue;
            $i++;
        }
        $pos .= $AREA[$areaid]['areaname'].$str;
    }
    $_len = strlen($str);
    if($str && substr($pos, -$_len, $_len) === $str) $pos = substr($pos, 0, strlen($pos)-$_len);
    return $pos;
}

/**
 * 异步多级地区调用
 * @param   int         $title         查询
 * @param   string      $areaid        分隔符
 * @param   int         $extend        查找深度
 * @param   int         $deep          查找开始
 * @param   int         $id            查找开始
 * @return  bool
 */
function get_area_select($title = '', $areaid = 0, $extend = '', $deep = 0, $id = 1, $name='') {
    $parents = array();
    if($areaid) {
        $r = Db::name('area')->field('childs,arrparentid')->where("areaid=$areaid")->find();
        $r['arrparentid'] = $r['arrparentid'] ? '0,'.$r['arrparentid'] : $r['arrparentid'];
        $parents = explode(',', $r['arrparentid']);
        if($r['childs']) $parents[] = $areaid;
    } else {
        $parents[] = 0;
    }
    $select = '';
    foreach($parents as $k=>$v) {
        if($deep && $deep <= $k) break;
        $v = intval($v);//onchange="//load_area(this.value, '.$id.');"
        $select .= '<div class="layui-inline" style="width:168px"><select '.$extend.' lay-filter="'.$name.'">';
        if($title) $select .= '<option value="'.$v.'">'.$title.'</option>';
        $r = Db::name('area')->field('areaid,areaname')->where("parentid=$v")->order(['listorder'=>'ASC','areaid'=>'ASC'])->select();
        foreach ($r as $a){
            $selectid = isset($parents[$k+1]) ? $parents[$k+1] : $areaid;
            $selected = $a['areaid'] == $selectid ? ' selected' : '';
            $select .= '<option value="'.$a['areaid'].'"'.$selected.'>'.$a['areaname'].'</option>';
        }
        $select .= '</select></div>';
    }
    return $select;
}

/**
 * 异步多级地区调用
 * @param   int         $name         提交表单时元素的名称
 * @param   string      $title        下拉的顶端提示
 * @param   int         $areaid       初始上级ID
 * @param   int         $extend       select标签上的 html 扩展
 * @param   int         $deep         显示的最大级数
 * @return  bool
 */
function ajax_area_select($name = 'areaid', $title = '', $areaid = 0, $extend = '', $deep = 0) {
    global $area_id;
    if($area_id) {
        $area_id++;
    } else {
        $area_id = 1;
    }
    $areaid = intval($areaid);
    $deep = intval($deep);
    $select = '';
    $select .= '<input name="'.$name.'" id="areaid_'.$area_id.'" type="hidden" value="'.$areaid.'"/>';
    $select .= '<span id="load_area_'.$area_id.'">'.get_area_select($title, $areaid, $extend, $deep, $area_id, $name).'</span>';
    /** /
    $select .= '<script type="text/javascript">';
    if($area_id == 1) $select .= 'var area_title = new Array;';
    $select .= 'area_title['.$area_id.']=\''.$title.'\';';
    if($area_id == 1) $select .= 'var area_extend = new Array;';
    $select .= 'area_extend['.$area_id.']=\''.encrypt($extend, 'ARE').'\';';
    if($area_id == 1) $select .= 'var area_areaid = new Array;';
    $select .= 'area_areaid['.$area_id.']=\''.$areaid.'\';';
    if($area_id == 1) $select .= 'var area_deep = new Array;';
    $select .= 'area_deep['.$area_id.']=\''.$deep.'\';';
    $select .= '</script>';
    if($area_id == 1) $select .= '<script type="text/javascript" src="/static/script/area.js"></script>';
    /**/
    return $select;
}

//function encrypt($txt, $key = '', $expiry = 0) {
//	strlen($key) > 5 or $key = 'ssdsd';
//	$str = $txt.substr($key, 0, 3);
//	return str_replace(array('=', '+', '/', '0x', '0X'), array('-E-', '-P-', '-S-', '-Z-', '-X-'), mycrypt($str, $key, 'ENCODE', $expiry));
//}
//
//function decrypt($txt, $key = '') {
//	strlen($key) > 5 or $key = DT_KEY;
//	$str = mycrypt(str_replace(array('-E-', '-P-', '-S-', '-Z-', '-X-'), array('=', '+', '/', '0x', '0X'), $txt), $key, 'DECODE');
//	return substr($str, -3) == substr($key, 0, 3) ? substr($str, 0, -3) : '';
//}

function mycrypt($string, $key, $operation = 'DECODE', $expiry = 0) {
    $ckey_length = 4;
    $key = md5($key);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if($operation == 'DECODE') {
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc.base64_encode($result);
    }
}

/**
 * 发送短信（短信宝接口）
 * @param   int         $mobile        手机号
 * @param   string      $message       短信内容
 * @param   int         $word          发送的字数
 * @access  public
 */
function send_sms($mobile, $message, $word = 0) {
    $V = VConfig('VSETTING');
    if(!$V['sms'] || !$V['sms_username'] || !$V['sms_password'] || !is_mobile($mobile) || strlen($message) < 5) return false;
    $word or $word = word_count($message);
    $sms_message = rawurlencode($message);// rawurlencode(convert($message, DT_CHARSET, 'UTF-8'));
    $url = 'http://www.smsbao.com/sms?u='.$V['sms_username'].'&p='.$V['sms_password'].'&m='.$mobile.'&c='.$sms_message;
    $fp = fopen($url, 'r') or exit('Open url faild!');
    $key = '';
    if($fp){
        while(!feof($fp)) {
            $key.=fgets($fp)."";
        }
        fclose($fp);
    }
    $key = intval(trim($key));
    $keys = array(
        '0'  => '成功',
        '30' => '密码错误',
        '40' => '账号不存在',
        '41' => '余额不足',
        '42' => '帐号过期',
        '43' => 'IP地址限制',
        '50' => '内容含有敏感词',
        '51' => '手机号码不正确'
    );
    $code = $keys[$key];
    $data = ['mobile'=>$mobile,'message'=>$message,'word'=>$word,'editor'=>'system','sendtime'=>time(),'code'=>$code];
    Db::name('sms')->data($data)->insert();
    return $code;
}

/**
 * 广告调用
 * @param   int         $pid       广告位ID
 * @param   int         $t         类型
 * @access  public
 */
function ad($pid,$t=1) {
    $str = '';
    $r = Db::name('ad_place')->field('width,height')->where("pid=$pid AND state")->find();
    if($r){
        $w = $r['width']==0 ? 'auto' : $r['width'];
        $h = $r['height']==0 ? 'auto' : $r['height'];
        $r = Db::name('ad')->field('src,url,alt')->where("pid=$pid AND state")->order('listorder','asc')->select();
        if($r){
            if($t==1){
                foreach ($r as $v){
                    $str .= '<a class="fui-swipe-item" href="'.$v['url'].'" data-nocache="true"><img src="'.$v['src'].'" style="display:block;width:'.$w.';height:'.$h.';"></a>';
                }
            }elseif($t==2){
                foreach ($r as $v){
                    $str .= '<li><a href="'.$v['url'].'"><img src="'.$v['src'].'" /></a></li>';
                }
            }
        }
    }
    echo $str;
}


/**
 * 键串转换键值串
 * @param   string         $ids       键串
 * @param   array          $arr       数组
 * @return  string
 * @access  public
 */
function idstoname($ids,$arr) {
    $str = '';
    $a = explode(',', $ids);
    foreach($a as $i){
        $t = isset($arr[$i]) ? $arr[$i] : '';
        if($t) $str .= $str ? '，'.$t : $t;
    }
    return $str;
}


/**
 * 编码转换
 * @param   string         $str       数据
 * @param   string         $from      转换的编码
 * @return  string         $to
 * @access  public
 */
function convert($str, $from = 'utf-8', $to = 'gb2312') {
    if(!$str) return '';
    $from = strtolower($from);
    $to = strtolower($to);
    if($from == $to) return $str;
    $from = str_replace('gbk', 'gb2312', $from);
    $to = str_replace('gbk', 'gb2312', $to);
    $from = str_replace('utf8', 'utf-8', $from);
    $to = str_replace('utf8', 'utf-8', $to);
    if($from == $to) return $str;
    $tmp = array();
    if(function_exists('mb_convert_encoding')) {
        if(is_array($str)) {
            foreach($str as $key => $val) {
                $tmp[$key] = mb_convert_encoding($val, $to, $from);
            }
            return $tmp;
        } else {
            return mb_convert_encoding($str, $to, $from);
        }
    } else if(function_exists('iconv')) {
        if(is_array($str)) {
            foreach($str as $key => $val) {
                $tmp[$key] = iconv($from, $to."//IGNORE", $val);
            }
            return $tmp;
        } else {
            return iconv($from, $to."//IGNORE", $str);
        }
    } else {
        return $str;
        //require_once '/include/convert.func.php';
        //return dconvert($str, $to, $from);
    }
}


/**
 * 多级列表构造（有递归）
 * @param  array  $rs      所有菜单数组集
 * @param  int    $pid     开始的父级ID
 * @param  array  $key     3要素 ['id','parentid','title'] 顺序不能变
 * @param  int    $tt      填充符
 * @param  int    $j       层级数
 * @param  string $s       缩进符
 * @param  array  $ids     某id键的子类个数集
 * @param  array  $arr     返回的父子重构顺序集 相对 $rs 多了 new_title 键
 * @return array
 */
function list_tree($rs=[],$pid=0,$key=['id','parentid','title'],$tt=1,$j=0,$s='',$ids=[],$arr=[]){
    if(empty($rs)) return $arr;
    $ids = empty($ids) ? array_count_values(array_column($rs,$key[1])) : $ids;
    $i = 1;
    $k = $j;
    $a = $s;
    if($tt==1){
        $c = array("&nbsp;&nbsp;&nbsp;│ ",'&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
    }else{
        $c = array(" - "," - "," - "," - ");
    }
    foreach ($rs as $v){
        if($pid == $v[$key[1]]){
            $t = '';
            if($k>0){
                $t = ($ids[$pid]==$i) ? $a.$c[2] : $a.$c[1];
                $s = ($ids[$pid]==$i) ? $a.$c[3] : $a.$c[0];
            }
            $v['new_title'] = $t.$v[$key[2]];
            $arr[] = $v;
            $id = $v[$key[0]];
            if(isset($ids[$id])){
                $j = $k+1;
                $arr = list_tree($rs,$id,$key,$tt,$j,$s,$ids,$arr);
            }
            $i++;
        }
    }
    return $arr;
}
