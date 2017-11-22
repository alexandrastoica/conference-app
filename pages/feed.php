<?php
  include('../includes/header.php');

  if($_SESSION['loggedin']){
    // STORE user id from session into 'user'
    $user = $_SESSION['user_id'];
  }

  // INSTANTIATE new Conferences object
  $confObj = new Conferences($db);
  // GET all conferences
  $conferences = $confObj->get();
?>
  <?php include('../includes/navbar.php'); ?>
  <section class="wrapper">
    <div class="content column">
      <h1>Discover </h1>
      <?php foreach($conferences as $key => $conference) {
        // if user is logged in
        if($_SESSION['loggedin']){
          // SET default flags
          $going = $interested = 0;
          // IF user is going to the conference
          if($confObj->isGoing($conference['conference_id'], $user)){
            // CHANGE flag
            $going = 1;
          }
          // IF user is going to the conference
          if($confObj->isInterested($conference['conference_id'], $user)){
            // CHANGE flag
            $interested = 1;
          }
        }
      ?>
      <!--CARD-->
      <div class="card">
        <div class="card-header">
          <div class="card-title">
              <h3>CONFERENCE</h3>
              <h4><?php echo $conference['conference_title'] ?></h4>
          </div>
          <div class="card-image" style="background-image: url(<?php echo "../uploads/" . $conference['conference_img']?>);"></div>
        </div>

        <div class="card-detail">
          <p>
            <i class="ion-ios-location"> <?php echo $conference['conference_location'] ?></i>
            <i class="ion-ios-calendar">
              <?php // convert date format into a more appropiate one
                $date = (new DateTime($conference['conference_date']))->format("d/m/Y");
                echo $date
              ?></i>
          </p>
          <p><?php echo $conference['conference_description'] ?></p>
          <div class="controls">
            <a href="conference_details.php?id=<?php echo $conference['conference_id'] ?>">
              <div id="details" class="btn cancel"><i class="ion-more"></i><span>Details</span></div>
            </a>
            <?php if($_SESSION['loggedin']){
              if($going == 1){?>
                <button type="button" id="going" class="btn cancel going" data-conf="<?php echo $conference['conference_id'] ?>" data-user="<?php echo $user ?>"><i class="ion-ios-checkmark"></i><span>Going</span></button>
              <?php } else { ?>
                <button type="button" id="going" class="btn cancel not-going" data-conf="<?php echo $conference['conference_id'] ?>" data-user="<?php echo $user ?>"><i class="ion-ios-checkmark-outline"></i><span>Going</span></button>
              <?php }
                if($interested == 1) { ?>
                  <button type="button" id="like" class="btn cancel dislike" data-conf="<?php echo $conference['conference_id'] ?>" data-user="<?php echo $user ?>"><i class="ion-ios-star"></i><span>Interested</span></button>
              <?php } else { ?>
                  <button type="button" id="like" class="btn cancel like" data-conf="<?php echo $conference['conference_id'] ?>" data-user="<?php echo $user ?>"><i class="ion-ios-star-outline"></i><span>Interested</span></button>
              <?php }
            } ?>
          </div>
        </div>
      </div>
      <!--end card-->
      <?php } ?>
    </div>
</section>

<?php
  include('../includes/footer.php');

?>
