<?php
    header("Content-Type: text/html; charset=utf-8");//防止界面乱码
    header('Access-Control-Allow-Origin:*'); // *代表允许任何网址请求
    header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE'); // 允许请求的类型
    header('Access-Control-Allow-Credentials: true'); // 设置是否允许发送 cookies
    header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin');
    //  // 设置允许自定义请求头的字段
    date_default_timezone_set('PRC');
    $mysql_server_name = 'localhost'; //改成自己的mysql数据库服务器
    $mysql_username = 'jianghu1'; //改成自己的mysql数据库用户名
    $mysql_password = 'rainlofty6466'; //改成自己的mysql数据库密码
    $mysql_database = 'jianghu1_liubai'; //改成自己的mysql数据库名
    if('/'==DIRECTORY_SEPARATOR){
        $server_ip=$_SERVER['SERVER_ADDR'];
    }else{
        $server_ip=@gethostbyname($_SERVER['SERVER_NAME']);
    }
    
    $conn=mysqli_connect($mysql_server_name,$mysql_username,$mysql_password,$mysql_database); //连接数据库
    $conn->query("SET NAMES utf8");
    //连接数据库错误提示
    if (mysqli_connect_errno($conn)) { 
        die("连接 MySQL 失败: " . mysqli_connect_error()); 
    }else{
        // echo '链接成功';
    }
   
?>