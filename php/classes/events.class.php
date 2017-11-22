<?php
  class Events {
    protected $db = null;

    public function __construct($db){
      $this->db = $db;
      $this->create();
    }

    public function create(){
      $query = "CREATE TABLE IF NOT EXISTS events (
                      event_id             INT(3) UNSIGNED AUTO_INCREMENT,
                      event_title          VARCHAR(25) NOT NULL,
                      event_description    VARCHAR(500) NOT NULL,
                      event_speaker        VARCHAR(25) NOT NULL,
                      event_time           TIME NOT NULL,
                      conference_id        INT(3) UNSIGNED,
                      CONSTRAINT PRIMARY KEY (event_id),
                      CONSTRAINT FOREIGN KEY (conference_id) REFERENCES conferences(conference_id)
                ) ENGINE = InnoDB";

      $pdo = $this->db->prepare($query);
      return $pdo->execute();
    }

    public function add($title, $description, $speaker, $time, $conference_id){
      // check if conference name already exists
      $pdo = $this->db->prepare("SELECT event_title FROM events WHERE event_title = :title AND conference_id = :id");
      $pdo->bindParam(':title', $title);
      $pdo->bindParam(':id', $conference_id);
      $pdo->execute();

      if($pdo->rowCount() > 0){
        return false;
      } else {
        // insert into db
        try{
          $query = "INSERT INTO events (
                      event_title,
                      event_description,
                      event_speaker,
                      event_time,
                      conference_id
                    ) VALUES (:title, :description, :speaker, :ctime, :conference_id)";

          $pdo = $this->db->prepare($query);

          $pdo->bindParam(":title", $title);
          $pdo->bindParam(":description", $description);
          $pdo->bindParam(":speaker", $speaker);
          $pdo->bindParam(":ctime", $time, PDO::PARAM_STR);
          $pdo->bindParam(":conference_id", $conference_id);

          $pdo->execute();

        } catch(Exception $e) {
            return $e;
        }
        // return lastId
        return $this->db->lastInsertId();
      }
    }

    public function update($id, $title, $description, $speaker, $time){
      // update query
      try{
        $query = "UPDATE events
                  SET event_title = :title
                  AND event_description = :description
                  AND event_speaker = :speaker
                  AND event_time = :ctime
                  WHERE event_id = :id";

        $pdo = $this->db->prepare($query);

        $pdo->bindParam(':id', $id);
        $pdo->bindParam(":title", $title);
        $pdo->bindParam(":description", $description);
        $pdo->bindParam(":speaker", $speaker);
        $pdo->bindParam(":ctime", $time);
        $pdo->execute();

      } catch (Exception $e) {
          die($e->getMessage());
      }
    }

    public function getByConferenceId($id){

      try{
        $query = "SELECT * FROM events WHERE conference_id = :id";
        $pdo = $this->db->prepare($query);
        $pdo->bindParam(':id', $id);
        $pdo->execute();

        return $pdo->fetchAll();

      } catch (Exception $e) {
          die($e->getMessage());
      }
    }

    public function delete($id){
      // delete from db
      try{
        $query = "DELETE FROM events WHERE event_id = :id";
        $pdo = $this->db->prepare($query);
        $pdo->bindParam(':id', $id);
        $pdo->execute();
      } catch (Exception $e) {
          die($e->getMessage());
      }
    }
  }

?>
