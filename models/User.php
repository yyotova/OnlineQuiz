<?php
include_once('Role.php');

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
    $this->enabled = true;
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
          $this->name = $user["name"];
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
    $user_id = uniqid();
    $query = $this->insertUserQuery(
      $user_id,
      $name,
      $familyName,
      $passwordHash,
      $email,
      $number,
      "84b2b226-c46b-11eb-8529-0242ac130003",
      $this->enabled
    );

    if ($query["success"]) {
      $userRole = new Role($this->conn);

      $this->password = $passwordHash;
      $this->email = $email;
      $this->userId = $user_id;
      $this->name = $name;
      $this->role = $userRole->getRoleById("84b2b226-c46b-11eb-8529-0242ac130003")["name"];
      $this->familyName = $familyName;
      $this->number = $number;

      return $query;
    } else {
      return $query;
    }
  }

  public function getUsersQuizes($userId)
  {
    $sql = 'SELECT * FROM quizes 
    JOIN user_info ON user_info.quiz_id = quizes.id 
    WHERE user_info.user_id = ?';
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(1, $userId, PDO::PARAM_STR);
    $stmt->execute([$userId]);

    $quizes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($quizes)) {
      throw new InvalidArgumentException('Quizes for user with id ' . $userId . ' does not exist!');
    }

    return $quizes;
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

  private function insertUserQuery($id, $name, $familyName, $password, $email, $number, $roleId, $enabled)
  {
    try {
      $this->conn->beginTransaction();

      $sql = "INSERT INTO users(id, name, password, email, family_name, role_id, number, enabled) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = $this->conn->prepare($sql);

      $stmt->bindValue(1, $id, PDO::PARAM_STR);
      $stmt->bindValue(2, $name, PDO::PARAM_STR);
      $stmt->bindValue(3, $password, PDO::PARAM_STR);
      $stmt->bindValue(4, $familyName, PDO::PARAM_STR);
      $stmt->bindValue(5, $email, PDO::PARAM_STR);
      $stmt->bindValue(6, $roleId, PDO::PARAM_STR);
      $stmt->bindValue(7, $number, PDO::PARAM_STR);
      $stmt->bindValue(8, $enabled, PDO::PARAM_BOOL);

      $stmt->execute([$id, $name, $password, $email, $familyName, $roleId, $number, $enabled]);
      $this->conn->commit();
      return ["success" => true];
    } catch (PDOException $e) {
      $this->conn->rollBack();
      return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
    }
  }
}
