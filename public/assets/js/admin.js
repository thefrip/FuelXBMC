jQuery(document).ready(function() {
  // Avoid admin to delete his own user account
  $(".disabled-link").click(function(event) {
    event.preventDefault();
  });

  $('#warning_no_albums').css('display', 'none');
  $('#warning_no_movies').css('display', 'none');
  $('#warning_no_tvshows').css('display', 'none');
  $('#warning_no_episodes').css('display', 'none');

  if ($('#no_movies').attr('checked'))
  {
    $('#warning_no_movies').css('display', '');
    $('#input_last_movies').addClass('uneditable-input');
    $('#input_last_movies').prop('disabled', true);
    $('#group_manage_sets').css('display', 'none');
  }

  if ($('#no_tvshows').attr('checked'))
  {
    $('#warning_no_tvshows').css('display', '');
    $('#input_last_tvshows').addClass('uneditable-input');
    $('#input_last_tvshows').prop('disabled', true);
    $('#warning_no_episodes').css('display', '');
    $('#input_last_episodes').addClass('uneditable-input');
    $('#input_last_episodes').prop('disabled', true);
  }

  // Handle warning display for media no selection
  $("input[type='radio']").change(function() {

      if (this.id)
      {
        var a = this.id.split('_');

        if (a[0] == 'no')
        {
          $('#warning_'+this.id).css('display', '');
          $('#input_last_'+a[1]).addClass('uneditable-input');
          $('#input_last_'+a[1]).prop('disabled', true);
          if (a[1] == 'movies')
          {
            $('#group_manage_sets').fadeOut("hide");
          }
          if (a[1] == 'tvshows')
          {
            $('#input_last_episodes').addClass('uneditable-input');
            $('#input_last_episodes').prop('disabled', true);
            $('#warning_no_episodes').css('display', '');
          }
        }
        else
        {
          $('#warning_no_'+a[1]).css('display', 'none');
          $('#input_last_'+a[1]).removeClass('uneditable-input');
          $('#input_last_'+a[1]).prop('disabled', false);
          if (a[1] == 'movies')
          {
            $('#group_manage_sets').fadeIn("slow");
          }
          if (a[1] == 'tvshows')
          {
            $('#input_last_episodes').removeClass('uneditable-input');
            $('#input_last_episodes').prop('disabled', false);
            $('#warning_no_episodes').css('display', 'none');
          }
        }
      }
  });

});
