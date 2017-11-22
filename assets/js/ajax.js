// All AJAX calls are in this file
// -------------- EVENTS -------------- //
// on DOM ready
$('document').ready(function(){
  // Ajax call when eventForm is submitted
  $('#eventForm').on('submit', function(e){
    // prevent the form from submitting as usual
    e.preventDefault();
    // make the ajax call to the php script
    // pass data of element using .serialize() method
    $.ajax({
      url: "../../php/add_event.php",
      type: "POST",
      data: $(this).serialize()
    }).done(function(data){
      // if error back
      if(data == 'timeEr'){
        // VALIDATION: display error message
        $('#timeEr').show().html("Invalid time format, please enter a HH:mm format.");
        $('.submit').html("Submit");
      } else if (!data) {
        // on success, change the text of the button for user experience
        $('.submit').html("Adding...");
        // reload page after 2 seconds to fetch the added event
        setTimeout(function(){
             location.reload();
        }, 2000);
      } else {
        // reset submit button
        $('.submit').html("Submit");
      }

    });
  });//submit end
});

// -------------- CONFERENCES -------------- //
// functions for UI elements
function deleteConf(id){
  // ajax call to the php script to delete conference
  // pass the conference id
  $.ajax({
		url: "../../php/delete_conference.php",
		type: "POST",
		data: { id: id }
  }).done(function(data){
    //console.log(data);
    // redirect
    window.location.assign('feed.php');
  });
}

//wait for DOM ready to bind 'click' event
$(document).ready(function(){

  $('body').on('click', '.not-going', function(e){
    // prevent default behaviour of element
    e.preventDefault();
    // get conference id and user id from data attributes
    confid = $(this).data('conf');
    userid = $(this).data('user');
    // save the element that was clicked
    var el = $(this);
    // log to console for testing purposes
    //console.log("not active  " + confid + "  " + userid);

    $.ajax({
  		url: "../../php/go_conference.php",
  		type: "POST",
  		data: { confid: confid, userid: userid }
    }).done(function(){
      console.log(el);
      el.removeClass('not-going').addClass('going');
      el.css('background-color', '#b3e5ff');
      el.find('i').removeClass('ion-ios-checkmark-outline').addClass('ion-ios-checkmark');
     });;
  });

  $('body').on('click', '.going', function(e){
    // prevent default behaviour of element
    e.preventDefault();
    // get conference id and user id from data attributes
    confid = $(this).data('conf');
    userid = $(this).data('user');
    // save the element that was clicked
    var el = $(this);
    // log to console for testing purposes
    //console.log("not active  " + confid + "  " + userid);

    $.ajax({
      url: "../../php/ngo_conference.php",
      type: "POST",
      data: { confid: confid, userid: userid }
    }).done(function(){
      console.log(el);
      el.removeClass('going').addClass('not-going');
      el.css('background-color', '#fff');
      el.find('i').removeClass('ion-ios-checkmark').addClass('ion-ios-checkmark-outline');
     });;
  });

  $('body').on('click', '#icon-delete-go', function(e){
    e.preventDefault();
    confid = $(this).data('conf');
    userid = $(this).data('user');
    var el = $(this);
    //console.log("active " + confid + "  " + userid);
    $.ajax({
  		url: "../../php/ngo_conference.php",
  		type: "POST",
  		data: { confid: confid, userid: userid }
    }).done(function(){
      //console.log(el);
      el.hide();
    });
  });

  $('body').on('click', '#icon-delete-go', function(e){
    e.preventDefault();
    confid = $(this).data('conf');
    userid = $(this).data('user');
    var el = $(this);

    $.ajax({
  		url: "../../php/ngo_conference.php",
  		type: "POST",
  		data: { confid: confid, userid: userid }
    }).done(function(){
      // GET parent div by classname and slideup to hide
      el.parents('div.divider').slideUp();
    });
  });

  $('body').on('click', '#icon-delete-like', function(e){
    e.preventDefault();
    confid = $(this).data('conf');
    userid = $(this).data('user');
    var el = $(this);

    $.ajax({
  		url: "../../php/dislike_conference.php",
  		type: "POST",
  		data: { confid: confid, userid: userid }
    }).done(function(){
      // GET parent div by classname and slideup to hide
      el.parents('div.divider').slideUp();
    });
  });

  $('body').on('click', '.like', function(e){
    // prevent default behaviour of element
    e.preventDefault();
    // get conference id and user id from data attributes
    confid = $(this).data('conf');
    userid = $(this).data('user');
    // save the element that was clicked
    var el = $(this);
    // log to console for testing purposes
    //console.log("not active  " + confid + "  " + userid);

    $.ajax({
      url: "../../php/like_conference.php",
      type: "POST",
      data: { confid: confid, userid: userid }
    }).done(function(){
      el.removeClass('like').addClass('dislike');
      el.css('background-color', '#b3e5ff');
      el.find('i').removeClass('ion-ios-star-outline').addClass('ion-ios-star');
     });;
  });

  $('body').on('click', '.dislike', function(e){
    // prevent default behaviour of element
    e.preventDefault();
    // get conference id and user id from data attributes
    confid = $(this).data('conf');
    userid = $(this).data('user');
    // save the element that was clicked
    var el = $(this);
    // log to console for testing purposes
    //console.log("not active  " + confid + "  " + userid);

    $.ajax({
      url: "../../php/dislike_conference.php",
      type: "POST",
      data: { confid: confid, userid: userid }
    }).done(function(){
      el.removeClass('dislike').addClass('like');
      el.css('background-color', '#fff');
      el.find('i').removeClass('ion-ios-star').addClass('ion-ios-star-outline');
     });;
  });
});

// -------------- SEARCH -------------- //
// wait for DOM to be ready
$(document).ready(function(){
  // DECLARE new array, call it res
    var res = new Array();

    // get the input field of the search box
    // callback on keyup input
    $('.search-box input[type="text"]').on("keyup input", function(){
        // get the value within the field
        var inputVal = $(this).val();
        // get the element of results by its 'search-result' class
        var resultDropdown = $(".search-result");

        // if there is an input
        if(inputVal.length) {
          // perform GET request to php script
          // PASS the input value
          $.get("../php/search.php", {term: inputVal}).done(function(data){
                // use callback function to get the returned data
                // if any result found
                if(data){
                  // parse data and store it into the res array
                  res = JSON.parse(data);
                  // DECLARE empty string, call it html
                  var html = '';
                  // loop through res array
                  for(var i = 0; i < res.length; i++) {
                    // concatenate the title of the result to html
                    html += '<li>' + res[i].title + '</li>';
                  }
                  // add the results to the result element
                  resultDropdown.html(html);
                  // reset html string
                  html = '';
                } else {
                  // if no results, display a message
                  resultDropdown.html('<li class="not-found">No matches found.</li>');
                }
            });
        } else {
          // if no input value, empty whatever is in the results div
          resultDropdown.empty();
        }
    });

    // set search input value on click of result item
    $(document).on("click", ".search-result li", function(){
      // find the 'search box' input field and display the value
      $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
      // empty the results element (div)
      $(this).parent(".result").empty();
      // call function to redirect to the correct page
      goToPage($(this).text());
    });

    $('.search').keypress(function(e){
      if(e.which == 13) {
        // apply same logic of the above click event if user presses 'enter'
        goToPage($(this).val());
      }
    });

    // METHOD redirects user to page by it's title
    // param: string of the conference title
    function goToPage(title){
      // log to console for testing purposes
      // console.log(title.toString());

      // AJAX call to the php script that returns id of conference_title
      // pass title of conference
      $.ajax({
        url: "../../php/gotoPage.php",
        type: "POST",
        data: {title: title}
      }).done(function(data){
        // if id found, redirect user to the correct page
        if(data) {
          window.location.assign('../pages/conference_details.php?id=' + data);
        }
      });
    }
});

// -------------- UPDATE USER SETTINGS -------------- //
// METHOD update text
// params: obj - element, column - name of column in database
function update(obj, column) {
		// AJAX call to the php script that updates the data
		$.ajax({
			url: "../../php/save_edit.php",
			type: "POST",
			data: {
				col: column, val: obj.innerHTML
			}
		});
}
