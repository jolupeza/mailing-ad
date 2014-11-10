var j = jQuery.noConflict();

(function($){
	j(document).on("ready", function(){
    // Cargamos los datos con los suscriptores.
    j('.content').load(_root_ + 'admin/lists/displayAjax');

    // Al posicionarnos encima de un item nos mostrará las opciones que podemos realizar con él
    j(document).on({
      mouseenter: function() {
        j(this).find('.opt-post').show();
      },
      mouseleave: function() {
        j(this).find('.opt-post').hide();
      }
    }, '.view-option-post');

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
        spinner.spin(target);
    		var href = j(this).data('href');
    		var search = j(this).parent().prev().val();
    		j('.content').load(href + search);
        spinner.stop();
  	});

  	// Activamos el switch
  	j("[name='status']").bootstrapSwitch({
  		size: 			'small',
  		onColor: 		'success',
  		radioAllOff: 	true
  	});

    j('body').on('click', '.ico-action', function(ev) {
      ev.preventDefault();

      var status = j(this).data('status');
      var id = j(this).data('id');
      var ret = j(this).data('return');

      spinner.spin(target);

      j.post(_root_ + 'admin/lists/action', {
        status: status,
        id : id
      }, function(data) {
        spinner.stop();
        if (data) {
          j('.content').load(_root_ + ret);
        }
      });
    });

    j('body').on('click', '.ico-status', function(ev){
      ev.preventDefault();
      var status = j(this).data('status');
      status = (status == '1') ? 0 : 1;
      var id = j(this).data('id');

      spinner.spin(target);

      j.post(_root_ + 'admin/lists/action', {
          id: id,
          status: status
        }, function(data) {
          spinner.stop();
          if (data) {
            j('.content').load(_root_ + 'admin/lists/displayAjax');
          }
      });
    });

    j('body').on('click', '.ico-del', function(ev) {
      ev.preventDefault();
      var id = j(this).data('id');

      spinner.spin(target);

      jConfirm('¿Desea eliminar este registro?', '¡Eliminación!', function(r) {
        if (r) {
          j.post(_root_ + 'admin/lists/delete', {
            id: id
          }, function(data) {
            spinner.stop();
            if (data) {
              j('.content').load(_root_ + 'admin/lists/displayAjax');
              jAlert('¡Se eliminó correctamente la lista!', 'Aviso');
            } else {
              jAlert('¡No se pudo eliminar la lista. Verifique que no contenga suscriptores.!', 'Aviso');
            }
          });
        } else {
          spinner.stop();
        }
      });
    });
	});
})(jQuery);