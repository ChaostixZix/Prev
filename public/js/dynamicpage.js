 (function($){
    var $mainContent = $("#content");
    var path = window.location.pathname.split('/');
    
    $(document).on("click", "[data-href]", function(event) {
      _link = $(this).attr("href");
      window.history.pushState(null, null, _link);
      loadContent(_link);
      return false;
      event.stopImmediatePropagation();
    });

    function loadContent(href){
        _link = window.location.pathname.replace(/^.*[\\\/]/, '');
        $("#main").fadeOut(200, function() {
              $("#main").hide().load(href + " .main", function(data) {
                  $('#main').show();
                  $(".bottom-bar li").removeClass('active');
                  $(".bottom-bar li").each(function() {
                      if ($(this).find('a').attr('route') == window.location.href) {
                          $(this).addClass("active");
                      }
                  });
                  $(".profile-block-card").removeClass('active');
                  $(".profile-block-card").each(function() {
                      if ($(this).find('a').attr('route') == window.location.href) {
                          $(this).addClass("active");
                      }
                  });
                  $('html').removeClass('pointer-event-0');
               });
         
         });
    }
    
    $(window).bind('popstate', function(){
       _link = window.location.pathname.replace(/^.*[\\\/]/, ''); //get filename only
       loadContent(_link);
    });
})(jQuery);