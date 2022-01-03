<?php 
session_start();
if(!isset($_SESSION['user'])){
    header('Location: login.php');
    exit();
}

if($_SESSION['user'] == 'admin' && $_SESSION['role'] == 'admin'){
    require_once("./admin/index.php");
}
else {
    if($_SESSION['role'] == 'manager'){
        require_once("./manager/index.php");
    }else {
        require_once("./user/index.php");
    }
}
?>