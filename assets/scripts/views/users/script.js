var j = jQuery.noConflict();

(function($){
  j(document).on("ready", function(){
    // Cargamos los datos con los suscriptores.
    j('.content').load(_root_ + 'admin/users/displayAjax');

    j('.iframe-btn').fancybox({
      'width' : 880,
      'minHeight' : 500,
      'type' : 'iframe',
      'autoScale' : false,
      'topRatio' : 0.3,
      beforeClose : function() {
        imgUrl = j('#fieldID').val();
        figure = j('.img-thumbs');
        if (figure.hasClass('hidden')) {
          figure.removeClass('hidden').addClass('show');
        }
        figure.find('img').attr('src', imgUrl).addClass('img-thumbnail');
      }
    });

    j('.remove-img').on('click', function(ev){
      ev.preventDefault();
      var figure = j('.img-thumbs');
      j('#fieldID').val('');
      figure.find('img').attr('src', '').parent().removeClass('show').addClass('hidden');
    });

    j(document).on('click', ".pagination-digg li a", function(e) {
        e.preventDefault();
        var href = j(this).attr("href");
        j('.content').load(href);
    });

    j(document).on('click', ".link-ajax", function(e) {
        e.preventDefault();
        var href = j(this).attr("href");
        j('.content').load(href);
    });

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

    // Activamos el switch
    j("[name='status']").bootstrapSwitch({
      size:       'small',
      onColor:    'success',
      radioAllOff:  true
    });

    j('body').on('click', '.ico-status', function(ev){
      ev.preventDefault();
      spinner.spin(target);

      var status = j(this).data('status');
      status = (status == '1') ? 0 : 1;
      var id = j(this).data('id');

      j.post(_root_ + 'admin/users/action', {
          id: id,
          status: status
        }, function(data) {
          if (data) {
            j('.content').load(_root_ + 'admin/users/displayAjax');
          }
      });
      spinner.stop();
    });

    j('body').on('click', '.ico-del', function(ev) {
      ev.preventDefault();
      var id = j(this).data('id');

      jConfirm('¿Desea eliminar este registro?', '¡Eliminación!', function(r) {
        if (r) {
          j.post(_root_ + 'admin/users/delete', {
            id: id
          }, function(data) {
            if (data) {
              j('.content').load(_root_ + 'admin/users/displayAjax');
            }
          });
        }
      });
    });

    // Filtrado de usuarios por roles
    j('body').on('change', '.filter-role', function(ev){
      ev.preventDefault();
      role = j(this).val();

      j('.content').load(_root_ + 'admin/users/displayAjax/' + role);
    });
  });
})(jQuery);

//
// Handles message from ResponsiveFilemanager
//
function OnMessage(e){
  var event = e.originalEvent;
  // Make sure the sender of the event is trusted
  if(event.data.sender === 'responsivefilemanager'){
    if(event.data.field_id){
      var fieldID = event.data.field_id;
      var url = event.data.url;
      j('#'+fieldID).val(url).trigger('change');
      j.fancybox.close();

      // Añadimos la imagen seleccionada al figure
      j('#img-thumbs img').attr('src', url).trigger('change');
      // Delete handler of the message from ResponsiveFilemanager
      j(window).off('message', OnMessage);
    }
  }
}
// Handler for a message from ResponsiveFilemanager
j('.iframe-btn').on('click',function(){
  j(window).on('message', OnMessage);
});