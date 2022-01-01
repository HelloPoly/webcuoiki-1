<?php 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
require_once('../../connect_db.php');

$json = file_get_contents('php://input');
$data = json_decode($json);
$id = $data->id;

$data = get_user($id);
echo json_encode(array('code'=>0, 'message'=>'Get products successful', 'data'=>$data['data']));
?>