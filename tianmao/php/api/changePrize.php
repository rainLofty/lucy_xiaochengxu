<?php
    include "../config/db.php";
   
    $body = @file_get_contents('php://input');//接受整个请求主体
    $body=json_decode($body)  ;//goodid  src title color size  num  openid
    
    if(isset($body->id) && isset($body->title) && isset($body->level) && isset($body->remin)){
        $id = $body->id;
        $title = $body->title;
        $level = $body->level;
        $remin = $body->remin;
       
        $query = $conn->query("UPDATE prize SET `title`='$title',`level`='$level',`remin`='$remin' WHERE id='$id'");
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