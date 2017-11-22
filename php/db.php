<?php session_start();

  // CREDENTIALS
  $host = 'localhost';
  $dbname = 'redhugci_confdb';
  $user = 'redhugci_conf';
  $password = '1234Conf';

  try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(PDOException $e) {
    echo $e->getMessage();
  }

 ?>
