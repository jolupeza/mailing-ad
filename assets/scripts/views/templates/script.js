var j = jQuery.noConflict();

(function($){
	j(document).on("ready", function(){
    // Cargamos los datos con los suscriptores.
    j('.content').load(_root_ + 'admin/templates/displayAjax');

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

    j('body').on('click', '.ico-status', function(ev){
      ev.preventDefault();
      spinner.spin(target);

      var status = j(this).data('status');
      status = (status == '1') ? 0 : 1;
      var id = j(this).data('id');

      j.post(_root_ + 'admin/templates/action', {
          id: id,
          status: status
        }, function(data) {
          spinner.stop();
          if (data) {
            j('.content').load(_root_ + 'admin/templates/displayAjax');
          }
      });
    });

    // Quitar imagen destacada al momento de agregar plantilla
    j('.remove-img').on('click', function(ev){
      ev.preventDefault();
      var figure = j('.img-thumbs');
      j('#fieldID').val('');
      figure.find('img').attr('src', '').parent().removeClass('show').addClass('hidden');
    });

    j('.remove-file').on('click', function(ev){
      ev.preventDefault();

      j('#img-file').removeClass('show').addClass('hidden');
      j('#remove-file').removeClass('show').addClass('hidden');
      //j('.wrapper-file-input').removeClass('hidden').addClass('show');

      j('#remove-file').parent().append('<div class="wrapper-file-input"><span class="fake-file-input"></span><input type="file" id="source" name="source" class="file-input"><a href="#" id="cancel-file">Cancelar</a></div>');
    });

    j('body').on('click', '#cancel-file', function(ev){
      ev.preventDefault();
      j('#img-file').removeClass('hidden').addClass('show');
      j('#remove-file').removeClass('hidden').addClass('show');

      j('.wrapper-file-input').remove();
    });

    j('body').on('click', '.delete', function(ev){
      ev.preventDefault();

      spinner.spin(target);
      var $this = j(this);

      jConfirm('¿Desea eliminar este registro?', '¡Eliminación!', function(r) {
        if (r) {
          var id = $this.data('id');

          j.post(_root_ + 'admin/templates/delete', {
            id: id
          }, function(data) {
            spinner.stop();
            if (data) {
              j('.content').load(_root_ + 'admin/templates/displayAjax');
            } else {
              jAlert('No se pudo eliminar el registro. Verifique que cuenta con los permisos adecuados.', 'Aviso');
            }
          });
        } else {
          spinner.stop();
        }
      });
    });

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
	});
})(jQuery);