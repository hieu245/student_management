<?php
//ket noi csdl
//su dung thu vien PDO de lam viec voi dtb(MYSQL)


//ham knoi toi dtb

function connectionDb()
{
    try {
        $dbh = new PDO('mysql:host=localhost;dbname=students_manager; charset=utf8','root','');
        return $dbh;
    } catch (PDOException $e) {
        // attempt to retry the connection after some timeout for example
        echo("Can't connect to database");
        print_r($e);
        die();
    }
}

//ham ngat knoi voi dtb

function disconnectDb($connection)
{
$connection=null;
}