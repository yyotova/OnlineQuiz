<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/DB.php';
include_once '../../models/Question.php';
include_once '../../utils/CommonFunction.php';
include_once '../../utils/ErrorMessages.php';

// Instantiate DB & connect
$database = new DB();
$db = $database->getConnection();

$data = json_decode(file_get_contents('php://input'), true);

$question = new Question($db);
$result;

$id = uniqid();
$title = isset($data['title']) ? CommonFunction::testInput($data['title']) : '';
$points = isset($data['points']) ? CommonFunction::testInput($data['points']) : '';
$picture = isset($data['picture']) ? CommonFunction::testInput($data['picture']) : '';
$quizId = isset($data['quizId']) ? CommonFunction::testInput($data['quizId']) : '';

try {
    validateParams($title, $points, $quizId);

    $question = $question->createQuestion($id, $title, $points, $quizId, $picture);

    $result = CommonFunction::createSuccessObject($question);
} catch (InvalidArgumentException $e) {
    $result = CommonFunction::createErrorObject($e->getMessage());
} catch (PDOException $e) {
    $result = CommonFunction::createErrorObject("Connection failed: " . $e->getMessage());
} finally {
    print_r($result);
    return $result;
}

function validateParams($title, $points, $quizId)
{
    CommonFunction::throwIfDataIsEmpty($title, ErrorMessages::QUESTION_TITLE_ERROR_MESSAGE);
    CommonFunction::throwIfDataIsEmpty($quizId, ErrorMessages::QUIZ_ID_ERROR_MESSAGE);
    CommonFunction::throwIfDataIsEmpty($points, ErrorMessages::QUESTION_POINTS_ERROR_MESSAGE);

    if (is_numeric($points) == false) {
        throw new InvalidArgumentException('Points type should be numeric!');
    }
}
