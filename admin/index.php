<?php
// session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lí nhân viên</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous" />
    <link rel="stylesheet" href="./home.css">
</head>

<body>
    <div class="container">
        <!-- // navbar include -->
        <?php include("navbar.php") ?>

        <div class="container mt-2 col-8">
            <h2 class="mt-3 mb-3">Danh sách nhân viên</h2>
            <button onclick="handleAddUser()" class="btn btn-primary" data-toggle="modal" data-target="#confirm-add-user">
                Thêm nhân viên
            </button>

            <table class="table table-hover mt-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Phòng ban</th>
                        <th>Chức vụ</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>



    <div id="reset-password-confirm" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Reset mật khẩu</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Bạn có muốn reset mật khẩu về mặc định
                            <strong id="reset-name"></strong> ?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button id="close-button" type="button" class="btn btn-primary" data-dismiss="modal">
                            Thoát
                        </button>
                        <button id="reset-button" type="button" class="btn btn-danger">
                            Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="show-user-info" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="card">
                    <img src="../src/img/beard.png" class="card-img-top mx-auto" alt="avatar" style="max-width: 100px;" />
                    <div class="card-body">
                        <h5 class="card-title">Thông tin nhân viên</h5>
                        <p class="card-text">
                            Mã số: <span class='eid'></span>
                        </p>
                        <p class="card-text">
                            Họ và tên: <span class='name'></span>
                        </p>
                        <p class="card-text">
                            Ngày sinh: <span class='birthday'></span>
                        </p>
                        <p class="card-text">
                            Giới tính: <span class='gender'></span>
                        </p>
                        <p class="card-text">
                            Chức vụ: <span class='role'></span>
                        </p>
                        <p class="card-text">
                            Phòng ban: <span class='department'></span>
                        </p>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">
                            Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="add-show-mess" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <hp class="modal-title">Thông báo</hp>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p id='mess'></p>
                </div>
            </div>
        </div>
    </div>

    <div id="confirm-add-user" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="add-user-form" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm nhân viên mới</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="full-name">Họ và tên</label>
                            <input type="text" name="full-name" class="form-control" id="full-name" require />
                        </div>
                        <div class="form-group">
                            <label for="user-name">Username</label>
                            <input type="text" name="user-name" class="form-control" id="user-name" require />
                        </div>
                        <div class="form-group">
                            <label for="user-birthday">Ngày sinh</label>
                            <input type="date" name="user-birthday" min="01-01-1950" class="form-control" id="user-birthday" require />
                        </div>
                        <div class="form-group">
                            <label for="user-gender">Giới tính</label>
                            <select class="form-control" name="user-gender" id="user-gender">
                                <option value="nam">Nam</option>
                                <option value="nữ">Nữ</option>
                                <option value="khác">Khác</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="phongban">Phòng ban</label>
                            <select class="form-control" name="phongban" id="phongban">
                                <option value='kế toán'>Kế toán</option>
                                <option value='marketing'>Marketing</option>
                                <option value='it'>IT</option>
                                <option value='nhân sự'>Nhân sự</option>
                                <option value='thống kê'>Thống kê</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="chucvu">Chức vụ</label>
                            <select class="form-control" name="chucvu" id="chucvu">
                                <option value=2>Nhân viên</option>
                                <option value=1>Trưởng phòng</option>
                            </select>
                        </div>


                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary exit" data-dismiss="modal">
                                Thoát
                            </button>
                            <button type="submit" class="btn btn-success">Thêm</button>
                        </div>
                        <p id='add-form-alert' class="alert alert-danger text-center d-none">Vui lòng điền thông tin đầy
                            đủ, không sử dụng các kí tự như ~,.-+= ...</p>
                </form>
            </div>
        </div>
    </div>

    <script src='./main.js'></script>
</body>

</html>