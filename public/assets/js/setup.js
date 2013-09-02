$(function () {
    'use strict';

    // Check database settings via ajax
    $('#btn_check_db').click(function (e) {

      // Disable button during ajax call
      $('#btn_check_db').prop('disabled', true);
      $('#status_db').addClass('loading');
      $('#status_db').html(loading);

      var host_ip = $('#input_host_ip').val();
      var username = 'root';
      var password = $('#input_password').val();

      var arr = { host_ip: host_ip,
                  username: username,
                  password: password
                };

      $.ajax({
        url: site_url+'setup/check_db',
        type: 'POST',
        data: JSON.stringify(arr),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        success : function(data) {
            // Enable button for new check
            $('#btn_check_db').prop('disabled', false);

            // Remove ajax loader and display status message
            $('#status_db').removeClass('loading');
            $('#status_db').html(data.message);
            if (data.success == '1')
            {
              $('#btn_next_step').addClass('btn-success');
              $('#btn_next_step').removeClass('btn-primary');
              $('#btn_next_step').removeAttr('disabled');
            }
        }
      });
      e.preventDefault(); // prevents default
    });

});
