<?php
class CommonFunction
{
    public static function testInput($input)
    {
        $input = trim($input);
        $input = htmlspecialchars($input);
        $input = stripslashes($input);

        return $input;
    }

    public static function throwIfDataIsEmpty($data, $errorMessage)
    {
        if (empty($data)) {
            throw new InvalidArgumentException($errorMessage);
        }
    }

    public static function createSuccessObject($data)
    {
        return ["success" => true, "data" => $data];
    }

    public static function createErrorObject($errorMessage)
    {
        return ["success" => false, "error" => $errorMessage];
    }
}
