<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/DB.php';
include_once '../../models/Quiz.php';
include_once '../../models/Question.php';
include_once '../../utils/CommonFunction.php';
include_once '../../utils/ErrorMessages.php';

// Instantiate DB & connect
$database = new DB();
$db = $database->getConnection();

// Get raw quiz data
$data = json_decode(file_get_contents('php://input'), true);

$quiz = new Quiz($db);
$question = new Question($db);

$result;

$id = uniqid();
$title = isset($data['title']) ? CommonFunction::testInput($data['title']) : '';
$description = isset($data['description']) ? CommonFunction::testInput($data['description']) : '';
$maxScore = isset($data['maxScore']) ? CommonFunction::testInput($data['maxScore']) : '';
$levelId = isset($data['levelId']) ? CommonFunction::testInput($data['levelId']) : '';
$questionTitle = isset($data['questionTitle']) ? CommonFunction::testInput($data['title']) : '';
$points = isset($data['points']) ? CommonFunction::testInput($data['points']) : '';
$picture = isset($data['picture']) ? CommonFunction::testInput($data['picture']) : '';
$quizId = uniqid();

try {
    validateParams($title, $description, $levelId, $maxScore);

    $quiz = $quiz->createQuiz($id, $title, $description, $levelId, $maxScore);
    $question = $question->createQuestion($quizId, $questionTitle, $points, $id, $picture);
    
    $result = CommonFunction::createSuccessObject($quiz);
    print_r(CommonFunction::createSuccessObject($question));
} catch (InvalidArgumentException $e) {
    $result = CommonFunction::createErrorObject($e->getMessage());
} catch (PDOException $e) {
    $result = CommonFunction::createErrorObject("Connection failed: " . $e->getMessage());
} finally {
    print_r($result);
    return $result; 
}

function validateParams($title, $description, $levelId, $maxScore)
{
    CommonFunction::throwIfDataIsEmpty($title, ErrorMessages::QUIZ_TITLE_ERROR_MESSAGE);
    CommonFunction::throwIfDataIsEmpty($description, ErrorMessages::QUIZ_DESCRIPTION_ERROR_MESSAGE);
    // CommonFunction::throwIfDataIsEmpty($levelId, ErrorMessages::LEVEL_ID_ERROR_MESSAGE);
    CommonFunction::throwIfDataIsEmpty($maxScore, ErrorMessages::QUIZ_MAXSCORE_ERROR_MESSAGE);
}
