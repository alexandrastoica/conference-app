<?php
  class Users {
    protected $db = null;

    public function __construct($db){
      $this->db = $db;
      $this->create();
    }

    public function create(){
      $query = "CREATE TABLE IF NOT EXISTS users (
                      user_id         INT(3) UNSIGNED AUTO_INCREMENT,
                      user_firstname  VARCHAR(25) NOT NULL,
                      user_lastname   VARCHAR(25) NOT NULL,
                      user_email      VARCHAR(100) NOT NULL,
                      user_password   CHAR(255) NOT NULL,
                      user_img        VARCHAR(500),
                      CONSTRAINT PRIMARY KEY (user_id),
                      UNIQUE KEY (user_email)
                ) ENGINE = InnoDB;
                CREATE TABLE IF NOT EXISTS liked_conf (
                      liked_id        INT(3) UNSIGNED AUTO_INCREMENT,
                      user_id         INT(3) UNSIGNED,
                      conference_id   INT(3) UNSIGNED,
                      CONSTRAINT PRIMARY KEY (liked_id),
                      CONSTRAINT FOREIGN KEY (user_id) REFERENCES users(user_id),
                      CONSTRAINT FOREIGN KEY (conference_id) REFERENCES conferences(conference_id)
                ) ENGINE = InnoDB;
                CREATE TABLE IF NOT EXISTS going_to (
                      going_id        INT(3) UNSIGNED AUTO_INCREMENT,
                      user_id         INT(3) UNSIGNED,
                      conference_id   INT(3) UNSIGNED,
                      CONSTRAINT PRIMARY KEY (going_id),
                      CONSTRAINT FOREIGN KEY (user_id) REFERENCES users(user_id),
                      CONSTRAINT FOREIGN KEY (conference_id) REFERENCES conferences(conference_id)
                ) ENGINE = InnoDB";

      $pdo = $this->db->prepare($query);
      return $pdo->execute();
    }

    public function register($firstname, $lastname, $email, $password){
      $encryptedPass = password_hash($password, PASSWORD_DEFAULT);
      // check if user already exists
      $pdo = $this->db->prepare("SELECT user_email FROM users WHERE user_email = :email");
      $pdo->bindParam(':email', $email);
      $pdo->execute();

      if($pdo->rowCount() > 0){
          return false;
      } else {
        // insert into db
        try{
          $query = "INSERT INTO users (user_firstname, user_lastname, user_email, user_password)
          VALUES (:firstname, :lastname, :email, :password)";
          $pdo = $this->db->prepare($query);
          $pdo->bindParam(":firstname", $firstname);
          $pdo->bindParam(":lastname", $lastname);
          $pdo->bindParam(":email", $email);
          $pdo->bindParam(":password", $encryptedPass);
          $pdo->execute();
        } catch(Exception $e) {
            die($e);
        }
        // return lastId
        return $this->db->lastInsertId();
      }
    }

    public function login($email, $password){
      // insert db
      try{
        $query = "SELECT * FROM users WHERE user_email = :email";
        $pdo = $this->db->prepare($query);
        $pdo->bindParam(':email', $email);
        $pdo->execute();

        $user = $pdo->fetch(PDO::FETCH_ASSOC);

        if(empty($user)) {
          return false;
        } elseif(password_verify($password, $user['user_password'])) {
          return $user;
        } else {
          return false;
        }

      } catch (Exception $e) {
          die($e->getMessage());
      }
    }

    public function uploadPic($file, $user_id){
      $folder = $_SERVER['DOCUMENT_ROOT'] . '/uploads/profile/';
      // remove all spaces from name
      $file["name"] = str_replace(' ', '', $file["name"]);
      $upload_image = $folder . basename($file["name"]);
      if (move_uploaded_file($file["tmp_name"], $upload_image)){
        try{
          $query = "UPDATE users SET user_img = :img WHERE user_id = :id";
          $pdo = $this->db->prepare($query);
          $pdo->bindParam(":img", $file["name"]);
          $pdo->bindParam(":id", $user_id);
          $pdo->execute();
          return 1;
        } catch(Exception $e) {
            echo $e->getMessage();
        }
      } else {
        //echo "Cannot upload picture.";
      }
    }

    public function like($user_id, $conf_id){
      try{
        $query = "INSERT INTO liked_conf (user_id, conference_id) VALUES (:user_id, :conference_id)";
        $pdo = $this->db->prepare($query);
        $pdo->bindParam(":user_id", $user_id);
        $pdo->bindParam(":conference_id", $conf_id);
        $pdo->execute();
      } catch(Exception $e) {
          die($e);
      }
      // return lastId
      return $this->db->lastInsertId();
    }

    public function dislike($user_id, $conf_id){
      try{
        $query = "DELETE FROM liked_conf WHERE user_id = :user_id AND conference_id = :conference_id";
        $pdo = $this->db->prepare($query);
        $pdo->bindParam(":user_id", $user_id);
        $pdo->bindParam(":conference_id", $conf_id);
        $pdo->execute();
      } catch(Exception $e) {
          die($e);
      }
    }

    public function going($user_id, $conf_id){
      try{
        $query = "INSERT INTO going_to (user_id, conference_id) VALUES (:user_id, :conference_id)";
        $pdo = $this->db->prepare($query);
        $pdo->bindParam(":user_id", $user_id);
        $pdo->bindParam(":conference_id", $conf_id);
        $pdo->execute();
      } catch(Exception $e) {
          die($e);
      }
      // return lastId
      return $this->db->lastInsertId();
    }

    public function notGoing($user_id, $conf_id){
      try{
        $query = "DELETE FROM going_to WHERE user_id = :user_id AND conference_id = :conference_id";
        $pdo = $this->db->prepare($query);
        $pdo->bindParam(":user_id", $user_id);
        $pdo->bindParam(":conference_id", $conf_id);
        $pdo->execute();
      } catch(Exception $e) {
          die($e);
      }
    }

    public function validate($firstname, $lastname, $email, $password, $cpassword){
      //check if first and last name are correct
      if (!preg_match("/^[a-zA-Z]*$/",$firstname)) {
        $errorF = "Only letters allowed.";
      }

      if (!preg_match("/^[a-zA-Z]*$/",$lastname)) {
        $errorL = "Only letters allowed.";
      }

      //check if email is valid
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorEmail = "Invalid email format.";
      }

      //check if password is the same as confirm password
      if($password == $cpassword){
        //check if password long enough
        if(strlen($_POST['password']) <= '3'){
          $errorPass = "Password too short.";
        } //check if password contains at least a number
        elseif(!preg_match("#[0-9]+#",$password)) {
          $errorPass = "Your password must contain at least 1 number.";
        } //check if password contains at least one capital letter
        elseif(!preg_match("#[A-Z]+#",$password)) {
            $errorPass = "Your password must contain at least 1 capital letter.";
        } //check if password contains at least one lowercase letter
        elseif(!preg_match("#[a-z]+#",$password)) {
            $errorPass = "Your password must contain at least 1 lowercase letter.";
        }
      } else {
          $errorCPass = "Passwords do not match.";
      } //password check
    }

    public function getDetails($id){
      try{
        $query = "SELECT * FROM users WHERE user_id = :user_id";
        $pdo = $this->db->prepare($query);
        $pdo->bindParam(":user_id", $id);
        $pdo->execute();
        return $pdo->fetch(PDO::FETCH_ASSOC);
      } catch(Exception $e) {
        return $e->getMessage();
      }
    }

    public function update($column, $value, $id){
      if($column == 'user_firstname'){
        try{
          $query = "UPDATE users SET user_firstname = :value WHERE user_id = :user_id";
          $pdo = $this->db->prepare($query);
          $pdo->bindParam(":value", $value);
          $pdo->bindParam(":user_id", $id);
          $pdo->execute();
        } catch(Exception $e) {
          die($e);
          return $e->getMessage();
        }
      } elseif($column == 'user_lastname'){
        try{
          $query = "UPDATE users SET user_lastname = :value WHERE user_id = :user_id";
          $pdo = $this->db->prepare($query);
          $pdo->bindParam(":value", $value);
          $pdo->bindParam(":user_id", $id);
          $pdo->execute();
        } catch(Exception $e) {
          die($e);
          return $e->getMessage();
        }
      } else {
        try{
          $query = "UPDATE users SET user_email = :value WHERE user_id = :user_id";
          $pdo = $this->db->prepare($query);
          $pdo->bindParam(":value", $value);
          $pdo->bindParam(":user_id", $id);
          $pdo->execute();
        } catch(Exception $e) {
          die($e);
          return $e->getMessage();
        }
      }
    }
  }
?>
