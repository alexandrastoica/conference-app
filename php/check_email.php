<?php
  include('db.php');
  include('classes/users.class.php');

  // store value into $email variable
  $email = $_POST['email'];

  // check if user already exists
  try{
    $pdo = $db->prepare("SELECT user_email FROM users WHERE user_email = :email");
    $pdo->bindParam(':email', $email);
    $pdo->execute();

    if($pdo->rowCount() > 0){
      //user found
      echo "false";
    } else {
      echo "true";
    }
  } catch(Exception $e) {
    die($e->getMessage());
  }
?>
