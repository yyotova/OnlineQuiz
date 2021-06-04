<?php
class Quiz
{
    // DB stuff
    private $conn;
    private $table = 'quizes';

    // Quiz Properties
    private $id;
    private $title;
    private $description;
    private $level_id;
    private $max_score;

    // Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get Quizes
    public function getQuizes()
    {
        // Create query
        $query = 'SELECT name FROM' . $this->table;

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
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
        return $this->level_id;
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

    public function setLevelId($level_id)
    {
        $this->level_id = $level_id;
    }

    public function setMaxScore($max_score)
    {
        $this->max_score = $max_score;
    }
}
