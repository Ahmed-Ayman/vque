$(document).ready(function () {

  $('#problem').keyup(function (){
    var ajax_url = "/home/check-suggestions/" + $(this).val() +"/"+$('#user_mobile').val();
    if($(this).val().length>0){
      $.ajax({url: ajax_url, success: function(result) {
          if(result){
            $('#show-all-solutions').removeClass('hidden');
          }else{
            $('#show-all-solutions').addClass('hidden');
          }
        }});
    }else{
      $('#show-all-solutions').addClass('hidden');
    }
  })
  $('#problem, #user_mobile').change(function(){
    if($('#problem').val().length > 0 && $('#user_mobile').val().length > 0){
      $('#reservation').removeClass('disabled');
    }else{
      $('#reservation').addClass('disabled');
    }

  })
});
