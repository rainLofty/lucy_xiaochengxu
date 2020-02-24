<?php
    include "../../config/db.php";

    $body = @file_get_contents('php://input');//接受整个请求主体
    $body=json_decode($body) ;//反序列化

    if(isset($body->username) && isset($body->password)){
        $username=$body->username;
        $password=$body->password;
        if($username == 'yimait' && $password == 'yimait123456'){
            $sql = "SELECT * FROM `admin_user` WHERE `username`='$username' AND `passwod`='$password'";
            $temp = $conn->query($sql);
            $result = mysqli_fetch_array($temp,MYSQL_ASSOC);
        
            if($result){
                $reData = [
                    'status' => 1,
                    'message' =>'登录成功',
                    'userId'=>$result['id'],
                ];
            }else{
                $reData = [
                    'status' => 0,
                    'message' =>'账号或密码错误',
                ];
            }
        }else{
            $reData = [
                'status' => 0,
                'message' =>'账号或密码错误',
            ];
        }
        
    }else{
        $reData = [
            'status' => 0,
            'message' =>'账号或密码错误',
        ];
    }
    $json = json_encode($reData);//把数据转换为JSON数据.
    echo $json;
?>