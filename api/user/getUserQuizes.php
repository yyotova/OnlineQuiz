<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/DB.php';
include_once '../../models/User.php';
include_once '../../utils/CommonFunction.php';
include_once '../../utils/ErrorMessages.php';

$database = new DB();
$db = $database->getConnection();

$user = new User($db);
$result;

try {
    $id = $_GET['id'];

    CommonFunction::throwIfDataIsEmpty($id, ErrorMessages::LEVEL_ID_ERROR_MESSAGE);

    $quizes = $user->getUsersQuizes($id);
    $result = CommonFunction::createSuccessObject($quizes);

} catch (PDOException $e) {
    $result = CommonFunction::createErrorObject("Connection failed: " . $e->getMessage());
} finally {
    print_r($result);
    return $result;
}
?>