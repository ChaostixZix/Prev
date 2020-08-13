(function($){
  "use strict";
    $(document).on('click', '.dark-mode', function() {
      if ($('body').hasClass('background-dark')) {
          $(this).find('em').removeClass('ni-sun');
          $(this).find('em').addClass('ni-moon');
      } else {
          $(this).find('em').removeClass('ni-moon');
          $(this).find('em').addClass('ni-sun');
      }
      if ($('body').hasClass('background-dark')) {
        sessionStorage.setItem('background', 'light');
        $('body').removeClass('background-dark');
        $(this).removeClass('on');
        $('body').addClass('theme-background');
      }else{
        sessionStorage.setItem('background', 'dark');
        $('body').addClass('background-dark');
        $('body').removeClass('theme-background');
        $(this).addClass('on');
      }
      return false;
    });
    if (sessionStorage['background'] == 'dark') {
       document.getElementById("body").className += " background-dark";
       $('.dark-mode').addClass('on');
       $('.dark-mode').find('em').removeClass('ni-moon');
       $('.dark-mode').find('em').addClass('ni-sun');
    }
})(jQuery);