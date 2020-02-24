<?php
 include "../config/db.php";
 $datetime = new DateTime();
 $endtime = $datetime->format('Y-m-d H:i:s');

 $body = @file_get_contents('php://input');//接受整个请求主体
 $body=json_decode($body)  ;//goodid  src title color size  num  openid
 if(isset($body->id)){
    $id = $body->id;

    $selectRe = $conn->query("SELECT *  FROM lucky WHERE id='$id'");
    $prizeId = mysqli_fetch_array($selectRe,MYSQL_ASSOC)['prize'];
    $query = $conn->query("UPDATE lucky SET status=1,endtime='$endtime' WHERE id='$id'");

    if(mysqli_affected_rows($conn) > 0){
        if($prizeId){
            $reminquery = $conn->query("UPDATE prize SET `remin` = `remin`-1 WHERE id='$prizeId'");
        }
        $reData = [
            'status' => 1,
            'message' =>'领取成功',
        ];
    }else{
        $reData = [
            'status' => 0,
            'message' =>'领取失败1',
        ];
    }
 }else{
    $reData = [
        'status' => 0,
        'message' =>'领取失败2',
    ];
 }
 $json = json_encode($reData);
 echo $json;
?>