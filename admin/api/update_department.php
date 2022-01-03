<?php 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: PATCH");
header('Content-Type: application/json');

require('../../connect_db.php');
$raw = file_get_contents('php://input');
$data = json_decode($raw);
$newCode = $data->newCode;
$newName = $data->newName;
$newDesc = $data->newDesc;
$id = $data->id;

$result = update_department($id, $newName, $newCode, $newDesc);
if($result){
    $arr = ['code'=>0, 'error' => ''];
    die(json_encode($arr));
}else{
    $arr = ['code'=>1, 'error' => 'Chỉnh sửa thông tin không thành công'];
    die(json_encode($arr));
}
?>