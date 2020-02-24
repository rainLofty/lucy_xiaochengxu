<?php
     include "../config/db.php";
    $body = @file_get_contents('php://input');
    $body=json_decode($body) ;//反序列化
   //status 0 未领取  1已领取  2全部
   //type:nickName 搜索的是用户名  code是搜索的是兑换码
   //contentstr：搜索的内容
    if(isset($body->status)){
        $status = $body->status;
    }else{
        $status = 0;
    }
    if(isset($body->type) && isset($body->contentstr)){
        $type = $body->type;
        $contentstr = $body->contentstr;
        if($status == 2){
            $sql = "SELECT * FROM `lucky` order by id desc";
        }else{
            if($type && $contentstr){//有搜索内容
                if($type == 'nickName'){
                     $sql = "SELECT * FROM `lucky` WHERE status='$status' and nickName='$contentstr' order by id desc";
                }else if($type == 'code'){
                    $sql = "SELECT * FROM `lucky` WHERE status='$status' and code='$contentstr' order by id desc";
                }
               
    
            }else{//没有搜索内容
                $sql = "SELECT * FROM `lucky` WHERE status='$status' order by id desc";
            }
        }
       
    }
    
    $result = $conn->query($sql);
    if($result){
        $data = array();
        while ($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
            $temp = [
                'nickName'  => $row['nickName'],   
                'avatarUrl'  => $row['avatarUrl'],   
                'starttime'  => $row['starttime'],   
                'code'  => $row['code'],   
                'endtime'  => $row['endtime'],   
                'status'  => $row['status'],   
                'id'  => $row['id'],   
            ];
            $prizeId =$row['prize']; 
            if($prizeId){
                $prizeResult = $conn->query("SELECT  * FROM `prize` where id='$prizeId'");
                $filterresult =  mysqli_fetch_array($prizeResult,MYSQL_ASSOC);
                if($filterresult){
                    $temp['title'] = $filterresult['title'];
                    $temp['src'] = $filterresult['src'];
                }
            }  
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