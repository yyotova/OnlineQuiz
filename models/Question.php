<?php

include_once('Quiz.php');

class Question
{
    private $connection;

    private $id;
    private $title;
    private $points;
    private $quizId;
    private $picture;

    public function __construct($db)
    {
        $this->connection = $db;
    }

    public function createQuestion($id, $title, $points, $quizId, $picture)
    {
        $quiz = new Quiz($this->connection);
        $quiz->getQuizById($quizId);

        return $this->insertQuestionQuery($id, $title, $points, $quizId, $picture);
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
        return $this->quizId;
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

    public function setQuizId($quizId)
    {
        $this->quizId = $quizId;
    }

    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    private function insertQuestionQuery($id, $title, $points, $quizId, $picture)
    {
        $this->connection->beginTransaction();

        $sql = "INSERT INTO questions(id, title, points, quiz_id, picture) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(1, $id, PDO::PARAM_STR);
        $stmt->bindValue(2, $title, PDO::PARAM_STR);
        $stmt->bindValue(3, $points, PDO::PARAM_STR);
        $stmt->bindValue(4, $quizId, PDO::PARAM_STR);
        $stmt->bindValue(5, $picture, PDO::PARAM_INT);

        $stmt->execute([$id, $title, $points, $quizId, $picture]);

        $this->connection->commit();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $row['id'] = $id;
        $row['title'] = $title;
        $row['points'] = $points;
        $row['quizId'] = $quizId;
        $row['picture'] = $picture;

        return $row;
    }
}