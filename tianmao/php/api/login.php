<?php
      include "../config/db.php";
    $body = @file_get_contents('php://input');//接受整个请求主体
    $body=json_decode($body)  ;//反序列化
    $APPID ="wxc7bfc8bffe66fad3";
    $AppSecret ="816d6274af601c67d79d682db2f07a40";
    
    if(isset($body->code)){
        $code=$body->code;//获取欲取参数
        $opts = array(
            'http'=>array(
              'method'=>"GET",
              'header'=>"'Content-type:application/x-www-form-urlencoded",
            )
        );
        $url="https://api.weixin.qq.com/sns/jscode2session?appid=".$APPID."&secret=".$AppSecret."&js_code=".$code."&grant_type=authorization_code";
    
        $context = stream_context_create($opts);
        $html = file_get_contents($url, false, $context);
    
        $arr = json_decode($html, true);
    
        if($arr){
            if( in_array('errcode',$arr) && $arr['errcode'] != 0){
                $re = [
                    'status' => 0,
                    'message'  => '网络错误'   
                ];
            }else{
                $openid = $arr['openid'];
                $session_key = $arr['session_key'];
                $skey = md5($session_key);
                $actived = false;
                
                $filter = $conn->query("SELECT *  FROM lucky WHERE openid='$openid'");
                $reLen = mysqli_num_rows($filter);
                if($reLen > 0){
                    $actived = true;
                }
                $re = [
                    'status' => 1,
                    'message'  => '请求成功',     
                    'openid'  => $openid,     
                    'skey'  => $skey,
                    'actived'=> $actived,   //actived 为true表示参与过   false表示未参与过
                ];
            }
        }else{
            $re = [
                'status' => 0,
                'message'  => '网络错误'   
            ];
        }
    }else{
        $re = [
            'status' => 0,
            'message'  => ' 参数错误'   
        ];
    }

    $json = json_encode($re);
    echo $json;
    die();
?>