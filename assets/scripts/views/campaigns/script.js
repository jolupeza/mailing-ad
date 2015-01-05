var j = jQuery.noConflict();

(function($){
	j(document).on("ready", function(){
    // Cargamos los datos con los suscriptores.
    j('.content').load(_root_ + 'admin/campaigns/displayAjax');

    // Activamos el datetimepicker para agregar un nuevo suscriptor
    j('#datetimepicker-add').datetimepicker({
      defaultDate:  moment()
    });

    // Activamos el datetimepicker al momento de editar suscriptor
    j('#datetimepicker-edit').datetimepicker({
      defaultDate:  moment(j("#datetimepicker-edit").data('datetime'))
    });

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

    j('body').on('click', '.ico-action', function(ev) {
      ev.preventDefault();

      var status = j(this).data('status');
      var id = j(this).data('id');
      var ret = j(this).data('return');

      spinner.spin(target);

      j.post(_root_ + 'admin/campaigns/action', {
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

      j.post(_root_ + 'admin/campaigns/action', {
          id: id,
          status: status
        }, function(data) {
          spinner.stop();
          if (data) {
            j('.content').load(_root_ + 'admin/campaigns/displayAjax');
          }
      });
    });

    j('body').on('click', '.ico-del', function(ev) {
      ev.preventDefault();
      var id = j(this).data('id');

      spinner.spin(target);

      jConfirm('¿Desea eliminar este registro?', '¡Eliminación!', function(r) {
        if (r) {
          j.post(_root_ + 'admin/campaigns/delete', {
            id: id
          }, function(data) {
            spinner.stop();
            if (data) {
              j('.content').load(_root_ + 'admin/campaigns/displayAjax');
              jAlert('¡Se eliminó correctamente la campaña!', 'Aviso');
            } else {
              jAlert('¡No se pudo eliminar la campaña. Por favor vuelva a intentarlo!', 'Aviso');
            }
          });
        } else {
          spinner.stop();
        }
      });
    });

    j('input[name="lists"]').on('click', function(){
      if (j(this).val() == '2') {
        if (j(this).data('id')) {
          j.post(_root_ + 'admin/campaigns/getLists', {
            id: j(this).data('id')
          }, function(data) {
            if (data) {
              j.each(data, function(index, val) {
                j('input[name="list[]"]').each(function(){
                  if (j(this).val() == val.list_id) {
                    j(this).prop('checked', true);
                  }
                });
              });
            }
          }, 'json');
        }

        j('.check-lists').css({
          'display' : 'block'
        });
      } else {
        j('input[name="list[]"]').each(function(){
          j(this).prop('checked', false);
        });

        j('.check-lists').css({
          'display' : 'none'
        });
      }
    });

    if (j("#display-lists").is(":checked")) {
      var id = j("#display-lists").data('id');

      j.post(_root_ + 'admin/campaigns/getLists', {
        id: id
      }, function(data) {
        if (data) {
          j.each(data, function(index, val) {
            j('input[name="list[]"]').each(function(){
              if (j(this).val() == val.list_id) {
                j(this).prop('checked', true);
              }
            });
          });
        }
      }, 'json');

      j('.check-lists').css({
        'display' : 'block'
      });
    }

    // Botón enviar campaña
    j('#send_camp').on('click', function(ev){
      ev.preventDefault();
      var id = j(this).data('id');
      spinner.spin(target);

      j.post(_root_ + 'admin/campaigns/send', {
        id: id
      }, function(data) {
        if (data) {
          spinner.stop();

          if (data.length > 0) {
            jAlert('No se pudo entregar el correo a algunos suscriptores.', 'Aviso');
            console.log(data);
          } else {
            jAlert('Se entregó la campaña satisfactoriamente.', 'Aviso');
          }
        } else {
          spinner.stop();
        }
      }, 'json');
    });
	});
})(jQuery);