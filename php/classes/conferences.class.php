<?php
  class Conferences {
    protected $db = null;

    public function __construct($db){
      $this->db = $db;
      $this->create();
    }

    // METHOD: create the table into database if not exists
    public function create(){
      $query = "CREATE TABLE IF NOT EXISTS conferences (
                      conference_id             INT(3) UNSIGNED AUTO_INCREMENT,
                      conference_title          VARCHAR(25) NOT NULL,
                      conference_description    VARCHAR(500) NOT NULL,
                      conference_date           DATE NOT NULL,
                      conference_postcode       VARCHAR(25) NOT NULL,
                      conference_location       VARCHAR(225) NOT NULL,
                      conference_img            VARCHAR(500) NOT NULL,
                      user_id                   INT(3) UNSIGNED,
                      CONSTRAINT PRIMARY KEY (conference_id),
                      CONSTRAINT FOREIGN KEY (user_id) REFERENCES users(user_id),
                      UNIQUE KEY (conference_title)
                ) ENGINE = InnoDB";

      $pdo = $this->db->prepare($query);
      return $pdo->execute();
    }

    // METHOD: INSERT values to conferences table
    public function add($title, $description, $cdate, $location, $postcode, $file, $user_id){
      $folder = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
      // remove all spaces from name
      $file["name"] = str_replace(' ', '', $file["name"]);
      $upload_image = $folder . basename($file["name"]);
      if (move_uploaded_file($file["tmp_name"], $upload_image)){
        // VALIDATION: check if conference name already exists
        $pdo = $this->db->prepare("SELECT conference_title FROM conferences WHERE conference_title = :title");
        $pdo->bindParam(':title', $title);
        $pdo->execute();

        if($pdo->rowCount() > 0){
          return 'exists';
        } else {
          // INSERT into db
          try{
            $query = "INSERT INTO conferences (
                        conference_title,
                        conference_description,
                        conference_date,
                        conference_location,
                        conference_postcode,
                        conference_img,
                        user_id
                      ) VALUES (:title, :description, :cdate, :location, :postcode, :img, :user_id)";

            $pdo = $this->db->prepare($query);

            $pdo->bindParam(":title", $title);
            $pdo->bindParam(":description", $description);
            $pdo->bindParam(":cdate", $cdate, PDO::PARAM_STR);
            $pdo->bindParam(":location", $location);
            $pdo->bindParam(":postcode", $postcode);
            $pdo->bindParam(":img", $file["name"]);
            $pdo->bindParam(":user_id", $user_id);

            $pdo->execute();

          } catch(Exception $e) {
              die($e);
              //return 'photo';
          }
          // return lastId
          return $this->db->lastInsertId();
        }
      } else {
        return 'photo';
      }
    }

    // METHOD: UPDATE values of conference table
    public function update($id, $title, $description, $cdate, $location, $postcode, $user_id){
      // update query
      try{
        $query = "UPDATE conferences
                  SET conference_title = :title
                  AND conference_description = :description
                  AND conference_date = :cdate
                  AND conference_location = :location
                  AND conference_postcode = :postcode
                  WHERE conference_id = :id";

        $pdo = $this->db->prepare($query);

        $pdo->bindParam(':id', $id);
        $pdo->bindParam(":title", $title);
        $pdo->bindParam(":description", $description);
        $pdo->bindParam(":cdate", $date);
        $pdo->bindParam(":location", $location);
        $pdo->bindParam(":postcode", $postcode);
        $pdo->execute();

      } catch (Exception $e) {
          die($e->getMessage());
      }
    }

    // METHOD: GET all results from conferences table
    public function get(){
      try{
        $query = "SELECT * FROM conferences";
        $pdo = $this->db->prepare($query);
        $pdo->execute();

        return $pdo->fetchAll();

      } catch (Exception $e) {
          die($e->getMessage());
      }
    }

    // METHOD: GET all results from conferences table, by passing the id
    public function getById($id){
      try{
        $query = "SELECT * FROM conferences WHERE conference_id = :id";
        $pdo = $this->db->prepare($query);
        $pdo->bindParam(':id', $id);
        $pdo->execute();

        return $pdo->fetch(PDO::FETCH_ASSOC);

      } catch (Exception $e) {
          die($e->getMessage());
      }
    }

    // METHOD: GET all results from conferences table by user id
    public function getConfByUserId($user_id){
      try{
        $query = "SELECT * FROM conferences WHERE user_id = :id";
        $pdo = $this->db->prepare($query);
        $pdo->bindParam(':id', $user_id);
        $pdo->execute();

        return $pdo->fetchAll();

      } catch (Exception $e) {
          die($e->getMessage());
      }
    }

    // METHOD: GET all conferences A user (id) goes to
    public function goingConf($user_id){
      try{
        $query = "SELECT conferences.conference_id, conferences.conference_title
                  FROM conferences JOIN going_to
                  ON conferences.conference_id = going_to.conference_id
                  WHERE going_to.user_id = :user_id";

        $pdo = $this->db->prepare($query);
        $pdo->bindParam(':user_id', $user_id);
        $pdo->execute();

        return $pdo->fetchAll();

      } catch (Exception $e) {
          die($e->getMessage());
      }
    }

    // METHOD: GET all conferences A user (id) is interested in
    public function likedConf($user_id){
      try{
        $query = "SELECT conferences.conference_id, conferences.conference_title
                  FROM conferences JOIN liked_conf
                  ON conferences.conference_id = liked_conf.conference_id
                  WHERE liked_conf.user_id = :user_id";

        $pdo = $this->db->prepare($query);
        $pdo->bindParam(':user_id', $user_id);
        $pdo->execute();

        return $pdo->fetchAll();

      } catch (Exception $e) {
          die($e->getMessage());
      }
    }

    // METHOD: GET all results of A conference (id) A user (id) goes to
    public function isGoing($conf_id, $user_id){
      try{
        $query = "SELECT * FROM going_to WHERE conference_id = :conf_id AND user_id = :user_id";
        $pdo = $this->db->prepare($query);
        $pdo->bindParam(':conf_id', $conf_id);
        $pdo->bindParam(':user_id', $user_id);
        $pdo->execute();

        $go = $pdo->fetch(PDO::FETCH_ASSOC);

        if($go){
          return true;
        } else {
          return false;
        }

      } catch (Exception $e) {
          die($e->getMessage());
      }
    }

    // METHOD: GET all results of A conference (id) A user (id) is interested in
    public function isInterested($conf_id, $user_id){
      try{
        $query = "SELECT * FROM liked_conf WHERE conference_id = :conf_id AND user_id = :user_id";
        $pdo = $this->db->prepare($query);
        $pdo->bindParam(':conf_id', $conf_id);
        $pdo->bindParam(':user_id', $user_id);
        $pdo->execute();

        $like = $pdo->fetch(PDO::FETCH_ASSOC);

        if($like){
          return true;
        } else {
          return false;
        }

      } catch (Exception $e) {
          die($e->getMessage());
      }
    }

    // METHOD: DELETE conference by id
    public function delete($id){
      // delete from db
      try{
        // DELETE from everywhere
        $query = "DELETE FROM going_to WHERE conference_id = :id;
                  DELETE FROM liked_conf WHERE conference_id = :id;
                  DELETE FROM events WHERE conference_id = :id;
                  DELETE FROM conferences WHERE conference_id = :id";
        $pdo = $this->db->prepare($query);
        $pdo->bindParam(':id', $id);
        $pdo->execute();

      } catch (Exception $e) {
          echo $e->getMessage();
      }
    }
  }

?>
