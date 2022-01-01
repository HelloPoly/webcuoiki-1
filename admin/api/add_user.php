<?php 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Headers: *");
require('../../connect_db.php');

// $user = $_POST['user'];
// $fullname = $_POST['fullname'];
// $phongban = $_POST['phongban'];
// $level = $_POST['level'];

$json = file_get_contents('php://input');
$data = json_decode($json);
$user = $data->user;
$fullname = $data->fullname;
$phongban = $data->phongban;
$level = $data->chucvu;

$result = register($user, $user, $fullname, $phongban, $level);
echo json_encode($result);
?>