<?php

include_once('Level.php');

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
            $quiz = $query["data"];

            $quizLevel = new Level($this->connection);
         
            $this->id = $quiz["id"];
            $this->title = $quiz["title"];
            $this->description = $quiz["description"];
            $this->maxScore = $quiz["maxScore"];
            $this->levelId = $quiz["levelId"];
            
            print_r($query);

            return $query;
        } else {
            print_r($query);
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

    private function insertQuizQuery($id, $title, $description, $levelId, $maxScore)
    {
        try {
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
          
            return ["success" => true, "data" => $row];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            echo 'here';
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }
}
