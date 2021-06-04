<?php
class Level
{
    // DB stuff
    private $conn;
    private $table = 'levels';

    // Post Properties
    public $id;
    public $name;

    // Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get Levels
    public function getLevels()
    {
        // Create query
        $query = 'SELECT name FROM' . $this->table;

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Get Single Level
    public function getLevelById()
    {
        // Create query
        $query = 'SELECT * FROM ' . $this->table . '
                  WHERE
                    id = ?
                  LIMIT 1';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute([$this->id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->name = $row['name'];
    }
}
