<?php
    include "../config/db.php";

    function lockString($txt,$key='ema'){
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
        $nh = rand(0,64);
        $ch = $chars[$nh];
        $mdKey = md5($key.$ch);
        $mdKey = substr($mdKey,$nh%8, $nh%8+7);
        $txt = base64_encode($txt);
        $tmp = '';
        $i=0;$j=0;$k = 0;
        for ($i=0; $i<strlen($txt); $i++) {
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = ($nh+strpos($chars,$txt[$i])+ord($mdKey[$k++]))%64;
            $tmp .= $chars[$j];
        }
        return urlencode($ch.$tmp);
    }



    $datetime = new DateTime();
    $starttime = $datetime->format('Y-m-d H:i:s');
 /**openid    	skey   nickName   avatarUrl   phone   prize   starttime   endtime   status */
    $body = @file_get_contents('php://input');//接受整个请求主体
    $body=json_decode($body) ;//反序列化
    
    if(isset($body->openid) && 
    isset($body->nickName) &&
    isset($body->avatarUrl) &&
    isset($body->prize)){

        $openid=$body->openid;
        $nickName=$body->nickName;
        $avatarUrl=$body->avatarUrl;
        $prize=$body->prize;
        $code = lockString($openid);

        $filter = $conn->query("SELECT *  FROM lucky WHERE openid='$openid'");
        $filterLen = mysqli_num_rows($filter);

        if($filterLen > 0){
            $reData = [
                'status'  => 2,     
                'message'  => '您已经参与过此活动',     
            ];
        }else{
            $sql = "INSERT INTO `lucky`( `openid`,`nickName`,`avatarUrl`,`prize`,`starttime`,`code`) VALUES ('$openid','$nickName','$avatarUrl','$prize','$starttime','$code')";
            $query =  $conn->query($sql);
            if($query){
                $reData = [
                    'status'  => 1,     
                    'message'  => '抽奖成功',     
                ];
            }else{
                $reData = [
                    'status'  => 0,     
                    'message'  => '抽奖失败', 
                ];
            }
        }
    }else{
        $reData = [
            'status' => 0,
            'message' =>'参数错误',
        ];
    }

    


    $json = json_encode($reData);
    echo $json;
    
?>