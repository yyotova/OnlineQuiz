<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/DB.php';
include_once '../../models/Role.php';

// Instantiate DB & connect
$database = new DB();
$db = $database->getConnection();

// Instantiate role object
$role = new Role($db);

// Get ID
$role_id = isset($_GET['id']) ? $_GET['id'] : die();

// Get role
$role->getRoleById($role_id);

// Create array
$role_arr = array(
  'id' => $role->getRoleId(),
  'name' => $role->getRoleName(),
);

// Make JSON
print_r(json_encode($role_arr));
?>
