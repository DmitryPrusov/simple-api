<?php
require_once('../vendor/autoload.php');
use Models\Storage;
use Config\Database;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type, Access-Control-Allow-Methods, X-Requested-With');


$database = new Database();
$db = $database->connect();
$storage = new Storage($db);
$data = json_decode(file_get_contents("php://input"));
$storage->id  = $data->id;
$storage->value = $data->value;

$storage->setValueToKey();
    echo json_encode([
    'result' => 'success'
]);




