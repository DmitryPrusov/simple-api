<?php
require_once('../vendor/autoload.php');
use Models\Storage;
use Config\Database;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$database = new Database();
$db = $database->connect();
$storage = new Storage($db);
$result = $storage->getAllValues();
$num = $result->rowCount();

if ($num > 0) {
    $arr = [];
    $arr['storage'] = [];
    while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
        extract($row);
        $storage_item = [
            'id' => $id,
            'value' => $value
        ];
        $arr['storage'][] = $storage_item;
    }
    echo json_encode($arr);
} else {
    http_response_code(404);
    echo json_encode([
        'error' => 'No data'
    ]);
}







