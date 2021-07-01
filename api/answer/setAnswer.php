<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/DB.php';
include_once '../../models/Answer.php';
include_once '../../utils/CommonFunction.php';

$answerId = $_POST['answerId'];
$userId = $_POST['userId'];
$answerContent = $_POST['answerContent'];

// Instantiate DB & connect
$database = new DB();
$db = $database->getConnection();

$answer = new Answer($db);
$result;

try {
    $answer->setAnswer($answerId, $userId, $answerContent);
} catch (PDOException $e) {
    $result = CommonFunction::createErrorObject("Connection failed: " . $e->getMessage());
    print_r($result);
}