jQuery(document).ready(function() {

  // Remplacement des liens de la pagination pour un appel en ajax
  $('.pagination a').live('click', function(e) {
		$('.ajax-progress').show();

    // Bloc parent du lien cliqué pour mise à jour future
    var content = $(this).closest('.tab-pane');

    $.ajax({
       type: "GET",
       url: $(this).attr('href'),
       success: function(html) {
        // Mise à jour du bloc parent du lien cliqué
        $(content).html(html);
        $('.ajax-progress').hide();
        }
    });

    // Empêche le lien de fonctionner normalement
    return false;
  });
});
