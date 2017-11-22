<?php
  include('../includes/header.php');
  //create new Users object
  $userObj = new Users($db);

  $error = $errorF = $errorL = $errorEmail = $errorPass = $errorCPass = '';
  if(isset($_POST['submit'])){

    if(!($_POST['firstname']) || !($_POST['lastname']) || !($_POST['email']) || !($_POST['password']) || !($_POST['cpassword'])){
      $error = "All fields are required.";
    } else {
      //store post values into variables:
      $firstname = $_POST['firstname'];
      $lastname = $_POST['lastname'];
      $email = $_POST['email'];
      $password = $_POST['password'];
      $cpassword = $_POST['cpassword'];

      //check if first and last name are correct
      if (!preg_match("/^[a-zA-Z]*$/",$firstname)) {
        $errorF = "Only letters allowed.";
      }

      if (!preg_match("/^[a-zA-Z]*$/",$lastname)) {
        $errorL = "Only letters allowed.";
      }

      //check if email is valid
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorEmail = "Invalid email format.";
      }

      //check if password is the same as confirm password
      if($password == $cpassword){
        //check if password long enough
        if(strlen($_POST['password']) <= '3'){
          $errorPass = "Password too short.";
        } //check if password contains at least a number
        elseif(!preg_match("#[0-9]+#",$password)) {
          $errorPass = "Your password must contain at least 1 number.";
        } //check if password contains at least one capital letter
        elseif(!preg_match("#[A-Z]+#",$password)) {
            $errorPass = "Your password must contain at least 1 capital letter.";
        } //check if password contains at least one lowercase letter
        elseif(!preg_match("#[a-z]+#",$password)) {
            $errorPass = "Your password must contain at least 1 lowercase letter.";
        }
      } else {
          $errorCPass = "Passwords do not match.";
      } //password check
    } // validation check

    if(!$error && !$errorEmail && !$errorPass && !$errorCPass){

      //has passed validation
      // register user
      $createUser = $userObj->register($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['password']);

      // if register is successful, redirect to login
      if($createUser){
        $to = $_POST['email'];
        $subject = "Welcome to our Conference App!";
        $message = "<html>
                      <head>
                        <title>Welcome to our Conference App</title>
                      </head>
                      <body>
                        <p>You registered to Conference App!</p>
                      </body>
                    </html>";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <stor1_14@uni.worc.ac.uk>' . "\r\n";

        mail($to, $subject, $message, $headers);

        echo "<script>window.location.assign('login.php');</script>";
      } else {
        // createUser returned false, which means user already in use
        // display error
        $error = 'User already exists.';
      }
    }
  }
?>
  <?php include('../includes/navbar.php'); ?>
  <section class="wrapper">
      <div class="content center column">
          <h1>Register</h1>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="registerform" method="POST">
            <?php if($error){
              echo '<div class="form-error"><i class="ion-alert-circled"></i>' . $error . '</div>';
            } ?>

            <div class="form-group">
              <label for="firstname">First Name</label>
              <input type="text" name="firstname" id="firstname" required>
              <?php if($errorF){
               echo '<div class="form-error"><i class="ion-alert-circled"></i>' . $errorF . '</div>';
              }?>
            </div>

            <div class="form-group">
              <label for="lastname">Last Name</label>
              <input type="text" name="lastname" id="lastname" required>
              <?php if($errorL){
               echo '<div class="form-error"><i class="ion-alert-circled"></i>' . $errorL . '</div>';
              }?>
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" name="email" id="email" required>
              <?php if($errorEmail){
               echo '<div class="form-error"><i class="ion-alert-circled"></i>' . $errorEmail . '</div>';
              }?>
            </div>

            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" required>
              <?php if($errorPass){
                echo '<div class="form-error"><i class="ion-alert-circled"></i>' . $errorPass . '</div>';
              }?>
            </div>

            <div class="form-group">
              <label for="cpassword">Confirm Password</label>
              <input type="password" name="cpassword" id="cpassword" required>
              <?php if($errorCPass){
                echo '<div class="form-error"><i class="ion-alert-circled"></i>' . $errorCPass . '</div>';
              }?>
            </div>

            <div class="form-button-group">
              <button type="button" name="cancel" class="btn cancel">Clear</button>
              <button type="submit" name="submit" class="btn submit">Register</button>
            </div>
          </form>
      </div>
  </section>
  <script>
    $(".loginform").validate();
  </script>
<?php include('../includes/footer.php'); ?>
