function displayURL(e) {
  var filename = $(e).val().split('\\').pop();
  filename = filename.substr(0,15) + '...';
  $('#upload_file').html(filename);
}

function displayEvent(e){
  $('#event-form').slideDown();
  // hide button
  $(e).hide();
}

function showSearch(e){
  if($(e).hasClass('ion-ios-search-strong')){
    // hide all links and logo
    $(e).parents('ul').children('li').children('a').hide();
    $('.sidebar-toggle').hide();
    $('.logo').fadeOut();
    // change icon class of search button
    $(e).removeClass('ion-ios-search-strong').addClass('ion-close');
    // move icon to right
    $('.mobile .search-box').css('max-width', '90%');
    $('.mobile .search-box').show();
    $('.mobile .search').css('padding', '5px 20px');
    $('.mobile .search').show();
    // show search bar
    $('.mobile .search-box').animate({
      width: '90%'
    }, 500, function(){});
  } else {
    // hide search bar
    $('.mobile .search-box').animate({
      width: '0'
    }, 500, function(){
      // hide search box
      $('.mobile .search-box').css('max-width', '0');
      $('.mobile .search-box').hide();
      $('.mobile .search').hide();
      // change icon class of search button
      $('#showSearch').removeClass('ion-close').addClass('ion-ios-search-strong');
      // show all links and logo
      $('#showSearch').parents('ul').children('li').children('a').fadeIn();
      $('.sidebar-toggle').fadeIn();
      $('.logo').fadeIn();
    });
  }
}

$(document).ready(function(){

  // DECLARE datepicker, part of jQuery UI
  $('.date-pick').datepicker({
    // set alternative field for database storage
    altField : "#actualDate",
    // in appropiate format
    altFormat: "yy-mm-dd",
    //set format
    dateFormat: "dd/mm/yy",
    //set min date
    //minDate: new Date(2017, 1 - 1, 1)
  });


  // Postcode lookup config
  $('#lookup_field').setupPostcodeLookup({
    // Add API key
    api_key: 'ak_j21t99qsSvfF5FczkoG2M0F75M1vv',
    // Identify fields with CSS selectors
    output_fields: {
      line_1: '#first_line',
      line_2: '#second_line',
      line_3: '#third_line',
      post_town: '#post_town',
      postcode: '#postcode'
    }
  });

  // SET click event to toggle sidebar on mobile
  $('.sidebar-toggle').on('click', function(){
      $('.sidebar').toggle('fast');
  });

  $('.cancel').on('click', function(){
    // CLEAR everything on cancel
    $(this).closest('form').find("input[type=text], input[type=email], input[type=password], textarea").val("");
  });

  $('#event-form .cancel').on('click', function(){
    // SLIDE up form
    $('#event-form').slideUp();
    // Show 'add event' button
    $('#add-event').show();
  });

  $('.ion-navicon').on('click', function(){
    $('.mobile > ul').slideDown();
  });

  // VALIDATION
  $.validator.methods.email = function( value, element ) {
    return this.optional( element ) || /[a-z]+@[a-z]+\.[a-z]+/.test( value );
  }

  $.validator.addMethod('password', function( value, element ) {
    return (this.optional( element ) || /[0-9]+/.test( value ) &&
            this.optional( element ) || /[A-Z]+/.test( value ) &&
            this.optional( element ) || /[a-z]+/.test( value ) &&
            this.optional( element ) || /[!@#$%^&*()]+/.test( value ) );
  }, "Password requires at least one capital, number or special character");

  $.validator.addMethod('confirm_password', function( value, element ) {
    return (value == $('#password').val());
  }, "Passwords do not match");

  $.validator.messages.remote = "This email address is already in use.";

  $(".registerform").validate({
    rules: {
      firstname: {
        required: true,
        rangelength: [3, 15]
      },
      lastname: {
        required: true,
        rangelength: [3, 15]
      },
      email: {
        required: true,
        email: true,
        remote: {
          url: "../php/check_email.php",
          type: "POST",
          dataType:"JSON",
          data: {
            email: function() {
              return $("#email").val();
            }
          }
        }
      },
      password: {
        required: true,
        password: true,
        rangelength: [3, 10]
      },
      cpassword: {
        required: true,
        confirm_password: true,
        rangelength: [3, 10]
      }
    }
  });

  $(".eventform").validate({
    rules: {
      title: {
        required: true
      },
      description: {
        rangelength: [3, 500]
      },
      speaker: {
        required: true
      },
      time: {
        required: true
      }
    }
  });

  $(".addconf").validate({
    rules: {
      title: {
        required: true
      },
      description: {
        rangelength: [3, 500]
      },
      date: {
        required: true
      },
      postcode: {
        required: true
      },
      loacation: {
        required: true
      }
    }
  });

});
