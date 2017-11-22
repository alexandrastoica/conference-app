<?php
  require_once($_SERVER['DOCUMENT_ROOT'] . '/php/db.php');
  require_once($_SERVER['DOCUMENT_ROOT'] . '/php/classes/users.class.php');
  require_once($_SERVER['DOCUMENT_ROOT'] . '/php/classes/conferences.class.php');
  require_once($_SERVER['DOCUMENT_ROOT'] . '/php/classes/events.class.php');

  echo '<!DOCTYPE html>
        <html lang="en">
          <head>
            <!--  Basic  -->
            <meta charset="utf-8">
            <title>Conference App</title>
            <meta name="description" content="">
            <meta name="author" content="Alexandra Stoica">

            <!--  Mobile Specific Metas  -->
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

            <!--  Links  -->
            <link rel="stylesheet" href="../assets/css/reset.min.css">
            <link rel="stylesheet" href="../assets/css/style.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

            <link href="https://fonts.googleapis.com/css?family=Open+Sans:300|Poppins" rel="stylesheet">

            <!--[if lt IE 9]>
                <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
                <script src="//ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
            <![endif]-->

            <!-- Favicons  -->
            <link rel="icon" type="image/ico" href="favicon.ico">
          </head>
          <body>';
?>
