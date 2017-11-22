<?php include('../includes/header.php');

  if(!$_SESSION['loggedin']){
    //user not logged in, redirect to index
    echo "<script>window.location.assign('../index.php');</script>";
  } else {
    // INSTANTIATE new Users object
    $userObj = new Users($db);
    // INSTANTIATE new Conferences object
    $confObj = new Conferences($db);
    // STORE user id from session
    $user_id = $_SESSION['user_id'];
    // GET all user details
    $user = $userObj->getDetails($user_id);
    // DEFAULT error messages
    $error = $errorP = $errorT = $errorL = $errorD = '';

    // IF form submitted
    if(isset($_POST['submit'])) {
      // STORE all post values
      $title = $_POST['title'];
      $description = $_POST['description'];
      $cdate = $_POST['actualDate'];
      $location = $_POST['location'];
      $postcode = $_POST['postcode'];
      $file = $_FILES['fileToUpload'];
      // VALIDATION: check if any fields are empty
      if(!($title) || !($description) || !($cdate) || !($location) || !($postcode) || $_FILES['fileToUpload']['size'] == 0) {
        // DISPLAY error message
        $error = "All fields are required.";
      } else {
        // CALL add method of conference object to insert
        $conf = $confObj->add($title, $description, $cdate, $location, $postcode, $file, $user_id);
        // VALIDATION: display error message according to returned value, redirect if success
        if($conf == 'exists') {
          // conference exists
          $error = 'This conference already exists.';
        } else if($conf == 'photo'){
          // was not able to upload photo
          $error = 'Error when uploading photo. Please try again later.';
        } else {
          //success, redirect to conference's page
          echo '<script>window.location.assign("conference_details.php?id='. $conf . '");</script>';
        }

      }
    }
?>
<?php include('../includes/navbar.php'); ?>
<section class="wrapper">
<?php include('../includes/profile_sidebar.php'); ?>

  <div class="content column">
    <h1>Publish</h1>
    <h2><i class="ion-ios-plus"></i>Add a conference</h2>
    <div class="sub-content center">
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="addconf" method="post" enctype="multipart/form-data">
        <?php if($error){
          echo '<div class="form-error"><i class="ion-alert-circled"></i>' . $error . '</div>';
        } ?>
        <div class="form-group">
          <label for="title">Title</label>
          <input type="text" id="title" name="title" required>
          <?php if($errorT){
           echo '<div class="form-error"><i class="ion-alert-circled"></i>' . $errorT . '</div>';
          }?>
        </div>

        <div class="form-group">
          <label for="description">Description</label>
          <textarea name="description" id="description" required></textarea>
          <?php if($errorD){
           echo '<div class="form-error"><i class="ion-alert-circled"></i>' . $errorD . '</div>';
          }?>
        </div>

        <div class="form-group">
          <label for="date">Date (dd/mm/yy)</label>
          <input type="text" id="date" class="date-pick" name="date" required>
          <?php if($errorD){
           echo '<div class="form-error"><i class="ion-alert-circled"></i>' . $errorD . '</div>';
          }?>
        </div>

        <div class="form-group">
          <!-- Add a div to house your postcode input field -->
          <label for="lookup_field">Look up postcode</label>
          <div id="lookup_field"></div>

          <label for="postcode">Postcode</label>
          <input id="postcode" id="postcode" name="postcode" type="text" required>
          <?php if($errorP){
           echo '<div class="form-error"><i class="ion-alert-circled"></i>' . $errorP . '</div>';
          }?>

          <label for="location">Location</label>
          <input type="text" id="first_line" name="location" required>
          <?php if($errorL){
           echo '<div class="form-error"><i class="ion-alert-circled"></i>' . $errorL . '</div>';
          }?>

        </div>

        <input type="file" name="fileToUpload" id="fileToUpload" class="upload" onChange="displayURL(this)" accept="image/gif, image/jpeg, image/png">
        <label for="fileToUpload"><i class="ion-android-arrow-dropup-circle"></i>Upload a photo</label>
        <span id="upload_file"></span>

        <input type="hidden" id="actualDate" name="actualDate"/>

        <div class="form-button-group">
        <button type="button" class="btn cancel">Clear</button>
        <button type="submit" name="submit" class="btn submit">Publish</button>
      </div>
      </form>
    </div>
  </div>
</section>
<?php
  include('../includes/footer.php');
  }
?>
