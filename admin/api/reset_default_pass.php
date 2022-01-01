<?php 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Headers: *");
require_once('../../connect_db.php');

$json = file_get_contents('php://input');
$data = json_decode($json);
$id = $data->id;
$username = $data->username;

$data = reset_default_password($id, $username);
echo json_encode($data);

?>