var j = jQuery.noConflict();

(function($){
  // Al posicionarnos encima de un post nos mostrará las opciones que podemos realizar con el post (Editar, Papelera, Ver)
  j(document).on({
    mouseenter: function() {
      j(this).find('.opt-post').show();
    },
    mouseleave: function() {
      j(this).find('.opt-post').hide();
    }
  }, '.view-option-post')

  j('.content').load(_root_ + 'admin/terms/displayTermsAjax');

  j(document).on('click', ".pagination-digg li a", function(e) {
    e.preventDefault();
    var href = j(this).attr("href");
    j('.content').load(href);
  })

  j(document).on('click', ".link-ajax", function(e) {
    e.preventDefault();
    var href = j(this).attr("href");
    j('.content').load(href);
  })

  j(document).on('click', '#goto-btn', function(ev){
    ev.preventDefault();
    var $page = parseInt(j('.goto').val());
    var $no_of_pages = parseInt(j(this).data('maxpage'));
    if ($page > 0 && $page <= $no_of_pages) {
      var href = j(this).data('href');
      var $offset = ($page - 1) * parseInt(j(this).data('limit'));
      j('.content').load(href + $offset);
    } else {
      return false;
    }
  });

  j(document).on('click', '#search-btn', function(ev){
    ev.preventDefault();
    var href = j(this).data('href');
    var search = j(this).parent().prev().val();
    j('.content').load(href + search);
  });
})(jQuery);