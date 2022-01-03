<?php
session_start();
require_once('connect_db.php');

// kiểm tra session, nếu có thì chuyển về home
if (isset($_SESSION['user']) && isset($_SESSION['role'])) {
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
            // $_SESSION['name'] = $name;
            $_SESSION['user'] = $user;

            if ($data['level'] == 0) {
                $_SESSION['role'] = 'admin';
            } else if ($data['level'] == 1) {
                $_SESSION['role'] = 'manager';
            } else {
                $_SESSION['role'] = 'user';
            }
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
?>

<!DOCTYPE html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <title>ADMIN</title>
    <!-- <link rel="shortcut icon" href="/assets/favicon.ico"> -->
    <link rel="stylesheet" href="./src/css/style.css">
</head>

<body class="bg_login">
    <div class="container">
        <form class="form" id="login" method="POST" action="">
            <h1 class="form_title">ADMIN</h1>
            <div class="form_input-group">
                <input type="text" class="form_input" name="user" value="<?= $user ?>" autofocus placeholder="Username or email">
            </div>
            <div class="form_input-group">
                <input type="password" class="form_input" name="pass" value="<?= $pass ?>" autofocus placeholder="Password">
                <div class="form_input-error-message"></div>
            </div>
            <input type="submit" class="form_button" value="Login" name="submit">
            <span class="message-error" style="color: #cc3333"><?= $error ?></span>
        </form>
    </div>
    <!-- <script src="./src/main.js"></script> -->
</body>