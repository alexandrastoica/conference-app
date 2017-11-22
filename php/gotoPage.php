<?php
    include('db.php');
    include('classes/conferences.class.php');

    $confObj = new Conferences($db);

    $title = $_POST['title'];

try{
    $query = 'SELECT conference_id FROM conferences WHERE conference_title = :title';
    $pdo = $db->prepare($query);
    $pdo->bindParam(':title', $title);
    $pdo->execute();

    $conf = $pdo->fetch(PDO::FETCH_ASSOC);

    if($conf){
      echo $conf['conference_id'];
    }
  } catch(Exception $e){
    return $e->getMessage();
  }


?>
