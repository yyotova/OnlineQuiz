<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/DB.php';
include_once '../../models/Question.php';
include_once '../../utils/CommonFunction.php';

$targetPath = "../../wav-files/" . $_POST['fileName'] . '.wav';
move_uploaded_file($_FILES["inpFile"]["tmp_name"], $targetPath);

// Instantiate DB & connect
$database = new DB();
$db = $database->getConnection();

$question = new Question($db);
$result;

try {
    $question->setAudioType($_POST['fileName']);
} catch (PDOException $e) {
    $result = CommonFunction::createErrorObject("Connection failed: " . $e->getMessage());
    print_r($result);
}