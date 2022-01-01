<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
require_once '../../connect_db.php';
$data = get_users();
echo json_encode(array('code'=>0, 'message'=>'Get products successful', 'data'=>$data['data']));