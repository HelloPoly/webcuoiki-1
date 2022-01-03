<?php
require_once("./connect_db.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Đổi mật khẩu</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <?php
    $error = '';
    $mess = '';
    if (isset($_GET['user'])) {
        $user = $_GET['user'];
    }

    if (isset($_GET['user']) && isset($_GET['token'])) {
        if (strlen($_GET['token']) != 32) {
            $error = 'Invalid token';
        } else if (isset($_POST['newPass']) && isset($_POST['oldPass']) && isset($_POST['newPassAgain'])) {
            $oldPass = $_POST['oldPass'];
            $newPass = $_POST['newPass'];
            $newPassAgain = $_POST['newPassAgain'];
            $token = $_GET['token'];
            $user = $_POST['name'];

            if (empty($user)) {
                $error = 'Không được để trống username';
            } else if (empty($newPass)) {
                $error = 'Không được để trống mật khẩu';
            } else if ($newPass != $newPassAgain) {
                $error = 'Mật khẩu mới không khớp';
            } else {
                $result = change_password($user, $token, $oldPass, $newPass);
                if ($result['code'] == 0) {
                    $mess = "Đổi mật khẩu thành công";
                } else {
                    $error = $result['error'];
                }
            }
        }
    } else {
        $error = 'Invalid username or token';
    }
    ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 border my-5 p-4 rounded mx-3">
                <h3 class="text-center text-secondary mt-2 mb-3 mb-3">Thay đổi mật khẩu</h3>
                <!-- <p class='text-danger'>Vui lòng thay đổi mật khẩu để có thể đăng nhập vào hệ thống</p> -->
                <form method="post" id="change-password">
                    <div class="form-group">
                        <label for="user">Username</label>
                        <input value="<?= $user ?? "" ?>" name="name" required readonly class="form-control" type="text" id="user">
                    </div>
                    <div class="form-group">
                        <label for="newPass">Mật khẩu cũ</label>
                        <input name="oldPass" required class="form-control" type="password" placeholder="Mật khẩu cũ" id="oldPass">
                    </div>
                    <div class="form-group">
                        <label for="newPass">Mật khẩu mới</label>
                        <input name="newPass" required class="form-control" type="password" placeholder="Mật khẩu mới" id="newPass">
                    </div>
                    <div class="form-group">
                        <label for="newPassAgain">Nhập lại mật khẩu mới</label>
                        <input name="newPassAgain" required class="form-control" type="password" placeholder="Nhập lại mật khẩu mới" id="newPassAgain">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary px-5 mr-2">Thay đổi</button>
                    </div>
                    <p id='change-pass-form-alert' class="alert alert-danger d-none"></p>
                </form>

                <?php
                if (!empty($error)) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
                if (!empty($mess)) {
                    echo "<div class='alert alert-success'>
                        $mess
                        <div>Bấm vào <a href='../login.php'>đây</a> để đăng nhập</div>
                    </div>";
                }
                ?>
            </div>
        </div>
    </div>
    <script src="./main.js"></script>
</body>

</html>