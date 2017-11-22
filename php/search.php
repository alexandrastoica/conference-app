<?php
    include('db.php');
    include('classes/conferences.class.php');

    $confObj = new Conferences($db);

    // Attempt search query execution
    try{
        if(isset($_REQUEST['term'])){
            // create prepared statement
            $sql = "SELECT * FROM conferences WHERE conference_title LIKE :term";
            $stmt = $db->prepare($sql);
            $term = '%' . $_REQUEST['term'] . '%';
            // bind parameters to statement
            $stmt->bindParam(':term', $term);
            // execute the prepared statement
            $stmt->execute();

            $result = array();

            if($stmt->rowCount() > 0){
                while($row = $stmt->fetch()){
                  array_push($result, array(
                    'id'    => $row['conference_id'],
                    'title' => $row['conference_title']
                  ));
                }
                echo json_encode($result);
            } else{
                echo false;
            }
        }
    } catch(PDOException $e){
        echo $e->getMessage();
    }
?>
