<?php
    include "../config/db.php";
   
    $body = @file_get_contents('php://input');//接受整个请求主体
    $body=json_decode($body)  ;//goodid  src title color size  num  openid
    
    if(isset($body->id) && isset($body->status) && isset($body->notes)){
        $id = $body->id;
        $status = $body->status;
        $notes = $body->notes;
       
        $query = $conn->query("UPDATE paper_user SET `state`='$status',`notes`='$notes' WHERE id='$id'");
        if(mysqli_affected_rows($conn) > 0){
            $reData = [
                'status'  => 1 ,  
                'message'=> '修改成功', 
            ];
        }else{
            $reData = [
                'status' => 0,
                'message' =>'修改失败',
            ];
        }
    }else{
        $reData = [
            'status' => 0,
            'message' =>'修改失败',
        ];
    }
    
    $json = json_encode($reData);
    echo $json;
?>