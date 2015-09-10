function addText(button) {
    $(button).hide();
    $(button).after("<div class='more-text'><input id='addText' type='text'><a class='button add'>Add</a></div>");
    
    $(".button.add").click(function() {
        var text = $('#addText').val();
        $('div.more-text').hide();
        $('.day-one div.my-input').append('<p>' + text + '</p>');
        $(button).show();
    })
}

function addImage(button) {
    $(button).hide();
    $(button).after("<div class='more-image'><input id='addImage' type='text'><a class='button add'>Add</a></div>");
    
    $(".button.add").click(function() {
        var text = $('#addImage').val();
        $('div.more-image').hide();
        $('.day-one div.my-input').append('<img src="' + text + '">');
        $(button).show();
    })
}

function addSound(button) {
    $(button).hide();
    $(button).after("<div class='more-sound'><input id='addSound' type='text'><a class='button add'>Add</a></div>");

    $(".button.add").click(function() {
        var text = $('#addSound').val();
        $('div.more-sound').hide();
        $('.day-one div.my-input').append(text);
        $(button).show();
    })
}

$(function() {
  $(".button.get_lhs").click(function() {
      var dataString = [];
          dataString[0] = $("input#flightnumber-from").val();
          dataString[1] = $("input#date-from").val();
          dataString[2] = $("input#flightnumber-to").val();
          dataString[3] = $("input#date-to").val();

    $.ajax({
      type: "GET",
      url: "bin/call_lhs.php",
      data: {dataString:dataString},
      success: function(data) {
        $(".wrapper").empty();
        $(".center").hide();
        $("html").removeClass('image');
        $(".wrapper").html( data );
      }
     });
    return false;
  });

});

$(document).ready(function() {
    $('#date-from, #date-to').datepicker({
        dateFormat: "yy-mm-dd"
    });
});

$(document).ajaxComplete(function() {

});
