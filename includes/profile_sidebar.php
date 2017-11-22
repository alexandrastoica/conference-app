<aside class="sidebar">
  <div class="sub-sidebar center">
    <?php if($user['user_img']) {?>
    <div class="profile-pic" title="Profile Picture" style="background-image: url('../uploads/profile/<?php echo $img["user_img"] ?> ');"> </div>
    <?php } else { ?>
    <i class="ion-person"></i>
    <?php } ?>
    <h3> <?php echo $user['user_firstname'] . ' ' . $user['user_lastname'] ?> </h3>
    <ul>
      <li class="title-li">Conferences</li>
      <li>
        <a href="add.php" alt="Publish a conference">Publish</a>
      </li>
      <li>
        <a href="manage.php" alt="Manage conferences">Manage</a>
      </li>
      <li>
        <a href="organise.php" alt="Organise conferences">Organise</a>
      </li>

      <li class="title-li">Settings</li>
      <li>
        <a href="profile.php" alt="Edit user details">Details</a>
      </li>
      <li>
        <a href="logout.php" alt="Logout">Sign Out</a>
      </li>
    </ul>
  </div>
</aside>
