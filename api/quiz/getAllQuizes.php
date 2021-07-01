<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/DB.php';
include_once '../../models/Quiz.php';
include_once '../../utils/CommonFunction.php';
include_once '../../utils/ErrorMessages.php';

$database = new DB();
$db = $database->getConnection();

$quiz = new Quiz($db);
$result;

try {
    $quizes = $quiz->getAllQuizes();
    $result = CommonFunction::createSuccessObject($quizes);
} catch (PDOException $e) {
    $result = CommonFunction::createErrorObject("Connection failed: " . $e->getMessage());
} finally {
    print_r(json_encode($result));
}
