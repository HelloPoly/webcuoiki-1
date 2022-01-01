<?php
session_start();
require_once('connect_db.php');

// kiểm tra session, nếu có thì chuyển về home
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

$user = "";
$pass = "";
$error = "";

// xử lí login
if (isset($_POST['user']) && isset($_POST['pass'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    if (empty($user)) {
        $error = 'Vui lòng nhập tên đăng nhập';
    } else if (empty($pass)) {
        $error = 'Vui lòng nhập mật khẩu';
    } else {
        $result = login($user, $pass);
        if ($result['code'] == 0) {
            $data = $result['data'];
            $_SESSION['user'] = $user;
            $_SESSION['name'] = $name;
            
            header("Location: index.php");
            exit();
        } else if ($result['code'] == 4) {
            $data = $result['data'];
            $token = $data['active_token'];
            header("Location: active.php?user=$user&token=$token");
        } else {
            $error = $result['error'];
        }
    }
}
