<?php
    include "../config/db.php";
   
    $body = @file_get_contents('php://input');//接受整个请求主体
    $body=json_decode($body)  ;//goodid  src title color size  num  openid
    /*
    name:this.name,
    phone:this.phone,
    chat:this.chat, 
    ruleForm:this.ruleForm
    */
    /*$ruleForm ['one'] = 'A';
    $ruleForm ['two'] = 'C';
    $ruleForm ['three'] = 'B';
    $ruleForm ['four'] = 'D';
    $ruleForm ['five'] = 'B';
    $ruleForm ['six'] = 'A';
    $ruleForm ['seven'] = 'C';
    $ruleForm ['eight'] = 'D';
    $ruleForm ['nine'] = 'B';
    $ruleForm ['ten'] = 'C';*/
    if(isset($body->name) && isset($body->phone) && isset($body->chat) && isset($body->ruleForm)){
        $name = $body->name;
        $phone = $body->phone;
        $chat = $body->chat;
        $ruleForm = $body->ruleForm;

        $selectRe = $conn->query("SELECT *  FROM paper_user WHERE phone='$phone'");
        $reLen = mysqli_num_rows($selectRe);
        if($reLen > 0){
            $reData = [
                'status'  => 0,     
                'message'  => '您已经提交过',     
            ];
        }else{
            $score_sum = 0;
            //计算分数
            $sql = "SELECT * FROM `paper_problems`";
            $score_result = $conn->query($sql);
            if($score_result){
                while ($score_row = mysqli_fetch_array($score_result,MYSQL_ASSOC)){
                    $score = 0;
                    $stag1 = $score_row['tag'];//'one'
                    switch ($stag1){
                        case 'one':
                            $stag = $ruleForm->one;
                        break;
                        case 'two':
                            $stag = $ruleForm->two;
                        break;
                        case 'three':
                            $stag = $ruleForm->three;
                        break;
                        case 'four':
                            $stag = $ruleForm->four;
                        break;
                        case 'five':
                            $stag = $ruleForm->five;
                        break;
                        case 'six':
                            $stag = $ruleForm->six;
                        break;
                        case 'seven':
                            $stag = $ruleForm->seven;
                        break;
                        case 'eight':
                            $stag = $ruleForm->eight;
                        break;
                        case 'nine':
                            $stag = $ruleForm->nine;
                        break;
                        case 'ten':
                            $stag = $ruleForm->ten;
                        break;
                    }
                   
                    if($stag == 'A'){
                        $score = $score_row['score_A'];
                    }else if($stag == 'B'){
                        $score = $score_row['score_B'];
                    }else if($stag == 'C'){
                        $score = $score_row['score_C'];
                    }else if($stag == 'D'){
                        $score = $score_row['score_D'];
                    }
                    $score_sum+=$score;
                }
            }
           
            //填表
           $date = date("Y-m-d");
           $sql = "INSERT INTO `paper_user`( `name`,`phone`,`chat`,`score`,`date`) VALUES ('$name','$phone','$chat','$score_sum','$date')";
           $query =  $conn->query($sql);
           if($query){
                $reData = [
                    'status'  => 1 ,  
                    'message'=> '修改成功', 
                    'score'=> $score_sum, 
                ];
            }else{
                $reData = [
                    'status' => 0,
                    'message' =>'修改失败q',
                ];
            }
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