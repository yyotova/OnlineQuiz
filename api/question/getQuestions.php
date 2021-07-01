<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/DB.php';
include_once '../../models/Question.php';
include_once '../../utils/CommonFunction.php';
include_once '../../utils/ErrorMessages.php';

$database = new DB();
$db = $database->getConnection();

$question = new Question($db);
$result = '';

try {
    $questions = $question->getQuestions();
    $result = CommonFunction::createSuccessObject($questions);

} catch (PDOException $e) {
    $result = CommonFunction::createErrorObject("Connection failed: " . $e->getMessage());
} finally {
    print_r(json_encode($result));
}