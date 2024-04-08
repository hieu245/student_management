<?php

require 'model/DepartmentModel.php';
require 'model/CourseModel.php';
require 'model/AccountModel.php';
require 'model/UserModel.php';

//m = ten cua ham nam trong thu muc controller
$m = trim($_GET['m'] ?? 'index'); //ham mac dinh trong controller la index
$m = strtolower($m); //viet thuong tat ca ten ham

switch ($m) 
{
    case 'index':
        index();
        break;
    case 'add':
        Add();
        break;
    case 'handle-add':
        handleAdd();
        break;
    case 'delete':
        handleDelete();
        break;
    case 'edit':
        edit();
        break;
    case 'handle-edit':
        handleEdit();
        break;
default:
index();
break;
}
function handleEdit(){
    if(isset($_POST['btnSave'])){

        $id = trim($_GET['id'] ?? null);
        $id = is_numeric($id) ? $id : 0;
        $info = getDetailAccountById($id);

        $username = trim($_POST['username']??null);
        $username = strip_tags($username);

        $password = trim($_POST['password']??null);
        $password = strip_tags($password);
        
     

        $role_id = trim($_POST['role_id']??0);

        $user_id = trim($_POST['user_id']??0);

        $status = trim($_POST['status']??null);
        $status = $status === '0' || $status === '1' ? $status :0;

      

        //kiem tra thong tin
        $_SESSION['error_update_account'] = [];
        if(empty($username)){
            $_SESSION['error_update_account']['username'] = 'Enter username of account, please';
        } else {
            $_SESSION['error_update_account']['username'] = null;
        }
        
        if(empty($password)){
            $_SESSION['error_update_account']['password'] = 'Enter password of account, please';
        } else {
            $_SESSION['error_update_account']['password'] = null;
        }

        $flagCheckingError = false;
        foreach($_SESSION['error_update_account'] as $error){
            if(!empty($error)){
                $flagCheckingError=true;
                break;

            }
        }


        if(!$flagCheckingError){
            //khong co loi-insert du lieu vao dtb
            if(isset($_SESSION['error_update_account'])){
                unset($_SESSION['error_update_account']);
            }
            $update = updateAccountById($username, $password, $role_id, $user_id, $status, $id);
            if($update){
                //update thanh cong
                header("Location:index.php?c=account&state=success");
            } else{
                header("Location:index.php?c=account&m=edit&id={$id}&state=error");
            }
        } 
        else {
            //co loi, cho quay lai form
            header("Location:index.php?c=account&m=edit&id={$id}&state=failure");
        }
       
    }
}
function edit(){
    // phai dang nhap moi duoc su dung chuc nang nay.
    if(!isLoginUser()){
        header("Location:index.php");
        exit();
    }
    $id = trim($_GET['id'] ?? null);
    $id = is_numeric($id) ? $id : 0; // is_numeric : kiem tra co phai la so hay ko ?
    $info = getDetailAccountById($id); // goi ham trong model
    if(!empty($info)){
        // co du lieu trong database
        // hien thi giao dien - thong tin chi tiet du lieu
        $role = getDataRole();
        $user = getDataUser();


       
        require 'view/account/edit_view.php';
    } else {
        // khong co du lieu trong database
        // thong bao 1 giao dien loi
        require 'view/error_view.php';
    }
}
function handleDelete(){
    if(!isLoginUser())
    {
        header ("Location:index.php");
        exit();
    }

    $id = trim($_GET['id'] ?? null);
    $id = is_numeric($id) ? $id : 0;


    $delete = deleteAccountById($id);
    if($delete){
        //xoa thanh cong
        header("Location:index.php?c=account&state_del=success");
    }
    else{
        //xoa that bai
        header("Location:index.php?c=account&state_del=failure");


    }
}

function handleAdd(){
    if(isset($_POST['btnSave'])){
        $username = trim($_POST['username']??null);
        $username = strip_tags($username);

        $password = trim($_POST['password']??null);
        $password = strip_tags($password);


        $role_id = trim($_POST['role_id']??2);
        
        $user_id = trim($_POST['user_id']??9);

        $status = trim($_POST['status']??null);
        $status = $status === '0' || $status === '1' ? $status :0;

      

        //kiem tra thong tin
        $_SESSION['error_add_account'] = [];
        if(empty($username)){
            $_SESSION['error_add_account']['username'] = 'Enter username of account, please';
        } else {
            $_SESSION['error_add_account']['username'] = null;
        }

        $_SESSION['error_add_account'] = [];
        if(empty($password)){
            $_SESSION['error_add_account']['password'] = 'Enter password of account, please';
        } else {
            $_SESSION['error_add_account']['password'] = null;
        }
        

        $flagCheckingError = false;
        foreach($_SESSION['error_add_account'] as $error){
            if(!empty($error)){
                $flagCheckingError=true;
                break;

            }
        }

        //tien hanh check lai
        if(!$flagCheckingError){
            //tien hanh insert vao dtb
            $insert = addNewAccount($username, $password, $role_id, $user_id, $status);
            if($insert){
                header("Location:index.php?c=account&state=success");
            }
            else{
                header("Location:index.php?c=account&m=add&state=error");

            }
        }
        else{
            //thong bao loi cho ng dung biet
            header("Location:index.php?c=account&m=add&state=fail");
        }
    }
    
}
function Add(){
    $role = getDataRole();
    $user = getDataUser();
    require 'view/account/add_view.php';
}
function index()
{


   // phai dang nhap moi duoc su dung chuc nang nay.
    if(!isLoginUser()){
        header("Location:index.php");
        exit();
    }
    $keyword = trim($_GET['search'] ?? null);
    $keyword = strip_tags($keyword);
    $page = trim($_GET['page'] ?? null);
    $page = (is_numeric($page) && $page > 0) ? $page : 1;
    $linkPage = createLink([
        'c' => 'account',
        'm' => 'index',
        'page' => '{page}',
        'search' => $keyword
    ]);

    $roleName =[];
    $role = getDataRole();
    foreach($role as $roles){
        $roleName[$roles['id']]=$roles['name'];
    }

    $UserName =[];
    $user = getDataUser();
    foreach($user as $users){
        $UserName[$users['id']]=$users['full_name'];
    }




    $totalItems = getAllDataAccount($keyword); // goi ten ham trong model
    $totalItems = count($totalItems);
    // departments
    $panigate = pagigate($linkPage, $totalItems, $page, $keyword, 2);
    $start = $panigate['start'] ?? 0;
    $accounts = getAllDataAccountByPage($keyword, $start, 2);
    $htmlPage = $panigate['pagination'] ?? null;
    require ('view/account/index_view.php');
}