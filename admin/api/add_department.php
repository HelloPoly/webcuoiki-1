<?php 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Headers: *");
require('../../connect_db.php');

$json = file_get_contents('php://input');
$data = json_decode($json);

$name = $data->name;
$code = $data->code;
$desc = $data->desc;

$result = add_deparmement($name, $code, $desc);
echo json_encode($result);
