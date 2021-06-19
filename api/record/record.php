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

$data = json_decode(file_get_contents('php://input'), true);
$fileBlob = $data['recordFile'];

$question = new Question($db);

$result;

try {
    $question = $question->addRecord($fileBlob);

    $result = CommonFunction::createSuccessObject($question);
} catch (InvalidArgumentException $e) {
    $result = CommonFunction::createErrorObject($e->getMessage());
} catch (PDOException $e) {
    $result = CommonFunction::createErrorObject("Connection failed: " . $e->getMessage());
} finally {
    print_r(json_encode($result));
}