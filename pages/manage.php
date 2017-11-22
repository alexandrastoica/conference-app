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
    $go_conferences = $confObj->goingConf($user_id);
    $liked_conferences = $confObj->likedConf($user_id);
?>
<?php include('../includes/navbar.php'); ?>
<section class="wrapper">
<?php include('../includes/profile_sidebar.php'); ?>

  <div class="content column">
    <h1>Manage</h1>
    <div class="sub-content">
      <h2><i class="ion-ios-checkmark"></i>Conferences you're going to</h2>
        <?php
        if($go_conferences){
          foreach($go_conferences as $key => $conference) {?>
            <div class="divider">
              <p><?php echo $conference['conference_title'] ?></p>
              <span>
                <a href='conference_details.php?id=<?php echo $conference["conference_id"] ?>&edit=1' alt='<?php echo $conference["conference_title"] ?>'>
                  <i id="icon-more" class="ion-more"></i>
                </a>
                <i id="icon-delete-go" data-conf="<?php echo $conference['conference_id'] ?>" data-user="<?php echo $user_id ?>" class="ion-close"></i>
              </span>
            </div>
          <?php }
        } else {
          echo 'No conferences.';
        }?>
    </div>

    <div class="sub-content">
        <h2><i class="ion-ios-star"></i>Conferences you're interested in</h2>
        <?php
        if($liked_conferences){
          foreach($liked_conferences as $key => $conference) {?>
            <div class="divider">
              <p><?php echo $conference['conference_title'] ?></p>
              <span>
                <a href='conference_details.php?id=<?php echo $conference["conference_id"] ?>&edit=1' alt='<?php echo $conference["conference_title"] ?>'>
                  <i id="icon-more" class="ion-more"></i>
                </a>
                <i id="icon-delete-like" data-conf="<?php echo $conference['conference_id'] ?>" data-user="<?php echo $user_id ?>" class="ion-close"></i>
              </span>
            </div>
          <?php }
        } else {
          echo '<div class="divider">No conferences.</div>';
        } ?>
    </div>
  </div>
</section>
<?php
  include('../includes/footer.php');
  }
?>
