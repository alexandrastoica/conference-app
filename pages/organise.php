<?php
  include('../includes/header.php');

  if(!$_SESSION['loggedin']){
    //user not logged in, redirect to index
    echo "<script>window.location.assign('../index.php');</script>";
  } else {
    $userObj = new Users($db);
    $confObj = new Conferences($db);

    $user_id = $_SESSION['user_id'];
    $user = $userObj->getDetails($_SESSION['user_id']);
    $conferences = $confObj->getConfByUserId($user_id);
?>
<?php include('../includes/navbar.php'); ?>
<section class="wrapper">
<?php include('../includes/profile_sidebar.php'); ?>
  <div class="content column">
    <h1>Organise</h1>
    <div class="sub-content">
      <h2><i class="ion-ios-people"></i>Your conferences</h2>
        <?php foreach($conferences as $key => $conference) {?>
          <div class="divider">
            <p><?php echo $conference['conference_title'] ?></p>
            <span><a href='conference_details.php?id=<?php echo $conference["conference_id"] ?>&edit=1' alt='<?php echo $conference["conference_title"] ?>'>
                  <i id="icon-edit" class="ion-edit"></i></a>
            </span>
          </div>
        <?php } ?>
    </div>
  </div>
</section>
<?php
  include('../includes/footer.php');
  }
?>
