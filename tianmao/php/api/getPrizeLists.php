<?php
    include "../config/db.php";
   
    $body = @file_get_contents('php://input');//接受整个请求主体
    $body=json_decode($body)  ;//goodid  src title color size  num  openid
    
    if(isset($body->id)){
        $id = $body->id;
       
        $sql = "SELECT * FROM `prize` WHERE id='$id'";
    }else{
        $sql = "SELECT * FROM `prize`";
    }
    
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