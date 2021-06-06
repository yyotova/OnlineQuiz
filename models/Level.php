<?php
class Level
{
    private $conn;

    private $id;
    private $name;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getLevelById($id)
    {
        $sql = 'SELECT * FROM levels WHERE id = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_STR);
        $stmt->execute([$id]);

        $level = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($level)) {
            throw new InvalidArgumentException('Level with id ' . $id . ' does not exist!');
        }

        return $level;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}
