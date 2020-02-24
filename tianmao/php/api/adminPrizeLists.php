<?php
    include "../config/db.php";
   
    $sql = "SELECT * FROM `prize`";
    $result = $conn->query($sql);
    if($result){
        $data = array();
        while ($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
            $prizeId = $row['id'];
            // $searchRows = mysqli_query("SELECT  * FROM `lucky` WHERE prize='$prizeId' and status=0");
            $searchRows = mysqli_query($conn, "SELECT  * FROM `lucky` WHERE prize='$prizeId' and status=0");
            $num_rows=mysqli_num_rows($searchRows);


            // die();
            // list($row_num) = $result->fetch_row(); 
            $temp = [
                'id'  => $row['id'], 
                'title'  => $row['title'], 
                'src'  => $row['src'], 
                'remin'  => $row['remin'], 
                'level'  => $row['level'], 
                'retain'  => $num_rows, 
            ];
            $data[]=$temp;
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