<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/DB.php';

// Instantiate DB & connect
$database = new DB();
$db = $database->getConnection();

// Get raw quiz data
$data = json_decode(file_get_contents('php://input'), true);

