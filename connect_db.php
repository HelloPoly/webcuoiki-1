<?php
function connection()
{
    $host = 'localhost';
    $root = 'root';
    $password = '';
    $db = 'webcuoiki';

    $conn = new mysqli($host, $root, $password, $db);
    if ($conn->connect_error) {
        die('Connect error: ' . $conn->connect_error);
    }
    return $conn;
}

function login($user, $pass)
{
    $sql = "select * from account where username = ?";
    $conn = connection();

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $user);
    if (!$stmt->execute()) {
        return array('code' => 1, 'error' => 'Excute command failed');
    }

    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        return array('code' => 2, 'error' => 'Username does not exists');
    }

    $data = $result->fetch_assoc();
    $hashed_pw = $data['password'];
    if (!password_verify($pass, $hashed_pw)) {
        return array('code' => 3, 'error' => 'Password is invalid');
    } else if ($data['activated'] == 0) {
        return array('code' => 4, 'error' => 'Account is not activated', 'data' => $data);
    } else {
        return array('code' => 0, 'error' => '', 'data' => $data);
    }
}

// kiểm tra user đã tồn tại hay chưa
function user_exists($user)
{
    $sql = 'select * from account where username = ?';
    $conn = connection();

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $user);
    if (!$stmt->execute()) {
        return null;
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return true;
    }
    return false;
}

//tham số: username, pass, fullname, phòng ban, chức vụ
function register($user, $pass, $name, $phongban, $level, $birthday, $gender)
{
    if (user_exists($user)) {
        return array('code' => 1, 'error' => 'Username is already exists');
    }

    $hashed_pw = password_hash($pass, PASSWORD_DEFAULT);

    //token ngẫu nhiên
    $random_num = random_int(0, 1000);
    $token = md5($user . '+' . $random_num);

    $sql = 'insert into account(username, password, birthday, gender, 
             level, active_token, fullname, phongban)
            values(?, ?, ?, ?, ?, ?, ?, ?)';
    $conn = connection();

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssisss', $user, $hashed_pw, $birthday, $gender, $level, $token, $name, $phongban);
    if (!$stmt->execute()) {
        return array('code' => 2, 'error' => 'Excute command failled');
    }
    return array('code' => 0, 'error' => '');
}

// active account 
function activateAccount($newPass, $user, $token)
{

    $sql = 'select * from account where username = ? and active_token = ? and activated = 0';
    $conn = connection();

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $user, $token);
    if (!$stmt->execute()) {
        return array('code' => 1, 'error' => 'Excute command failled');
    }

    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        return array('code' => 2, 'error' => 'Username or token not found');
    }

    $data = $result->fetch_assoc();
    $cur_hashed_pw = $data['password'];
    if (password_verify($newPass, $cur_hashed_pw)) {
        return array('code' => 3, 'error' => 'Không sử dụng mật khẩu cũ');
    }

    $hashed_pw = password_hash($newPass, PASSWORD_DEFAULT);
    $sql = "update account set activated=1, active_token='', password = ? where username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $hashed_pw, $user);
    if (!$stmt->execute()) {
        return array('code' => 1, 'error' => 'Excute command failled');
    }
    return array('code' => 0, 'error' => '');
}

function get_users()
{
    $sql = "select * from account where username != 'admin'";
    $conn = connection();

    $result = $conn->query($sql);
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return array('code' => 0, 'error' => '', 'data' => $data);
}

function get_user($id)
{
    $sql = 'select * from account where eid = ?';
    $conn = connection();

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        return array('code' => 1, 'error' => 'Excute command failled');
    }
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    return array('code' => 0, 'error' => '', 'data' => $data);
}

function get_departments()
{
    $sql = 'select * from department';
    $conn = connection();

    $result = $conn->query($sql);
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return array('code' => 0, 'error' => '', 'data' => $data);
}

function get_department($id)
{
    $sql = 'select * from department where id = ?';
    $conn = connection();

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        return array('code' => 1, 'error' => 'Excute command failled');
    }

    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        return array('code' => 2, 'error' => 'Id không hợp lệ');
    }
    $data = $result->fetch_assoc();
    return array('code' => 0, 'error' => '', 'data' => $data);
}

function name_exists($name){
    $sql = 'select * from department where name = ?';
    $conn = connection();

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $name);
    if (!$stmt->execute()) {
        return null;
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return true;
    }
    return false;
}

function code_exists($code){
    $sql = 'select * from department where ma_so = ?';
    $conn = connection();

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $code);
    if (!$stmt->execute()) {
        return null;
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return true;
    }
    return false;
}

function add_deparmement($name, $code, $desc){
    if(name_exists($name)){
        return array('code' => 1, 'error' => 'Phòng ban này đã tồn tại');
    }
    
    if(code_exists($code)){
        return array('code' => 2, 'error' => 'Mã số này đã được sử dụng');
    }

    $sql = 'insert into department(name, ma_so, description)
            values(?, ?, ?)';
    $conn = connection();

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sis', $name, $code, $desc);
    if (!$stmt->execute()) {
        return array('code' => 3, 'error' => 'Excute command failled');
    }
    return array('code' => 0, 'error' => '');
}

function update_department($id, $name, $code, $desc){
    $sql = "UPDATE department SET name = ?, ma_so = ?, description = ? WHERE id = ?";
    $conn = connection();
    $stm = $conn->prepare($sql);
    $stm->bind_param('sisi', $name, $code, $desc, $id);
    $stm->execute();
    return $stm->affected_rows == 1;
}

function reset_default_password($id, $username)
{
    //token ngẫu nhiên
    $random_num = random_int(0, 1000);
    $token = md5($username . '+' . $random_num);
    $hashed_pw = password_hash($username, PASSWORD_DEFAULT);

    $sql = 'update account set activated=0, password = ?, active_token=? where eid = ? and username = ?';
    $conn = connection();
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssis', $hashed_pw, $token, $id, $username);

    if (!$stmt->execute()) {
        return array('code' => 1, 'error' => 'Excute command failled');
    }

    if ($stmt->affected_rows === 0) {
        return array('code' => 2, 'error' => "Không thể reset password");
    }
    return array('code' => 0, 'error' => '');
}

function change_password($user, $token, $oldPass, $newPass)
{
    $sql = 'select * from account where username = ? and password_token = ?';
    $conn = connection();

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $user, $token);
    if (!$stmt->execute()) {
        return array('code' => 1, 'error' => 'Excute command failled');
    }

    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        return array('code' => 2, 'error' => 'Username or token invalid');
    }

    $data = $result->fetch_assoc();
    $cur_hashed_pw = $data['password'];
    // kiểm tra mật khẩu cũ có khớp k
    if (!password_verify($oldPass, $cur_hashed_pw)) {
        return array('code' => 3, 'error' => 'Mật khẩu cũ không đúng');
    }

    if (password_verify($newPass, $cur_hashed_pw)) {
        return array('code' => 4, 'error' => 'Không sử dụng mật khẩu cũ');
    }

    $hashed_new_pw = password_hash($newPass, PASSWORD_DEFAULT);
    //token ngẫu nhiên
    $random_num = random_int(0, 1000);
    $new_pass_token = md5($user . '+' . $random_num);

    $sql = 'update account set password = ?, password_token = ?';
    $conn = connection();

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $hashed_new_pw, $new_pass_token);
    if (!$stmt->execute()) {
        return array('code' => 2, 'error' => 'Excute command failled');
    }
    return array('code' => 0, 'error' => '');
}
