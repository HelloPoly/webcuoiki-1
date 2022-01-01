<?php
require_once("connect_db.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kích hoạt tài khoản</title>
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
    $user = $_GET['user'];

    if (isset($_POST['newPass']) && isset($_GET['user']) && isset($_GET['token'])) {
        $newPass = $_POST['newPass'];
        $token = $_GET['token'];
        $user = $_GET['user'];

        if (empty($newPass)) {
            $error = 'Không được để trống mật khẩu';
        } else {
            $result = activateAccount($newPass, $user, $token);
            if ($result['code'] == 0) {
                $mess = "Activate account successful";
            } else {
                $error = $result['error'];
            }
        }
    }
    ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 border my-5 p-4 rounded mx-3">
            <h3 class="text-center text-secondary mt-2 mb-3 mb-3">Kích hoạt tài khoản</h3>
            <p class='text-danger'>Vui lòng thay đổi mật khẩu để có thể đăng nhập vào hệ thống</p>
                <form method="post" action="" novalidate>
                    <div class="form-group">
                        <label for="name">Username</label>
                        <input value="<?= $user ?>" name="name" required readonly class="form-control" type="text" id="name">
                    </div>
                    <div class="form-group">
                        <label for="price">Mật khẩu mới</label>
                        <input name="newPass" required class="form-control" type="password" placeholder="Mật khẩu mới" id="newPass">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary px-5 mr-2">Thay đổi</button>
                    </div>
                </form>

                <?php
                if (!empty($error)) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
                if (!empty($mess)) {
                    echo "<div class='alert alert-success'>
                        $mess
                        <div>Bấm vào <a href='login.php'>đây</a> để đăng nhập</div>
                    </div>";
                }
                ?>
            </div>
        </div>
    </div>

</body>

</html>