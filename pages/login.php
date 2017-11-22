<?php
  include('../includes/header.php');

  $error = '';
  if(isset($_POST['submit'])){

    if(!($_POST['email']) || !($_POST['password'])){
      $error = "All fields are required.";
    }

    if(!$error){
      $userObj = new Users($db);
      $currentUser = $userObj->login($_POST['email'], $_POST['password']);

      if($currentUser){
        //User found
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $currentUser['user_id'];

        echo "<script>window.location.assign('feed.php');</script>";
      } else {
         $error = "Username/password incorrect.";
      }
    }
  }
?>
  <?php include('../includes/navbar.php'); ?>
  <section class="wrapper">
      <div class="content center column">
          <h1>Login</h1>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="loginform" method="post">
            <?php if($error){
              echo '<div class="form-error"><i class="ion-alert-circled"></i>' . $error . '</div>';
            } ?>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" required>
            </div>

            <div class="form-button-group">
              <button type="button" name="cancel" class="btn cancel">Clear</button>
              <button type="submit" name="submit" class="btn submit">Login</button>
            </div>
          </form>
      </div>
  </section>
<?php include('../includes/footer.php'); ?>
