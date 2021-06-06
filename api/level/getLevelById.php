<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/DB.php';
include_once '../../models/Level.php';
include_once '../../utils/CommonFunction.php';

// Instantiate DB & connect
$database = new DB();
$db = $database->getConnection();

$level = new Level($db);
$result;

try {
    $id = $_GET['id'];

    if (empty($id)) {
        throw new InvalidArgumentException('Level ID should be provided!');
    }

    $level = $level->getLevelById($id);

    $result = CommonFunction::createSuccessObject($level);
} catch (InvalidArgumentException $e) {
    $result = CommonFunction::createErrorObject($e->getMessage());
} catch (PDOException $e) {
    $result = CommonFunction::createErrorObject("Connection failed: " . $e->getMessage());
} finally {
    print_r($result);
    return $result;
}
