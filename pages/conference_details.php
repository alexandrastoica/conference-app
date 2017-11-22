<?php
  include('../includes/header.php');

  if($_SESSION['loggedin']){
    // STORE user id from session into 'user'
    $user = $_SESSION['user_id'];
    // DECLARE default variables
    $author = $editable = 0;
    $going = $interested = 0;
  }

  // instantiate Conferences object
  $confObj = new Conferences($db);
  // instantiate Events object
  $eventObj = new Events($db);

  // STORE conference id
  $conf_id = $_GET['id'];

  // DEFAULT error
  $error = '';

  // get conference details by id
  $conference = $confObj->getById($conf_id);
  // get events of conference
  $events = $eventObj->getByConferenceId($conf_id);

  //if conference details were found
  if($conference){
    // check if user is logged in
    if($_SESSION['loggedin']){
      // check if the author is the current user
      if($conference['user_id'] == $user){
        // change flag to 1
        $author = 1;
        // check if the page's state is editable, and change flag accordingly
        $editable = ((isset($_GET['edit'])) ? $_GET['edit'] : 0);
      }
      // check if user is going to this conference
      if($confObj->isGoing($conf_id, $user)){
        // change flag
        $going = 1;
      }
      // check if user is interested in this conference
      if($confObj->isInterested($conf_id, $user)){
        // change flag
        $interested = 1;
      }
    }
    // display navbar
    include('../includes/navbar.php');
?>
<section class="wrapper column">
  <?php if($_SESSION['loggedin'] && $author){ ?>
  <div class="float-button-group">
    <a href="conference_details.php?id=<?php echo $conf_id ?>" alt="Preview mode"><div class="btn round <?php if(!($editable == 1)){echo 'active';} ?>"><i class="ion-eye"></i></div></a>
    <a href="conference_details.php?id=<?php echo $conf_id ?>&amp;edit=1" alt="Edit mode"><div class="btn round <?php if($editable == 1){echo 'active';} ?>"><i class="ion-edit"></i></div></a>
  </div>
  <?php } ?>
  <div class="content center column">
    <!--CARD-->
    <div class="card">
      <div class="card-header">
        <div class="card-title">
          <h3>CONFERENCE</h3>
          <h4><?php echo $conference['conference_title'] ?></h4>
        </div>
      </div>

      <div class="card-detail">
        <p>
          <i class="ion-ios-location"> <?php echo $conference['conference_location'] . ", " . $conference['conference_postcode'] ?></i>
          <i class="ion-ios-calendar">
            <?php // convert date format into a more appropiate one
              $date = (new DateTime($conference['conference_date']))->format("d/m/Y");
              echo $date
            ?></i>
        </p>
        <p><?php echo $conference['conference_description'] ?></p>
      </div>

      <div id="post-map">
        <div id="map"></div>
      </div>

    </div>
    <!--end card-->

    <div class="post-event">
      <div class="form-button-group">
        <?php if($_SESSION['loggedin']){
            if($going == 1){?>
              <button type="button" id="going" class="btn cancel going" data-conf="<?php echo $conf_id ?>" data-user="<?php echo $user ?>"><i class="ion-ios-checkmark"></i>Going</button>
            <?php } else { ?>
              <button type="button" id="going" class="btn cancel not-going" data-conf="<?php echo $conf_id ?>" data-user="<?php echo $user ?>"><i class="ion-ios-checkmark-outline"></i>Going</button>
            <?php }
              if($interested == 1) { ?>
                <button type="button" id="like" class="btn cancel dislike" data-conf="<?php echo $conf_id ?>" data-user="<?php echo $user ?>"><i class="ion-ios-star"></i>Interested</button>
            <?php } else { ?>
                <button type="button" id="like" class="btn cancel like" data-conf="<?php echo $conf_id ?>" data-user="<?php echo $user ?>"><i class="ion-ios-star-outline"></i>Interested</button>
            <?php } ?>
      </div>
      <?php if($author == 1 && $editable == true){ ?>
      <div id="event-form" style="display: none">
        <form action="#" id="eventForm" method="post">
          <?php if($error){
            echo '<div class="form-error"><i class="ion-alert-circled"></i>' . $error . '</div>';
          } ?>
          <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" required>
          </div>

          <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" required></textarea>
          </div>

          <div class="form-group">
            <label for="speaker">Speaker</label>
            <input type="text" name="speaker" required>
          </div>

          <div class="form-group">
            <label for="speaker">Time (HH:MM)</label>
            <input type="text" name="time" required>
            <div class="form-error" id="timeEr" style="display:none"><i class="ion-alert-circled"></i></div>
          </div>

          <input type="hidden" name="id" value=<?php echo $conf_id ?> required>

          <div class="form-button-group">
          <button type="button" class="btn cancel">Cancel</button>
          <button type="submit" name="submit" class="btn submit">Add</button>
        </div>
        </form>
      </div>
      <div class="form-button-group">
        <button class="btn submit" type="button" id="add-event" onClick="displayEvent(this)"><i class="ion-plus-round"></i>Add Event</button>
        <button class="btn error-btn" type="button" onClick="deleteConf(<?php echo $conf_id ?>)"><i class="ion-minus-round"></i>Delete</button>
      </div>
      <?php }
      } // end if: logged in ?>

    </div> <!--postevent-->

    <h4>Schedule</h4>
    <?php if($events) {
      foreach($events as $key => $event) {?>
      <!--CARD-->
      <div class="card schedule">
        <div class="card-header">
          <div class="card-title">
              <h3>Event</h3>
              <h4><?php echo $event['event_title'] ?></h4>
          </div>
        </div>

        <div class="card-detail">
          <p>
            <i class="ion-ios-person"> <?php echo $event['event_speaker'] ?></i>
            <i class="ion-clock"> <?php echo date("H:i", strtotime($event['event_time'])) ?></i>
          </p>
          <p><?php echo $event['event_description'] ?></p>
        </div>
      </div>
      <!--end card-->
    <?php }
    } else {
      echo "<p>No events yet.<p>";
    } ?>
</section>
<?php
  // display footer
  include('../includes/footer.php');
}
?>
<script>
  // Google Maps API config
	var map;
  // usability methods:
  function enableScrollingWithMouseWheel() {
      map.setOptions({ scrollwheel: true });
  }

  function disableScrollingWithMouseWheel() {
      map.setOptions({ scrollwheel: false });
  }

	function initMap() {
		var geocoder = new google.maps.Geocoder();
		var myLatLng;
    // store postcode from database
		var postcode = "<?php echo htmlspecialchars($conference['conference_postcode']) ?>";
    // pass it as 'address'
		geocoder.geocode({'address': postcode}, function(result, status){

			if(status == google.maps.GeocoderStatus.OK){
				myLatLng = {lat: result[0].geometry.location.lat() , lng: result[0].geometry.location.lng()};
			} else{
				myLatLng = {lat: 52.1936 , lng: -2.2216};
			}

			map = new google.maps.Map(document.getElementById('map'), {
				center: myLatLng,
				zoom: 13,
        scrollwheel: false // disableScrollingWithMouseWheel as default
			});

      google.maps.event.addListener(map, 'mousedown', function(){
        enableScrollingWithMouseWheel();
      });

			var marker = new google.maps.Marker({
				position: myLatLng,
				map: map
			});
		});

    // CODE FROM: http://bdadam.com/blog/simple-usability-trick-for-google-maps.html
    google.maps.event.addDomListener(window, 'load', initMap);

    $('body').on('mousedown', function(event) {
        var clickedInsideMap = $(event.target).parents('#map').length > 0;

        if(!clickedInsideMap) {
            disableScrollingWithMouseWheel();
        }
    });

    $(window).scroll(function() {
        disableScrollingWithMouseWheel();
    });
	}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCUiwW6ZhmoeKhTAopcOd2Yx3HP5PKDVms&callback=initMap"
async defer></script>
