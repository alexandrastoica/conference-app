<?php if(isset($_SESSION['loggedin'])){
  try{
    // PREPARE query
    $pdo = $db->prepare("SELECT user_img FROM users WHERE user_id = :id");
    // BIND user id
    $pdo->bindParam(':id', $_SESSION['user_id']);
    // EXECUTE query
    $pdo->execute();
    // FETCH result and store it into 'img'
    $img = $pdo->fetch(PDO::FETCH_ASSOC);

  } catch (Exception $e) {
    // THROW EXCEPTION
    echo $e->getMessage();
  }
?>
<div class="navbar">
  <!-- IF: on desktop AND logged in-->
  <div class="sub-navbar desktop">
    <!--- Logo --->
    <div class="logo">
      <a href="../index.php" alt="Link to homepage"><i class="ion-planet"></i><span></a>Sharing ideas</span>
    </div>
    <!--- Search Bar --->
    <!-- IF: on index.php, do not show -->
    <?php if(!($_SERVER['REQUEST_URI'] == '/index.php' || $_SERVER['REQUEST_URI'] == '/')){?>
    <div class="search-box">
      <input type="text" autocomplete="off" placeholder="Search" class="search"/>
    </div>
    <ul class="search-result"></ul>
    <?php } ?>
    <!--- Links --->
    <ul class="nav-links">
      <li><a href="../pages/feed.php" alt="Link to feed"><i class="ion-ios-list"><span>Discover</span></i></a></li>
      <?php // IF there is an user_img, display it
      if($img['user_img']) {?>
        <li><a href="../pages/profile.php" alt="Link to profile">
          <div class="profile-pic" title="Profile Picture" style="background-image: url('../uploads/profile/<?php echo htmlspecialchars($img["user_img"]); ?>');"></div>
        </a></li>
      <?php } else { // ELSE display the default icon ?>
        <li><a href="../pages/profile.php" alt="Link to profile"><i class="ion-person"></i><span>Profile</span></a></li>
      <?php } ?>
    </ul>
  </div>

  <!-- IF: on mobile AND logged in -->
  <div class="sub-navbar mobile">
    <!--- Logo --->
    <div class="logo">
      <a href="../index.php" alt="Link to homepage"><i class="ion-planet"></i></a>
    </div>
    <!--- SIDEMENU: only on profile page and its subpages --->
    <?php if($_SERVER['REQUEST_URI'] == '/pages/profile.php' || $_SERVER['REQUEST_URI'] == '/pages/manage.php' ||
      $_SERVER['REQUEST_URI'] == '/pages/organise.php' || $_SERVER['REQUEST_URI'] == '/pages/add.php'){ ?>
      <div class="sidebar-toggle mobile">
        <a href="#" alt="Open sidebar"><i class="ion-navicon"></i></a>
      </div>
    <?php } ?>
    <!--- Search Bar --->
    <!-- IF: on index.php, do not show -->
    <?php if(!($_SERVER['REQUEST_URI'] == '/index.php' || $_SERVER['REQUEST_URI'] == '/')){?>
    <div class="search-box">
      <input type="text" autocomplete="off" placeholder="Search" class="search"/>
    </div>
    <ul class="search-result"></ul>
    <?php } ?>
    <!--- Links --->
    <ul class="nav-links">
      <!-- Do not show on index.php -->
      <?php if(!($_SERVER['REQUEST_URI'] == '/index.php' || $_SERVER['REQUEST_URI'] == '/')){?>
      <li><i id="showSearch" class="ion-ios-search-strong" onClick="showSearch(this)"></i></li>
      <?php } ?>

      <li><a href="../pages/feed.php" alt="Link to feed"><i class="ion-ios-list"></i></a></li>

      <?php // IF there is an user_img, display it
      if($img['user_img']) {?>
        <li><a href="../pages/profile.php" alt="Link to profile">
          <div class="profile-pic" title="Profile Picture" style="background-image: url('../uploads/profile/<?php echo htmlspecialchars($img["user_img"]); ?>');"></div>
        </a></li>
      <?php } else { // ELSE display the default icon ?>
        <li><a href="../pages/profile.php" alt="Link to profile"><i class="ion-person"></i></a></li>
      <?php } ?>
    </ul>
  </div>
</div>
<?php } else { ?>
  <div class="navbar">
  <!-- IF: on desktop AND not logged in -->
  <div class="sub-navbar desktop">
    <!--- Logo --->
    <div class="logo">
      <a href="../index.php" alt="Link to homepage"><i class="ion-planet"></i></a><span>Sharing ideas</span>
    </div>
    <!--- Search Bar --->
    <!-- IF: on index.php, do not show -->
    <?php if(!($_SERVER['REQUEST_URI'] == '/index.php' || $_SERVER['REQUEST_URI'] == '/')){?>
    <div class="search-box">
      <input type="text" autocomplete="off" placeholder="Search" class="search"/>
    </div>
    <ul class="search-result"></ul>
    <?php } ?>
    <!--- Links --->
    <ul class="nav-links">
      <li><a href="../pages/feed.php" alt="Link to feed"><i class="ion-ios-list"><span>Discover</span></i></a></li>
      <li><a href="../pages/login.php" alt="Link to login page"><i class="ion-log-in"><span>Login</span></i></a></li>
      <li><a href="../pages/register.php" alt="Link to register page"><i class="ion-ios-unlocked"><span>Register</span></i></a></li>
    </ul>
  </div>

    <!-- IF: on mobile AND not logged in -->
    <div class="sub-navbar mobile">
      <!--- Logo --->
      <div class="logo">
        <a href="../index.php" alt="Link to homepage"><i class="ion-planet"></i></a>
      </div>
      <!--- Search Bar --->
      <!-- IF: on index.php, do not show -->
      <?php if(!($_SERVER['REQUEST_URI'] == '/index.php' || $_SERVER['REQUEST_URI'] == '/')){?>
      <div class="search-box">
        <input type="text" autocomplete="off" placeholder="Search" class="search"/>
      </div>
      <ul class="search-result"></ul>
      <?php } ?>
      <!--- Links --->
      <ul class="nav-links">
        <!-- Do not show on index.php -->
        <?php if(!($_SERVER['REQUEST_URI'] == '/index.php' || $_SERVER['REQUEST_URI'] == '/')){?>
        <li><i id="showSearch" class="ion-ios-search-strong" onClick="showSearch(this)"></i></li>
        <?php } ?>
        <li><a href="../pages/feed.php" alt="Link to feed"><i class="ion-ios-list"></i></a></li>
        <li><a href="../pages/login.php" alt="Link to login page"><i class="ion-log-in"></i></li>
        <li><a href="../pages/register.php" alt="Link to register page"><i class="ion-ios-unlocked"></i></a></li>
      </ul>
    </div>
  </div>


  </div>

<?php } ?>
