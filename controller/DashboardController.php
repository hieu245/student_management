<?php
$m = trim($_GET['m']??'index');//hàm mặc định trong controller tên là index
$m = strtolower($m);// viet thường tất cả các tên hàm 
switch($m){
    case 'index':
        index();
        break;
    default:
    index();
    break;
}
function index(){
    // phai dang nhap moi doc su dung chuc nang nay
    if(!isLoginUser()){
        header("Location:index.php");
        exit();
    }
    require 'view/dashboard/index_view.php';
}
// function index(){
//     require 'view/dashboard/index_view.php';
// }