<?php
  include('db.php');
  include('classes/conferences.class.php');

  // INSTANTIATE new Conferences object
  $confObj = new Conferences($db);
  // Call delete method of object
  echo $confObj->delete($_POST["id"]);


?>
