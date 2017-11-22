<?php
  include('db.php');
  include('classes/users.class.php');

  $userObj = new Users($db);
  return $userObj->going($_POST["userid"], $_POST["confid"]);

?>
