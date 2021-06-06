<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/DB.php';
include_once '../../models/Answer.php';
include_once '../../utils/CommonFunction.php';
include_once '../../utils/ErrorMessages.php';

// Instantiate DB & connect
$database = new DB();
$db = $database->getConnection();

$data = json_decode(file_get_contents('php://input'), true);

$answer = new Answer($db);
$result;

$id = uniqid();
$content = isset($data['content']) ? CommonFunction::testInput($data['content']) : '';
$questionId = isset($data['questionId']) ? CommonFunction::testInput($data['questionId']) : '';
$isCorrect = isset($data['isCorrect']) ? CommonFunction::testInput($data['isCorrect']) : false;

try {
    CommonFunction::throwIfDataIsEmpty($questionId, ErrorMessages::QUESTION_ID_ERROR_MESSAGE);

    $answer = $answer->createAnswer($id, $content, $questionId, $isCorrect);

    $result = CommonFunction::createSuccessObject($answer);
} catch (InvalidArgumentException $e) {
    $result = CommonFunction::createErrorObject($e->getMessage());
} catch (PDOException $e) {
    $result = CommonFunction::createErrorObject("Connection failed: " . $e->getMessage());
} finally {
    print_r($result);
    return $result;
}
