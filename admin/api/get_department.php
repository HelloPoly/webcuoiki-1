<?php 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
require_once('../../connect_db.php');

$json = file_get_contents('php://input');
$data = json_decode($json);
$id = $data->id;

$data = get_department($id);
if($data['code'] == 0){
    echo json_encode(array('code'=>0, 'message'=>'Get department successful', 'data'=>$data['data']));
}else{
    echo json_encode(array('code'=> $data['code'], 'message'=> $data['error']));
}
?>