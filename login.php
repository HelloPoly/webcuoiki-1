<?php
require_once("login_process.php"); 
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
        <form class="form" id="login" method="POST" action="" >
            <h1 class="form_title">ADMIN</h1>
            <div class="form_input-group">
                <input type="text" class="form_input" name="user" value="<?= $user ?>"autofocus placeholder="Username or email">
            </div>  
            <div class="form_input-group">
                <input type="password" class="form_input" name="pass" value="<?= $pass ?>" autofocus placeholder="Password">
                <div class="form_input-error-message"></div>
            </div>
            <input  type="submit" class="form_button" value="Login" name="submit">
            <span class="message-error" style="color: #cc3333"><?= $error ?></span>
        </form>
    </div>
    <!-- <script src="./src/main.js"></script> -->
</body>