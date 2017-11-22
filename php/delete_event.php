<?php
  include('db.php');
  include('classes/events.class.php');

  // INSTANTIATE new Events object
  $eventObj = new Events($db);
  // Call delete method of object
  $addEvent = $eventObj->delete($_POST["id"]);

  return $addEvent;

?>
