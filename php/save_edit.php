<?php
  include('db.php');
  include('classes/users.class.php');

  $col = $_POST['col'];
  $val = $_POST['val'];
  $id = $_SESSION['user_id'];

  $userObj = new Users($db);
  $userObj->update($col, $val, $id);
?>
