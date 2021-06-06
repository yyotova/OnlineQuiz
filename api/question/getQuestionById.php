<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/DB.php';
include_once '../../models/Question.php';
include_once '../../utils/CommonFunction.php';
include_once '../../utils/ErrorMessages.php';

// Instantiate DB & connect
$database = new DB();
$db = $database->getConnection();

$question = new Question($db);
$result;

try {
    $id = $_GET['id'];

    CommonFunction::throwIfDataIsEmpty($id, ErrorMessages::QUESTION_ID_ERROR_MESSAGE);

    $question = $question->getQuestionById($id);

    $result = CommonFunction::createSuccessObject($question);
} catch (InvalidArgumentException $e) {
    $result = CommonFunction::createErrorObject($e->getMessage());
} catch (PDOException $e) {
    $result = CommonFunction::createErrorObject("Connection failed: " . $e->getMessage());
} finally {
    print_r($result);
    return $result;
}
