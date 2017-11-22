<?php
  include('includes/header.php');
  include('includes/navbar.php');

  require_once('php/classes/users.class.php');
  $userObj = new Users($db);
?>
  <header role="header" class="header">
    <h1>Share and discover ideas</h1>
    <section>
      <div class="search-box">
        <input type="text" autocomplete="off" placeholder="Search" class="search"/>
        <ul class="search-result"></ul>
      </div>
    </section>
  </header>
<?php include('includes/footer.php'); ?>
