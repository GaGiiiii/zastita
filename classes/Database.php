<?php

/*

1. SELECT ALL
2. SELECT ALL WHERE
3. SELECT ONE
4. SELECT ONE WHERE
5. SELECT JOIN
6. INSERT
7. UPDATE
8. DELETE

*/

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

class Database extends PDO {
  private $host; // Host
  private $db_name; // DB Name
  private $username; // DB Username
  private $password; // DB Password


  private static $instance = null; // Instanca klase
  public $connection = null; // Konekcija

  private function __construct() {
    $this->host = "localhost"; // Host
    $this->db_name = "zastita"; // DB Name
    $this->username = "root"; // DB Username
    $this->password = ""; // DB Password

    try {
      $this->connection = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8', $this->username, $this->password);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->connection->setAttribute(PDO::ATTR_FETCH_TABLE_NAMES, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      die("Database didn't connect.");
    }

    // $this->connection = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
  }

  public function getConnection() {
    return $this->connection;
  }

  public static function getInstance() {
    if (!isset(self::$instance)) {
      self::$instance = new Database();
    }

    return self::$instance;
  }

  /* oSELECT ONE WHERE */

  public function takenEmail($email) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM user WHERE email = :email");
      $query->execute(array(
        ':email' => $email,
      ));

      $user = $query->fetch(PDO::FETCH_ASSOC);

      if ($user) {
        return true;
      }

      return false;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return false;
    }
  }

  /* oINSERT */

  public function registerUser($data) {
    try {
      $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT); // Hashovanje sifre sa md5
      $query = Database::getInstance()->getConnection()->prepare("INSERT INTO user (email, password, username, image) VALUES (?, ?, ?, ?)");
      $result = $query->execute([
        $data['email'],
        $data['password'],
        $data['username'],
        $data['image'],
      ]);

      $data['id'] = Database::getInstance()->getConnection()->lastInsertId();
      $data['is_admin'] = false;

      $data = $this->fixData($data);

      if ($result) { // Ukoliko je query uspesan pravimo sesiju i vracamo true
        $_SESSION['user'] = $data;

        return true;
      }

      return false;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return false;
    }
  }

  public function fixData($data) {
    //Get keys
    $keys = array_keys($data);

    //Map keys to format function
    $keys = array_map(function($key){
      return $key = "user." . $key;
    }, $keys);

    //Use array_combine to map formatted keys to array values
    $data = array_combine($keys, $data);

    return $data;
  }

  /* oUPDATE */

  public function warnUser($user_id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("UPDATE `user` SET warned = 1 WHERE id = ?");
      $result = $query->execute([$user_id]);

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return false;
    }
  }

  public function loginUser($data) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM user WHERE email = :email");
      $query->execute(array(
        ':email' => $data['email'],
      ));

      $user = $query->fetch(PDO::FETCH_ASSOC);

      if ($user && password_verify($data['password'], $user['user.password'])) {
        $_SESSION['user'] = $user;

        return true;
      }

      return false;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  public function isUserLoggedIn() {
    if (isset($_SESSION['user'])) {
      return true;
    }

    return false;
  }

  public function logoutUser() {
    unset($_SESSION['user']);
  }

  public function getUserByID($id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM user WHERE id = :id");
      $query->execute(array(
        ':id' => $id,
      ));

      $user = $query->fetch(PDO::FETCH_ASSOC);

      return $user;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  public function getUserByEmail($email) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM user WHERE email = :email");
      $query->execute(array(
        ':email' => $email,
      ));

      $user = $query->fetch(PDO::FETCH_ASSOC);

      return $user;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  public function getPasswordForUser($id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT password FROM user WHERE id = :id");
      $query->execute(array(
        ':id' => $id,
      ));

      $password = $query->fetch()['password'];

      return $password;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  public function updateUserPassword($id, $password) {
    try {
      $password = md5(md5($password)); // Hashovanje sifre sa md5
      $query = Database::getInstance()->getConnection()->prepare("UPDATE `user` SET password = :password WHERE id = :id");
      $result = $query->execute([
        ':password' => $password,
        ':id' => $id,
      ]);

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  /* oSELECT ALL */

  public function getAllUsers() {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM `user` u");
      $query->execute();
      $users = $query->fetchAll(PDO::FETCH_ASSOC);

      return $users;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return [];
    }
  }

  // COURESES =====================================================================================================================================

  /* oDELETE */

  public function deleteCourse($id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("DELETE FROM course WHERE id = :id LIMIT 1");
      $result = $query->execute(array(':id' => $id));

      $query = Database::getInstance()->getConnection()->prepare("DELETE FROM user_course WHERE course_id = :id");
      $result = $query->execute(array(':id' => $id));

      $query = Database::getInstance()->getConnection()->prepare("DELETE FROM `clip` WHERE course_id = :id");
      $result = $query->execute(array(':id' => $id));

      $query = Database::getInstance()->getConnection()->prepare("DELETE FROM `chapter` WHERE course_id = :id");
      $result = $query->execute(array(':id' => $id));

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  /* oSELECT ONE WHERE */

  public function getCourse($id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM course c JOIN grade g ON g.grade_id = c.course_grade_id WHERE id = :id");
      $query->execute(array(
        ':id' => $id,
      ));

      $course = $query->fetch(PDO::FETCH_ASSOC);

      return $course;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return null;
    }
  }

  // USER_COURSE =================================================================================================================================================

  /* oDELETE */

  public function deleteCoursePurchase($id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("DELETE FROM `user_course` WHERE id = :id LIMIT 1");
      $result = $query->execute(array(
        ':id' => $id,
      ));

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  // PASSWORD RESET ================================================================================================================================================

  public function getPasswordReset($selector, $expires) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM `password_reset` WHERE selector = :selector AND expires >= :expires");
      $query->execute([
        ":selector" => $selector,
        ":expires" => $expires
      ]);

      $password_reset = $query->fetch();

      return $password_reset;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      die();
    }
  }

  public function deletePasswordReset($email) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("DELETE FROM password_reset WHERE email = :email");
      $result = $query->execute([
        ':email' => $email,
      ]);

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";
      echo $e->getLine();

      exit;
    }
  }

  public function insertPasswordReset($data) {
    try {
      $data = (object) $data;
      $hashedToken = password_hash($data->token, PASSWORD_DEFAULT);
      $query = Database::getInstance()->getConnection()->prepare("INSERT INTO password_reset (email, selector, token, expires) VALUES (?, ?, ?, ?)");
      $result = $query->execute([
        $data->email,
        $data->selector,
        $hashedToken,
        $data->expires,
      ]);

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";
      echo $e->getLine();

      exit;
    }
  }

  public function deleteLoginInfo($id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("DELETE FROM `login_details` WHERE id= ?");
      $result = $query->execute([
        $id,
      ]);

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";
      echo $e->getLine();

      exit;
    }
  }
}
