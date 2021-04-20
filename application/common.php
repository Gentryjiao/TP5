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


