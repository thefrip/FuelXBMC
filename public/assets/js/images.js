$(function () {
    'use strict';

    var getCSS = function (prop, fromClass) {

        var $inspector = $("<div>").css('display', 'none').addClass(fromClass).prepend('<img src="'+site_url+'assets/css/ajax-loader.gif" />');
        $("body").append($inspector); // add to DOM, in order to read the CSS property
        try {
            return $inspector.css(prop);
        } finally {
            $inspector.remove(); // and remove from DOM
        }
    };

    // Open select thumb modal button:
    $('#select-thumb').button().click(function () {
        var options = $(this).data(),
            modal = $(options.target),
            data = modal.data('modal');
        if (data) {
            $.extend(data.options, options);
        } else {
            options = $.extend(modal.data(), options);
        }
        modal.modal(options);
    });

    // Open select banner modal button:
    $('#select-banner').button().click(function () {
        var options = $(this).data(),
            modal = $(options.target),
            data = modal.data('modal');
        if (data) {
            $.extend(data.options, options);
        } else {
            options = $.extend(modal.data(), options);
        }
        modal.modal(options);
    });

    // Open select poster modal button:
    $('#select-poster').button().click(function () {
        var options = $(this).data(),
            modal = $(options.target),
            data = modal.data('modal');
        if (data) {
            $.extend(data.options, options);
        } else {
            options = $.extend(modal.data(), options);
        }
        modal.modal(options);
    });

    // Open select fanart modal button:
    $('#select-fanart').button().click(function () {
        var options = $(this).data(),
            modal = $(options.target),
            data = modal.data('modal');
        if (data) {
            $.extend(data.options, options);
        } else {
            options = $.extend(modal.data(), options);
        }
        modal.modal(options);
    });

    $(".modal-select").click(function(event) {
      var image_link = this.href;
      var image_type = this.getAttribute('rel');
      var modal_gallery = $(this).closest(".modal-gallery");
      event.preventDefault();
      $(modal_gallery).modal('hide');

      // add loading spinner to parent setting id
      $('#'+image_type).parent().attr('id', 'loaded-image');

      // and change size
      $('#'+image_type).parent().css({
          width: getCSS('width', image_type),
          height: getCSS('height', image_type)
      });

      $('#'+image_type).remove();

      var arr = { media: media_id,
                  type: image_type,
                  url: image_link
                };

      // get token value from cookie
      var token = fuel_csrf_token();

      // change image only if token is found
      if (token != '')
      {
        $.ajax({
          url: site_url+'media/change_image/'+token,
          type: 'POST',
          data: JSON.stringify(arr),
          contentType: 'application/json; charset=utf-8',
          dataType: 'json',
          async: false,
          success : function(data) {
              var timestamp = new Date().getTime();
              var img = new Image();

              // wrap our new image in jQuery, then:
              $(img)
                // once the image has loaded, execute this code
                .load(function () {
                  // set the image hidden by default
                  $(this).hide();

                  // with the holding div with class modal_loading, apply:
                  $('#loaded-image')

                    // remove the loading class resetting id,
                    .attr('id', '')
                    // then insert our image
                    .append(this)
                    // remove size
                    .css('width', '')
                    .css('height', '');

                  // fade our image in to create a nice effect
                  $(this).fadeIn();
                })

                // set the id
                .attr('id', data.type)

                // *finally*, set the src attribute of the new image to our image
                .attr('src', data.url + '?' +timestamp);

              if (data.type == 'fanart')
              {
                $('.opacity').css('background-image', "url('" + data.url + "')");
              }
          }
        });
      }

    });

});
