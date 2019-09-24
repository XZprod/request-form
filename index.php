<?php

require __DIR__ . '/global_utils.php';
require __DIR__ . '/vendor/autoload.php';

$action = $_GET['action'];
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    require 'form.php';
    return;
} else {
    $entityBody = json_decode(file_get_contents('php://input'), true);

    $request = new App\Request($entityBody);
    $request->setSaveStrategy('file');
    $request->save();
    echo json_encode(['result' => 'success']);
    return;
}