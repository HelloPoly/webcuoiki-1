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
function register($user, $pass, $name, $phongban, $level)
{
    if (user_exists($user)) {
        return array('code' => 1, 'error' => 'Username is already exists');
    }

    $hashed_pw = password_hash($pass, PASSWORD_DEFAULT);

    //token ngẫu nhiên
    $random_num = random_int(0, 1000);
    $token = md5($user . '+' . $random_num);

    $sql = 'insert into account(username, password, 
             level, active_token, fullname, phongban)
            values(?, ?, ?, ?, ?, ?)';
    $conn = connection();

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssisss', $user, $hashed_pw, $level, $token, $name, $phongban);
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
    $sql = 'select * from account';
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

    if ($stmt->affected_rows===0) {
        return array('code'=>2, 'error'=>"Không thể reset password");
    }
    return array('code'=>0, 'error'=>'');
}
