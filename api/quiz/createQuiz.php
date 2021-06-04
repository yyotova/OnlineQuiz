<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/DB.php';
include_once '../../models/Quiz.php';
include_once '../../utils/CommonFunction.php';

// Instantiate DB & connect
$database = new DB();
$db = $database->getConnection();

// Instantiate quiz object
$quiz = new Quiz($db);

// Get raw quiz data
$data = json_decode(file_get_contents('php://input'), true);

$id = uniqid();
$title = isset($data['title']) ? CommonFunction::testInput($data['title']) : '';
$description = isset($data['description']) ? CommonFunction::testInput($data['description']) : '';
$maxScore = isset($data['maxScore']) ? CommonFunction::testInput($data['maxScore']) : 0;
$levelId = isset($data['levelId']) ? CommonFunction::testInput($data['levelId']) : '';

$quiz->createQuiz($id, $title, $description, $maxScore, $levelId);

// Make JSON
print_r(json_encode($quiz));
