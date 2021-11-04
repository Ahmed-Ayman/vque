$(document).ready(function () {

  $('#problem').keyup(function (){
    var ajax_url = "/home/check-suggestions/" + $(this).val() +"/"+$('#user_mobile').val();
    if($(this).val().length>0){
      $.ajax({url: ajax_url, success: function(result) {
          if(result){
            $('#show-all-solutions').attr('href',ajax_url)
            $('#show-all-solutions').removeClass('hidden');
          }else{
            $('#show-all-solutions').addClass('hidden');
            $('#show-all-solutions').attr('href', '#');
          }
        }});
    }else{
      $('#show-all-solutions').addClass('hidden');
    }
  })
  $('#problem, #user_mobile').change(function(){
    if($('#problem').val().length > 0 && $('#user_mobile').val().length > 0){
      console.log( 'removeClass')

      $('#reservation').removeClass('disabled');
    }else{
      console.log( 'addClass')
      $('#reservation').addClass('disabled');
    }

  })
});
