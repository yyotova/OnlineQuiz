<?php
require_once "db.php";

class User
{
  private $role;
  private $familyName;
  private $number;
  private $enabled;
  private $name;
  private $password;
  private $email;
  private $userId;

  private $conn;
  private $table = 'users';

  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function getUserId()
  {
    return $this->userId;
  }

  public function userExists()
  {
    $query = $this->selectUserQuery(["user" => $this->name]);

    if ($query["success"]) {
      $user = $query["data"]->fetch(PDO::FETCH_ASSOC);

      if ($user) {
        return true;
      } else {
        return false;
      }
    } else {
      return $query;
    }
  }

  public function isValid()
  {
    $query = $this->db->selectUserQuery(["user" => $this->name]);

    if ($query["success"]) {
      $user = $query["data"]->fetch(PDO::FETCH_ASSOC);

      if ($user) {
        if (password_verify($this->password, $user["password"])) {
          $userRole = new Role($this->conn);
          $this->password = $user["password"];
          $this->email = $user["email"];
          $this->userId = $user["id"];
          $this->name= $user["name"];
          $this->role = $userRole->getRoleById($user["role_id"])["name"];
          $this->familyName = $user["family_name"];
          $this->number = $user["number"];
          $this->enabled = $user["enabled"];

          return ["success" => true];
        } else {
          return ["success" => false, "error" => "Invalid password"];
        }
      } else {
        return ["success" => false, "error" => "Invalid username"];
      }
    } else {
      return $query;
    }
  }

  public function createUser($name, $familyName, $passwordHash, $email, $number)
  {
    $query = $this->insertUserQuery([
      "name" => $name, 
      "familyName" => $familyName,
      "password" => $passwordHash, 
      "email" => $email,
      "number" => $number,
      "role_id" => "430d3096-c51d-11eb-8529-0242ac130003",
      "enabled" => true]
    );

    if ($query["success"]) {
      $user = $query["data"]->fetch(PDO::FETCH_ASSOC);
      $userRole = new Role($this->conn);

      $this->password = $passwordHash;
      $this->email = $email;
      $this->userId = $user["id"];
      $this->name= $user["name"];
      $this->role = $userRole->getRoleById($user["role_id"])["name"];
      $this->familyName = $user["family_name"];
      $this->number = $user["number"];
      $this->enabled = $user["enabled"];

      return $query;
    } else {
      return $query;
    }
  }

  private function selectUserQuery($data)
  {
    try {
      $sql = 'SELECT * FROM' . $this->table . 'WHERE name = :user';
      $stmt = $this->conn->prepare($sql);

      $stmt->execute($data);

      return ["success" => true, "data" => $stmt];
    } catch (PDOException $e) {
      return ["success" => false, "error" => $e->getMessage()];
    }
  }

  private function insertUserQuery($data)
  {
    try {
      $sql = "INSERT INTO users(id, name, password, email, family_name, role_id, number, enabled) VALUES (:id, :username, :password, :email, :familyName, :roleId, :number, :enabled)";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute($data);

      return ["success" => true, "data" => $stmt];
    } catch (PDOException $e) {
      $this->connection->rollBack();
      return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
    }
  }
}
