<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/DB.php';
include_once '../../models/Quiz.php';
include_once '../../utils/CommonFunction.php';
include_once '../../utils/ErrorMessages.php';

// Instantiate DB & connect
$database = new DB();
$db = $database->getConnection();
$result;

$data = json_decode(file_get_contents('php://input'), true);
$quiz = new Quiz($db);

$userId = isset($data['userId']) ? CommonFunction::testInput($data['userId']) : '';
$quizId = isset($data['quizId']) ? CommonFunction::testInput($data['quizId']) : '';
$userScore = isset($data['userScore']) ? CommonFunction::testInput($data['userScore']) : '';

try {
    $userScore = $quiz->setUserScore($userId, $quizId, $userScore);

    $result = CommonFunction::createSuccessObject($userScore);
} catch (PDOException $e) {
    $result = CommonFunction::createErrorObject("Connection failed: " . $e->getMessage());
} finally {
    print_r($result);
    return $result;
}
?>