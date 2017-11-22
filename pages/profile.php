<?php
  include('../includes/header.php');
  if(!$_SESSION['loggedin']){
    //user not logged in, redirect to index
    echo "<script>window.location.assign('../index.php');</script>";
  } else {
    // INSTANTIATE new users obj
    $userObj = new Users($db);
    // GET all user details
    $user = $userObj->getDetails($_SESSION['user_id']);

    // SET default error message
    $errorUp = '';

    if(isset($_POST['submit'])){
      if($_FILES['fileToUpload']['size'] != 0){
        $ok = $userObj->uploadPic($_FILES['fileToUpload'], $_SESSION['user_id']);
        if($ok == 1) {
          //success
        } else {
          // DISPLAY error message
          $errorUp = "Could not upload your photo. Please try again later.";
        }
      } else {
        // DISPLAY error messsage
        $errorUp = "Please upload a picture.";
      }
    }

    include('../includes/navbar.php');
?>
<section class="wrapper">
  <?php include('../includes/profile_sidebar.php'); ?>
  <div class="content column">
    <h1>Profile</h1>
    <div class="sub-content">
      <h2><i class="ion-ios-gear"></i>Profile Settings</h2>
      <h3>Click on text to edit.</h3>
      <div class="divider">
        <p>first name</p>
        <span><p id="edit-p" contenteditable="true" onBlur="update(this, 'user_firstname')"><?php echo $user['user_firstname'] ?> </p> <i class="ion-edit"></i></span>
      </div>
      <div class="divider">
        <p>last name</p>
        <span><p contenteditable="true" onBlur="update(this, 'user_lastname')"><?php echo $user['user_lastname'] ?></p> <i class="ion-edit"></i></span>
      </div>
      <?php if($errorUp){
       echo '<div class="form-error"><i class="ion-alert-circled"></i>' . $errorUp . '</div>';
      }?>
      <div class="uploadP">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data">
          <input type="file" name="fileToUpload" id="fileToUpload" class="upload" onChange="displayURL(this)" accept="image/gif, image/jpeg, image/png">
          <label for="fileToUpload"><i class="ion-android-arrow-dropup-circle"></i>Select profile picture</label>
          <span id="upload_file"></span>
          <button type="submit" name="submit" class="btn submit">Upload</button>
        </form>
      </div>
    </div>
  </div>
</section>

<?php
  include('../includes/footer.php');
  }
?>
