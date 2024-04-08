<?php
require 'model/DepartmentModel.php';
require 'model/CourseModel.php';

$m = trim($_GET['m'] ?? 'index'); //hàm mặc định trong controller tên là index
$m = strtolower($m); // vietes thường tất cả các tên hàm 
switch ($m) {
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
function edit()
{
    $departmentName = [];
    $department = getAllDataDepartments();
    foreach ($department as $departments) {
        $departmentName[$departments['id']] = $departments['name'];
    }
    if (!isLoginUser()) {
        header("Location:index.php");
        exit();
    }
    $id = trim($_GET['id'] ?? null);
    $id = is_numeric($id) ? $id : 0; //is_numberic : kiem tra co phai la so khong
    $info = getDetailCourseById($id); //goi ham trong model
    if (!empty($info)) {
        require 'view/course/edit_view.php';
    } else {
        require 'view/error_view.php';
    }
}
function handleEdit()
{
    if (isset($_POST['btnSave'])) {
        $id = trim($_GET['id'] ?? null);
        $id = is_numeric($id) ? $id : 0;
        $info = getDetailCourseById($id);
        $name = trim($_POST['name'] ?? null);
        $name = strip_tags($name);
        $departmentId = isset($_POST['department_id']) ? intval($_POST['department_id']) : null;
        $status = trim($_POST['status'] ?? null);
        $status = $status === '0' || $status === '1' ? $status : 0;
        $_SESSION['error_update_course'] = [];
        $_SESSION['error_update_course'] = [];
        if (empty($name)) {
            $_SESSION['error_update_course']['name'] = 'Enter name of courses, please !!!';
        } else {
            $_SESSION['error_update_course']['name'] = null;
        }
        if (empty($departmentId)) {
            $_SESSION['error_update_course']['department_id'] = 'Enter department_id of courses, please !!!';
        } else {
            $_SESSION['error_update_course']['department_id'] = null;
        }
        $flagCheckingError = false;
        foreach ($_SESSION['error_update_course'] as $error) {
            if (!empty($error)) {
                $flagCheckingError = true;
                break;
            }
        }
        if (!$flagCheckingError) {
            if (isset($_SESSION['error_update_course'])) {
                unset($_SESSION['error_update_course']);
            }
            $slug = slug_string($name);
            $update = updateCourseById(
                $name,
                $slug,
                $departmentId,
                $status,
                $id
            );
            if ($update) {
                header("Location:index.php?c=course&state=success");
            } else {
                header("Location:index.php?c=course&m=edit&id={$id}&state=error");
            }
        } else {
            header("Location:index.php?c=course&m=edit&id={$id}&state=failure");
        }
    }
}

function handleDelete()
{
    if (!isLoginUser()) {
        header("Location:index.php");
        exit();
    }
    $id = trim($_GET['id'] ?? null);
    $id = is_numeric($id) ? $id : 0;
    $delete = deleteCourseById($id); // goi ten ham trong model
    if ($delete) {
        header("Location:index.php?c=course&state_del=success");
    } else {
        header("Location:index.php?c=course&state_del=failure");
    }
}

function handleAdd()
{
    if (isset($_POST['btnSave'])) {
        $name = trim($_POST['name'] ?? null);
        $name = strip_tags($name);
        $departmentId = isset($_POST['department_id']) ? intval($_POST['department_id']) : null;        $status = trim($_POST['status'] ?? null);
        $status = $status === '0' || $status === '1' ? $status : 0;
        $_SESSION['error_add_course'] = [];
        $_SESSION['error_add_course'] = [];
        if (empty($name)) {
            $_SESSION['error_add_course']['name'] = 'Enter name of courses, please !!!';
        } else {
            $_SESSION['error_add_course']['name'] = null;
        }

        if (empty($departmentId)) {
            $_SESSION['error_add_course']['department_id'] = 'Enter department_id of courses, please !!!';
        } else {
            $_SESSION['error_add_course']['department_id'] = null;
        }
        $flagCheckingError = false;
        foreach ($_SESSION['error_add_course'] as $error) {
            if (!empty($error)) {
                $flagCheckingError = true;
                break;
            }
        }
        if (!$flagCheckingError) {
            $slug = slug_string($name);
            $insert = insertCourse($name, $slug, $departmentId, $status);

            if ($insert) {
                header("Location:index.php?c=course&state=success");
            } else {
                header("Location:index.php?c=course&&m=add&state=error");
            }
        } else {
            header("Location:index.php?c=course&m=add&state=fail");
        }
    }
}

function Add()
{
    $departments = getAllDataDepartments(); // goi tu  department model
    require 'view/course/add_view.php';
}

function index()
{
   //phai dang nhap moi su dung duoc chuc nang nay
   if(!isLoginUser())
   {
       header ("Location:index.php");
       exit();
   }
   $keyword = trim($_GET['search']??null);
   $keyword = strip_tags($keyword);

   $departmentName =[];
   $department = getAllDataDepartments();
   foreach($department as $departments){
       $departmentName[$departments['id']]=$departments['name'];
   }


   $page = trim($_GET['page'] ?? null);
   $page = (is_numeric($page) && $page > 0) ? $page : 1;
   $linkPage = createLink([
       'c' => 'course',
       'm' => 'index',
       'page' => '{page}',
       'search' => $keyword
   ]);
   $totalItems = getAllDataCourses($keyword); // goi ten ham trong model
   $totalItems = count($totalItems);
   // courses
   $panigate = pagigate($linkPage, $totalItems, $page, $keyword, 2);
   $start = $panigate['start'] ?? 0;
   $courses = getAllDataDCoursesByPage($keyword, $start, 4);
   $htmlPage = $panigate['pagination'] ?? null;
   require ('view/course/index_view.php');
}
