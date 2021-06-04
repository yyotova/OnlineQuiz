<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/DB.php';
include_once '../../models/Level.php';

// Instantiate DB & connect
$database = new DB();
$db = $database->getConnection();

// Instantiate level object
$level = new Level($db);

// Get ID
$level->id = isset($_GET['id']) ? $_GET['id'] : die();

// Get level
$level->getLevelById();

// Create array
$level_arr = array(
    'id' => $level->id,
    'name' => $level->name,
);

// Make JSON
print_r(json_encode($level_arr));
