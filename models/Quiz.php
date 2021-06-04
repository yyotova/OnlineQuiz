<?php
class Quiz
{
    // DB stuff
    private $connection;
    private $table = 'quizes';

    // Quiz Properties
    private $id;
    private $title;
    private $description;
    private $levelId;
    private $maxScore;

    // Constructor with DB
    public function __construct($db)
    {
        $this->connection = $db;
    }

    // Get Quizes
    public function getQuizes()
    {
        // Create query
        $query = 'SELECT name FROM' . $this->table;

        // Prepare statement
        $stmt = $this->connection->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    public function createQuiz($id, $title, $description, $levelId, $maxScore)
    {
        $query = $this->insertQuizQuery($id, $title, $description, $levelId, $maxScore);

        if ($query["success"]) {
            $quiz = $query["data"]->fetch(PDO::FETCH_ASSOC);
            $quizLevel = new Level($this->connection);

            $this->id = $quiz["id"];
            $this->title = $quiz["title"];
            $this->description = $quiz["description"];
            $this->maxScore = $quiz["maxScore"];
            $this->level = $quizLevel->getLevelById($quiz["levelId"])["name"];

            return $query;
        } else {
            return $query;
        }
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
        return $this->max_score;
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

    private function insertQuizQuery($id, $title, $descriptions, $levelId, $maxScore)
    {
        try {
            $this->connection->beginTransaction();

            $sql = "INSERT INTO quizes (id, title, description, levelId, maxScore) VALUES (:id, :title, :description, :levelId, :maxScore)";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$id, $title, $descriptions, $levelId, $maxScore]);

            $this->connection->commit();

            return ["success" => true, "data" => $stmt];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }
}
