<?php
  include('db.php');
  include('classes/users.class.php');

  // INSTANTIATE new Users object
  $userObj = new Users($db);
  // Call and return result of dislike method
  return $userObj->dislike($_POST["userid"], $_POST["confid"]);

?>
