<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/DB.php';
include_once '../../models/User.php';

// Instantiate DB & connect
$database = new DB();
$db = $database->getConnection();

// Instantiate role object
$user = new User($db);

$data = json_decode(file_get_contents("php://input"), true);
$passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
$user->createUser($data['name'], $data['familyName'], $passwordHash, $data['email'], $data['number']);

// Make JSON
print_r(json_encode($user));
return $user;
?>