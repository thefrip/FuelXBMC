// prepare list of urls for genres
function get_html_links_genres(media_type)
{
  var links = new Array();

  // the same begin url for all links
  var begin_url = '<a class="label label-warning" href="'+site_url+media_type+'/genre/';

  $.each(json_all_genres, function(i, obj) {
      for(var key in selected_genres)
      {
        if (selected_genres[key] == obj.id)
        {
          links.push(begin_url+obj.id+'-'+obj.slug+'">'+obj.name+'</a>');
        }
      }

  });

  // replace the previous genres links
  $('#genres').html(links.join(' '));
  $('#genres').highlight();
}

// prepare list of urls for studios
function get_html_links_studios(media_type)
{
  var links = new Array();

  // the same begin url for all links
  var begin_url = '<a href="'+site_url+media_type+'/studio/';

  $.each(json_all_studios, function(i, obj) {
      for(var key in selected_studios)
      {
        if (selected_studios[key] == obj.id)
        {
          links.push(begin_url+obj.id+'-'+obj.slug+'">'+obj.name+'</a>');
        }
      }

  });

  // replace the previous studios links
  $('#studios').html(links.join(' / '));
  $('#studios').highlight();
}

// prepare list of urls for countries
function get_html_links_countries(media_type)
{
  var links = new Array();

  // the same begin url for all links
  var begin_url = '<a href="'+site_url+media_type+'/country/';

  $.each(json_all_countries, function(i, obj) {
      for(var key in selected_countries)
      {
        if (selected_countries[key] == obj.id)
        {
          links.push(begin_url+obj.id+'-'+obj.slug+'">'+obj.name+'</a>');
        }
      }

  });

  // replace the previous countries links
  $('#countries').html(links.join(' / '));
  $('#countries').highlight();
}

// prepare select certification html tag
function get_html_select_certification()
{
  // Me make a single select with this all
  var html = '<select id="select-certification" class="span12" name="certification" >';

  $.each(json_all_certifications, function(i, obj) {
      html += '<option value="'+obj.id+'"';

      if (selected_certification == obj.name)
      {
        html += ' selected="selected"';
      }

      html += '>'+obj.name+'</option>';
  });

  html += '</select>';

  // Add to the page
  $('#ajax-certification').html(html);
  $("#select-certification").select2();
}

// prepare select set html tag
function get_html_select_set()
{
  // Me make a single select with this all
  var html = '<select id="select-set" class="span12" name="set" >';

  $.each(json_all_sets, function(i, obj) {
      html += '<option value="'+obj.id+'"';

      if (selected_set == obj.id)
      {
        html += ' selected="selected"';
      }

      html += '>'+obj.name+'</option>';
  });

  html += '</select>';

  // Add to the page
  $('#ajax-set').html(html);
  $("#select-set").select2({
    minimumInputLength: 1
    });
}

// prepare select genres html tag
function get_html_select_genres()
{
  // Me make a multiple select with this all genres
  var html = '<select id="select-genres" class="span12" multiple="multiple" name="genres[]" >';

  $.each(json_all_genres, function(i, obj) {
      html += '<option value="'+obj.id+'"';

      for(var key in selected_genres)
      {
        if (selected_genres[key] == obj.id)
        {
          html += ' selected="selected"';
        }
      }

      html += '>'+obj.name+'</option>';
  });

  html += '</select>';

  // Add to the page
  $('#ajax-genres').html(html);
  $("#select-genres").select2({
    minimumInputLength: 4
    });
}

// prepare select studios html tag
function get_html_select_studios()
{
  // Me make a multiple select with this all studios
  var html = '<select id="select-studios" class="span12" multiple="multiple" name="studios[]" >';

  $.each(json_all_studios, function(i, obj) {
      html += '<option value="'+obj.id+'"';

      for(var key in selected_studios)
      {
        if (selected_studios[key] == obj.id)
        {
          html += ' selected="selected"';
        }
      }

      html += '>'+obj.name+'</option>';
  });

  html += '</select>';

  // Add to the page
  $('#ajax-studios').html(html);
  $("#select-studios").select2({
    minimumInputLength: 2
    });
}

// prepare select countries html tag
function get_html_select_countries()
{
  // Me make a multiple select with this all countries
  var html = '<select id="select-countries" class="span12" multiple="multiple" name="countries[]" >';

  $.each(json_all_countries, function(i, obj) {
      html += '<option value="'+obj.id+'"';

      for(var key in selected_countries)
      {
        if (selected_countries[key] == obj.id)
        {
          html += ' selected="selected"';
        }
      }

      html += '>'+obj.name+'</option>';
  });

  html += '</select>';

  // Add to the page
  $('#ajax-countries').html(html);
  $("#select-countries").select2({
    minimumInputLength: 4
    });
}

$(document).ready(function() {
    'use strict';

    // change class for some select boxes
    $("#select-year").select2({
      minimumInputLength: 3
    });

    // we need to the real type of this media
    var media_infos = media_id.split('_');

    // only if the media is a movie
    if (media_infos[0] == 'movie')
    {
      // only if exist else we use an inout html tag
      if ($("#select-runtime-hour").length)
      {
        $("#select-runtime-hour").select2({
          minimumInputLength: 1
        });
        $("#select-runtime-minute").select2({
          minimumInputLength: 1
        });
      }

      // prepare select set html tag
      get_html_select_set();

    }

    // prepare select certification html tag
    get_html_select_certification();

    // prepare select genres tag
    get_html_select_genres(selected_genres);

    // prepare select studios tag
    get_html_select_studios(selected_studios);

    // only if the media is a movie
    if (media_infos[0] == 'movie')
    {
      // prepare select countries html tag
      get_html_select_countries(selected_countries);
    }

    // manage cancel form submission
    $('#cancel-form-modal').click(function() {

      // hide the form and don't send data
      $('#media-modal').modal('hide');
    });

    // manage form submission
    $('#submit-form-modal').click(function() {

      // hide the form before sending data
      $('#media-modal').modal('hide');

      // appel Ajax
      $.ajax({
          url: site_url+'data/infos/'+media_id,
          type: 'POST',
          data: $('#form-modal').serialize(), // je sérialise les données (voir plus loin), ici les $_POST
          dataType: 'json',
          success: function(json) {

            var media_type = json.media_type;

            // local title?
            if (json.local_title !== undefined)
            {
              $('#local_title').html(json.local_title);
              $('#local_title').highlight();
            }

            // new overview?
            if (json.overview !== undefined)
            {
              $('#overview').html(json.overview);
              $('#overview').highlight();
            }

            // new tagline?
            if (json.tagline !== undefined)
            {
              $('#tagline').html(json.tagline);
              $('#tagline').highlight();
            }

            // new year?
            if (json.year !== undefined)
            {
              var url_year = site_url+media_type+'/year/'+json.year;
              var html = '<a href="'+url_year+'">'+json.year+'</a>';
              $('#year').append(html);
              $('#year').html(html);
              $('#year').highlight();
            }

            // new set for this movie or movie removed from set?
            if (json.set !== undefined)
            {
              $('#set').html(json.set);
              $('#set').highlight();
            }

            // new runtime?
            if (json.runtime !== undefined)
            {
              $('#runtime').html(json.runtime);
              $('#runtime').highlight();
            }

            // new certification?
            if (json.certification !== undefined)
            {
              $('#certification').html(json.certification);
              $('#certification').highlight();
            }

            // genres (new or not)
            if (json.genres !== undefined)
            {
              // change global variable value
              selected_genres = json.genres;
              // prepare select genres tag
              get_html_select_genres();

              // prepare list of urls for genres
              get_html_links_genres(media_type);
            }

            // studios (new or not)
            if (json.studios !== undefined)
            {
              // change global variable value
              selected_studios = json.studios;
              // prepare select studios tag
              get_html_select_studios();

              // prepare list of urls for studios
              get_html_links_studios(media_type);
            }

            // countries (new or not)
            if (json.countries !== undefined)
            {
              // change global variable value
              selected_countries = json.countries;
              // prepare select countries tag
              get_html_select_countries();

              // prepare list of urls for countries
              get_html_links_countries(media_type);
            }


          }
      });

      return false;
    });

});
