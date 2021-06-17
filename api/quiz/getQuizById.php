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

$quiz = new Quiz($db);
$result;

try {
    $id = $_GET['id'];

    CommonFunction::throwIfDataIsEmpty($id, ErrorMessages::QUIZ_ID_ERROR_MESSAGE);

    $quiz = $quiz->getQuizById($id);

    $result = CommonFunction::createSuccessObject($quiz);
} catch (InvalidArgumentException $e) {
    $result = CommonFunction::createErrorObject($e->getMessage());
} catch (PDOException $e) {
    $result = CommonFunction::createErrorObject("Connection failed: " . $e->getMessage());
} finally {
    print_r(json_encode($result));
}
