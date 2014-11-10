var j = jQuery.noConflict();

(function($){
	j(document).on("ready", function(){
		// Activamos el datetimepicker para agregar un nuevo suscriptor
    j('#datetimepicker-add').datetimepicker({
      pickTime: false
    });

    // Activamos el datetimepicker al momento de editar suscriptor
    j('#datetimepicker-edit').datetimepicker({
      defaultDate:  moment(j("#datetimepicker-edit").data('datetime'))
    });

    // Cargamos los datos con los suscriptores.
    j('.content').load(_root_ + 'admin/subscribers/displayAjax');

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
  		var href = j(this).data('href');
  		var search = j(this).parent().prev().val();
  		j('.content').load(href + search);
  	});

  	// Activamos el switch
  	j("[name='status']").bootstrapSwitch({
  		size: 			'small',
  		onColor: 		'success',
  		radioAllOff: 	true
  	});

    // Funcionalidad para el botón de seleccionar lista de correo
    j('body').on('change', 'select[name="lists"]' , function(){
      var href = j(this).data("href");
      var search = $(this).data("search");
      j('.content').load(href + j(this).val() + '/' + search);
    });

    j('body').on('click', '.ico-action', function(ev) {
      ev.preventDefault();

      var status = j(this).data('status');
      var id = j(this).data('id');
      var ret = j(this).data('return');

      spinner.spin(target);

      j.post(_root_ + 'admin/subscribers/action', {
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

      j.post(_root_ + 'admin/subscribers/action', {
          id: id,
          status: status
        }, function(data) {
          spinner.stop();
          if (data) {
            j('.content').load(_root_ + 'admin/subscribers/displayAjax');
          }
      });
    });

    j('body').on('click', '.ico-del', function(ev) {
      ev.preventDefault();
      var id = j(this).data('id');

      spinner.spin(target);

      jConfirm('¿Desea eliminar este registro?', '¡Eliminación!', function(r) {
        if (r) {
          j.post(_root_ + 'admin/subscribers/delete', {
            id: id
          }, function(data) {
            spinner.stop();
            if (data) {
              j('.content').load(_root_ + 'admin/subscribers/displayAjax');
              jAlert('¡Se eliminó correctamente la suscripción!', 'Aviso');
            } else {
              jAlert('¡No se pudo eliminar la suscripción. Por favor vuelva a intentarlo!', 'Aviso');
            }
          });
        } else {
          spinner.stop();
        }
      });
    });
	});
})(jQuery);