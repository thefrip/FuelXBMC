jQuery.fn.highlight = function() {
  var $e = this,
      bgColor = $e.css('background-color');

  $e.css('background-color', '#FFFF80');
  setTimeout(function(){
      if(bgColor === 'transparent') {
          bgColor = '';
      }
      $e.css('background-color', bgColor);
      $e.addClass('highlight');
      setTimeout(function(){
         $e.removeClass('highlight');
      }, 1700);
  }, 10);
};
