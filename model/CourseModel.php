    <?php
    require_once "database/database.php";



    function updateCourseById( $name,$slug,$departmentId,$status, $id ){
        $checkUpdate=false;
        $db = connectionDb();
        $sql="UPDATE `courses` SET `name` = :nameCourse, `slug` = :slug, `department_id`=:departmentId ,`status` = :statusCourse, `updated_at` = :updated_at 
        WHERE `id` = :id AND `deleted_at` IS NULL";
        $updateTime = date('Y-m-d H:i:s');
        $stmt =$db->prepare($sql);
        if($stmt){
            $stmt->bindParam(':nameCourse', $name, PDO::PARAM_STR);
            $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
            $stmt->bindParam(':departmentId', $departmentId, PDO::PARAM_INT);
            $stmt->bindParam(':statusCourse', $status, PDO::PARAM_INT);
            $stmt->bindParam(':updated_at', $updateTime, PDO::PARAM_STR);
            $stmt->bindParam(':id',$id, PDO::PARAM_INT);
            if($stmt->execute()){
                $checkUpdate=true;
            }
        }
        return $checkUpdate;
    }

    function getDetailCourseById($id=0){
        $sql = "SELECT*FROM `courses`WHERE `id` =:id AND `deleted_at` IS NULL";
        $db =connectionDb();
        $data=[];
        $stmt =$db->prepare($sql);
        if($stmt){
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            if($stmt->execute()){
                if($stmt->rowCount()>0){
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                }            
            }
        }
        disconnectDb($db);
        return $data;
    }
    function deleteCourseById($id=0){
        $sql = "UPDATE `courses` SET `deleted_at`=:deleted_at WHERE `id`=:id";
        $db=connectionDb();
        $checkDelete=false;
        $deleteTime =date("Y-m-d H:i:s");
        $stmt=$db->prepare($sql);
        if($stmt){
            $stmt->bindParam(':deleted_at', $deleteTime, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            if($stmt->execute()){
                $checkDelete=true;
            }
        }
        disconnectDb($db);
        return $checkDelete;
    }


    function getAllDataDCoursesByPage($keyword = null, $start = 0, $limit = 4){
        $key = "%{$keyword}%";
        $sql = "SELECT * FROM `courses` WHERE (`name` LIKE :nameCourse ) AND `deleted_at` IS NULL  LIMIT :startData, :limitData";

      
        $db = connectionDb();
        $stmt = $db->prepare($sql);
        $data = [];
        if($stmt){
            $stmt->bindParam(':nameCourse', $key, PDO::PARAM_STR);
            
            $stmt->bindParam(':startData', $start, PDO::PARAM_INT);
            $stmt->bindParam(':limitData', $limit, PDO::PARAM_INT);
            if($stmt->execute()){
                if($stmt->rowCount() > 0){
                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            }
        }
        disconnectDb($db);
        return $data;
    }

    
    function insertCourse($name, $slug, $departmentId, $status){
        $sqlInsert = "INSERT INTO courses(`name`, `slug`, `department_id`, `status`, `created_at`) 
        VALUES (:nameCourse, :slug, :departmentId, :statusCourse, :createdAt )";
        $checkInsert = false;
            $db = connectionDb();
            $stmt =$db->prepare($sqlInsert);
            $currentDate=date('Y-m-d H:i:s');
            if($stmt){
                $stmt->bindParam(':nameCourse', $name, PDO::PARAM_STR);
                $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
                $stmt->bindParam(':departmentId', $departmentId, PDO::PARAM_INT);
                $stmt->bindParam(':statusCourse', $status, PDO::PARAM_INT);
                $stmt->bindParam(':createdAt', $currentDate, PDO::PARAM_STR);
                if($stmt->execute()){
                    $checkInsert=true;
                }
            }
            disconnectDb($db);
            return $checkInsert;
    }

    // function getAllDataCourses($keyword = null, $start =0, $limit = 2){
        
    //     $key = "%{$keyword}%";
    //     $sql = "SELECT * FROM `courses` WHERE  (`name` LIKE :nameCourse ) AND  `deleted_at` IS NULL";
     
    //     $db = connectionDb();
    //     $stmt =$db ->prepare($sql);
    //     $data=[];
    //     if($stmt){
    //         $stmt->bindParam(':nameCourse',$key,PDO::PARAM_STR);
            
    //         if($stmt->execute()){
    //         if($stmt->rowCount() >0){
    //             $data =$stmt ->fetchAll(PDO::FETCH_ASSOC);
    //         }
    //         }
    //     }
    //     disconnectDb($db);
    //     return $data;
    // }

 


    function getAllDataCourses($keyword = null, $start =0, $limit = 2){
    
        $key = "%{$keyword}%";
        $sql = "SELECT * FROM `courses` WHERE  (`name` LIKE :nameCourse ) AND  `deleted_at` IS NULL";
        $db = connectionDb();
        $stmt =$db ->prepare($sql);
        $data=[];
        if($stmt){
            $stmt->bindParam(':nameCourse',$key,PDO::PARAM_STR);
            if($stmt->execute()){
               if($stmt->rowCount() >0){
                $data =$stmt ->fetchAll(PDO::FETCH_ASSOC);
               }
            }
        }
        disconnectDb($db);
        return $data;
    }





    
  
    

