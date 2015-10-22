$(function() {
  $(".button.get-beacon").click(function() {
    var dataString = '';
    
    $.ajax({
      type: "POST",
      url: "bin/call_beacon.php",
      data: dataString,
      success: function(data) {
        $(".wrapper").empty();
        $(".wrapper").html( data );
      }
     });
    return false;
  });

  $(".button.get-twitter").click(function() {
    var dataString = '';
    
    $.ajax({
      type: "POST",
      url: "bin/twitter-proxy.php?url="+encodeURIComponent("statuses/home_timeline.json?screen_name=2welten&count=100"),
      data: dataString,
      success: function(data) {
        $(".wrapper").empty();
        $(".wrapper").html( data );
      }
     });
    return false;

  });
});

$(document).ready(function() {

  $('input[type="checkbox"]').change( function() {
      var checkboxClass = $(this).find('+label').attr('class');
      if( $(this).is(':checked') ){
          $('.wrapper').find("."+checkboxClass).parent().parent().show();
      } else {
          $('.wrapper').find("."+checkboxClass).parent().parent().hide();
      }

  });
});

$(document).ajaxComplete(function() {

  $(".product-image-list img.product-image").click(function() {
    if ( $(this).hasClass('active') ) {
      $(this).removeClass('active');
    } else {
      $(this).addClass('active');
    }
  });

  $(".button.submit-listing_ids").click(function() {

    var dataString = 'product_ids=';

    $("img.product-image.active").each(function () {
      dataString = dataString + $(this).attr('data-id') + ',';
    });
    
    $.ajax({
      type: "POST",
      url: "bin/display_details.php",
      data: dataString,
      success: function(data) {
        $("#success > div").replaceWith( data );
      }
     });
    return false;
  });

  $(".button.submit-get_pdf").click(function() {

	  var name = $("#success").html();

		var dataString = 'html='+ name;
		
		$.ajax({
      type: "POST",
      url: "bin/process_pdf.php",
      data: dataString,
      success: function(data) {
        window.location.href = data;
        var output = '<div class="result-list"><header><h1>Your pdf was created.</h1><header></div>'
        $("#success").html( output );
      }
     });
    return false;
  });

});
