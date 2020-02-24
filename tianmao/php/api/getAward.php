<?php
    // include "config/db.php";
    include "../config/db.php";
    $body = @file_get_contents('php://input');
    $body=json_decode($body) ;//反序列化
   
    if(isset($body->openid)){
        $openid=$body->openid;
        $selectRe = $conn->query("SELECT  * FROM `lucky` where openid='$openid'");

        $filterresult =  mysqli_fetch_array($selectRe,MYSQL_ASSOC);
        
        $prizeId =  $filterresult['prize'];
        $starttime =  $filterresult['starttime'];
        $code =  $filterresult['code'];
        if($prizeId && $code){
            $result = $conn->query("SELECT  * FROM `prize` where id='$prizeId'");
            if($result){
                $data = array();
                while ($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
                    $data = [
                        'title'  => $row['title'],  
                        'src'  => $row['src'],  
                        'starttime'  => $starttime,  
                        'code'  => $code,  
                    ];
                }
                $reData = [
                    'status'  => 1 ,  
                    'message'=> '查询成功', 
                    'result'=>$data,
                ];
            }else{
                $reData = [
                    'status' => 0,
                    'message' =>'没有数据',
                ];
            }
        }else{
            $reData = [
                'status' => 0,
                'message' =>'没有数据',
            ];
        }
        
    }else{
        $reData = [
            'status' => 0,
            'message' =>'查询失败',
        ];
    }
   

    $json = json_encode($reData);
    echo $json;
?>