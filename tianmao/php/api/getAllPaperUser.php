<?php
     include "../config/db.php";
    $body = @file_get_contents('php://input');
    $body=json_decode($body) ;//反序列化
   //status   0 '未沟通'  1'已沟通未面试'   2'面试通过'   3'面试未通过'   4'无效'
    if(isset($body->status)){
        $status = $body->status;
    }else{
        $status = 0;
    }

    $sql = "SELECT * FROM `paper_user` WHERE state='$status' order by score desc";
    
    $result = $conn->query($sql);
    if($result){
        $data = array();
        while ($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
            $data[]=$row;
        }
        $reData = [
            'status'  => 1 ,  
            'message'=> '查询成功', 
            'result'=>$data
        ];
    }else{
        $reData = [
            'status' => 0,
            'message' =>'查询失败',
            'result'=>''
        ];
    }
    $json = json_encode($reData);
    echo $json;
?>