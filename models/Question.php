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
    private $record;

    public function __construct($db)
    {
        $this->connection = $db;
    }

    public function getQuestionById($id)
    {
        $sql = 'SELECT * FROM questions WHERE id = ?';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_STR);
        $stmt->execute([$id]);

        $question = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($question)) {
            throw new InvalidArgumentException('Question with id ' . $id . ' does not exist!');
        }

        return $question;
    }

    public function getQuestionsByQuizId($quizId)
  {
    $sql = 'SELECT * FROM questions 
    WHERE quiz_id = ?';
    $stmt = $this->connection->prepare($sql);
    $stmt->bindValue(1, $quizId, PDO::PARAM_STR);
    $stmt->execute([$quizId]);

    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($questions)) {
      throw new InvalidArgumentException('Questions for quiz with id ' . $quizId . ' does not exist!');
    }

    return $questions;
  }

  public function getQuestions()
  {
    $sql = 'SELECT * FROM questions';
    $stmt = $this->connection->prepare($sql);
    $stmt->execute();

    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $questions;
  }

    public function createQuestion($id, $title, $points, $quizId, $picture)
    {
        $quiz = new Quiz($this->connection);
        $quiz->getQuizById($quizId);

        return $this->insertQuestionQuery($id, $title, $points, $quizId, $picture);
    }

    public function setAudioType($questionId) {
        echo 'hreee';
        $sql = 'UPDATE questions SET is_audio = 1 WHERE id = ?';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(1, $questionId, PDO::PARAM_STR);
        $stmt->execute([$questionId]);
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

    public function getRecord()
    {
        return $this->record;
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

    public function setRecord($record)
    {
        $this->record = $record;
    }

    private function insertQuestionQuery($id, $title, $points, $quizId, $picture)
    {
        $this->connection->beginTransaction();

        $sql = "INSERT INTO questions(id, title, points, quiz_id, picture) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(1, $id, PDO::PARAM_STR);
        $stmt->bindValue(2, $title, PDO::PARAM_STR);
        $stmt->bindValue(3, $points, PDO::PARAM_INT);
        $stmt->bindValue(4, $quizId, PDO::PARAM_STR);
        $stmt->bindValue(5, $picture, PDO::PARAM_STR);

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
