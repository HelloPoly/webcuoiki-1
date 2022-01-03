<?php
session_start();
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
    <title>Danh sách phòng ban</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous" />
</head>

<body>
    <div class="container mt-2">
        <h2 class="mt-3 mb-3">Danh sách phòng ban</h2>
        <button onclick="handleAddDepartment()" class="btn btn-primary" data-toggle="modal" data-target="#confirm-add-department">
            Thêm phòng ban
        </button>
        <table class="table table-hover mt-4">
            <thead>
                <tr>
                    <th>Mã số</th>
                    <th>Tên phòng ban</th>
                    <th>Trưởng phòng</th>
                    <th>Mô tả</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- cập nhật dialog -->
    <div class="modal fade" id="edit-department-dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Chỉnh sửa thông tin phòng ban</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id='edit-department-form'>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit-name">Tên phòng ban</label>
                            <input type="text" placeholder="Tên phòng ban" class="form-control" id="edit-name" required />
                        </div>
                        <div class="form-group">
                            <label for="edit-code">Mã số phòng ban</label>
                            <input type="number" placeholder="Mã số phòng ban" class="form-control" id="edit-code" required />
                        </div>
                        <div class="form-group">
                            <label for="edit-desc">Mô tả</label>
                            <textarea rows="4" id="edit-desc" class="form-control" placeholder="Mô tả" required></textarea>
                        </div>
                    </div>
                    <p id='edit-form-alert' class="alert alert-danger text-center d-none">Vui lòng điền thông tin đầy
                            đủ, không sử dụng các kí tự như ~,.-+= ...</p>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger exit" data-dismiss="modal">Thoát</button>
                        <button type="sumit" id="update-department-btn" class="btn btn-success">Sửa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="show-department-info" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin phòng ban</h5>
                        <p class="card-text">
                            Tên phòng ban: <span class='department-name'></span>
                        </p>
                        <p class="card-text">
                            Mã số: <span class='department-code'></span>
                        </p>
                        <p class="card-text">
                            Trưởng phòng: <span class='manager'></span>
                        </p>
                        <p class="card-text">
                            Mô tả: <span class='description'></span>
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

    <div id="confirm-add-department" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="add-department-form" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm phòng ban</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="department-name">Tên phòng ban</label>
                            <input type="text" name="department-name" class="form-control" id="department-name" require />
                        </div>
                        <div class="form-group">
                            <label for="department-code">Mã số phòng ban</label>
                            <input type="number" min="1" name="department-code" class="form-control" id="department-code" require />
                        </div>
                        <div class="form-group">
                            <label for="desc">Mô tả</label>
                            <textarea id="desc" name="desc" rows="4" class="form-control" placeholder="Mô tả"></textarea>
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


    <script src="../main.js"></script>
</body>

</html>