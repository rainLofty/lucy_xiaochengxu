<?php
     include "../config/db.php";

    $sql = "SELECT * FROM `paper_problems` order by id asc";
    
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