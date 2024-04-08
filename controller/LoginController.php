<?php
require 'model/LoginModel.php';// import model

$m = trim($_GET['m']??'index');//hàm mặc định trong controller tên là index
$m = strtolower($m);// viet thường tất cả các tên hàm 
switch($m){
    case 'index':
        index();
    break;
    case 'handle':
        handleLogin();
        break;
    case 'logout':
        handleLogout();
    break;
    default:
        index();
    break;
}
function handleLogout(){
    if(isset($_POST['btnLogout'])){
        //huy cac session 
        session_destroy();
        //quay ve trang dang nhap
        header("Location:index.php");   
    }
}
function handleLogin(){
    if(isset($_POST['btnLogin'])){
        $username = trim($_POST['username']??null);
        $username=strip_tags($username); // strip_tags : xoa cac the html trong chuoi

        $password = trim($_POST['password']??null);
        $password=strip_tags($password); // strip_tags : xoa cac the html trong chuoi

        $userInfo = checkLoginUser($username, $password);
        if(!empty($userInfo)){
            // tai khoan co ton tai
            // luu thon tin nguoi dung vao session
            $_SESSION['username']=$userInfo['username'];
            $_SESSION['fullname']=$userInfo['fullname'];
            $_SESSION['email']=$userInfo['email'];
            $_SESSION['idUser']=$userInfo['user_id'];
            $_SESSION['roleId']=$userInfo['role_id'];
            $_SESSION['idAccount']=$userInfo['id'];
            // cho vao trang quan tri
            header("Location:index.php?c=dashboard");
        }else{
            // tai khoan khong ton tai
            // quay ve trang dang nhap va tbao loi
            header("Location:index.php?state=error");
        }
    }
}
function index(){
    if(isLoginUser()){
        header("Location:index.php?c=dashboard");
        exit();
    }
    require "view/login/index_view.php";
}