<?php

include_once('Level.php');

class Quiz
{
    private $connection;

    private $id;
    private $title;
    private $description;
    private $levelId;
    private $maxScore;

    public function __construct($db)
    {
        $this->connection = $db;
    }

    public function getQuizById($id)
    {
        $sql = 'SELECT * FROM quizes WHERE id = ?';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_STR);
        $stmt->execute([$id]);

        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($quiz)) {
            throw new InvalidArgumentException('Quiz with id ' . $id . ' does not exist!');
        }

        return $quiz;
    }

    public function createQuiz($id, $title, $description, $levelId, $maxScore)
    {
        $quizLevel = new Level($this->connection);
        $quizLevel->getLevelById($levelId);

        return $this->insertQuizQuery($id, $title, $description, $levelId, $maxScore);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getLevelId()
    {
        return $this->levelId;
    }

    public function getMaxScore()
    {
        return $this->maxScore;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setLevelId($levelId)
    {
        $this->levelId = $levelId;
    }

    public function setMaxScore($maxScore)
    {
        $this->maxScore = $maxScore;
    }

    public function __toString()
    {
        return "{$this->id}" + ' ' .
            "{$this->description}";
    }

    private function insertQuizQuery($id, $title, $description, $levelId, $maxScore)
    {
        $this->connection->beginTransaction();

        $sql = "INSERT INTO quizes(id, title, description, level_id, max_score) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(1, $id, PDO::PARAM_STR);
        $stmt->bindValue(2, $title, PDO::PARAM_STR);
        $stmt->bindValue(3, $description, PDO::PARAM_STR);
        $stmt->bindValue(4, $levelId, PDO::PARAM_STR);
        $stmt->bindValue(5, $maxScore, PDO::PARAM_INT);

        $stmt->execute([$id, $title, $description, $levelId, $maxScore]);

        $this->connection->commit();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $row['id'] = $id;
        $row['title'] = $title;
        $row['description'] = $description;
        $row['levelId'] = $levelId;
        $row['maxScore'] = $maxScore;

        return $row;
    }
}
