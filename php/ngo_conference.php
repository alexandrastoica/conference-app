<?php
  include('db.php');
  include('classes/users.class.php');

  $userObj = new Users($db);
  return $userObj->notGoing($_POST["userid"], $_POST["confid"]);

?>
