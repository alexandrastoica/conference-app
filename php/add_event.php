<?php
  include('db.php');
  include('classes/events.class.php');
  // INSTANTIATE new Events object, pass the $db value to constructor
  $eventObj = new Events($db);
  // Validate time field
  if(!preg_match("/^([01]?[0-9]|2[0-3])\:+[0-5][0-9]$/", $_POST["time"])){
    echo 'timeEr';
  } else {
    // Call 'add' method and pass the data
    $addEvent = $eventObj->add($_POST["title"], $_POST["description"], $_POST["speaker"], $_POST["time"], $_POST["id"]);
    //die($addEvent);
  }
?>
