<?php
if(!function_exists('isLoginUser')){
    function isLoginUser(){
        $idUser = getSessionidUser(); // nam o file common.php
        $username = getSessionUsername();// nam o file common
        if(empty($idUser)||empty($username)){
            return false;
        }
        return true;
    }
}