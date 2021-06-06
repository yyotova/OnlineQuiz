<?php
class Question
{
    private $conn;

    private $id;
    private $title;
    private $points;
    private $quiz_id;
    private $picture;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createQuestion($id, $title, $points, $QuizId, $picture)
    {
       
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getPoints()
    {
        return $this->points;
    }

    public function getQuizId()
    {
        return $this->quiz_id;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setPoints($points)
    {
        $this->points = $points;
    }

    public function setQuizId($quiz_id)
    {
        $this->quiz_id = $quiz_id;
    }

    public function setPicture($picture)
    {
        $this->picture = $picture;
    }
}
