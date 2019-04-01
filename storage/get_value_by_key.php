<?php
require_once('../vendor/autoload.php');
use Models\Storage;
use Config\Database;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$database = new Database();
$db = $database->connect();
$storage = new Storage($db);
$storage->id = isset($_GET['id']) ?  $_GET['id'] : exit;

$storage->getValueByKey();
if ($storage->value) {
    $arr = [
        'value' => $storage->value
    ];
    echo json_encode($arr);
} else  {
    http_response_code(404);
    echo json_encode([
        'error' => 'not found'
    ]);
}
