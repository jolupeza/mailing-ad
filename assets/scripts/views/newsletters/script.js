var j = jQuery.noConflict();

(function($){
	j(document).on("ready", function(){
    tinymce.init({
      selector: "textarea.edit-wysiwg",
      theme: "modern",
      height: 600,
      document_base_url: _root_,
      remove_script_host: false,
      relative_urls: false,
      plugins: [
        "advlist autolink link image code lists charmap print preview hr anchor pagebreak",
         "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
         "table contextmenu directionality emoticons paste textcolor responsivefilemanager"
      ],
      toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
      toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
      image_advtab: true,
      external_filemanager_path: _root_ + "filemanager/",
      filemanager_title:"Responsive Filemanager" ,
      external_plugins: { "filemanager" : _root_ + "filemanager/plugin.min.js"}
    });

		// Activamos el datetimepicker para agregar un nuevo suscriptor
    j('#datetimepicker-add').datetimepicker({
	    defaultDate:  moment()
	  });

    // Activamos el datetimepicker al momento de editar suscriptor
    j('#datetimepicker-edit').datetimepicker({
      defaultDate:  moment(j("#datetimepicker-edit").data('datetime'))
    });

    // Cargamos los datos con los suscriptores.
    j('.content').load(_root_ + 'admin/newsletters/displayAjax');

    j(document).on('click', ".pagination-digg li a", function(e) {
    		e.preventDefault();
        spinner.spin(target);
    		var href = j(this).attr("href");
    		j('.content').load(href);
        spinner.stop();
  	});

  	j(document).on('click', ".link-ajax", function(e) {
    		e.preventDefault();
    		var href = j(this).attr("href");
    		j('.content').load(href);
  	});

  	j(document).on('click', '#goto-btn', function(ev){
    		ev.preventDefault();
        spinner.spin(target);

    		var $page = parseInt(j('.goto').val());
    		var $no_of_pages = parseInt(j(this).data('maxpage'));

    		if ($page > 0 && $page <= $no_of_pages) {
      		var href = j(this).data('href');
      		var $offset = ($page - 1) * parseInt(j(this).data('limit'));
      		j('.content').load(href + $offset);
          spinner.stop();
    		} else {
          spinner.stop();
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

    j('body').on('click', 'input[id^="temp-"]', function(){
      var id = j(this).val();
      var $news = j(this).data('news');
      spinner.spin(target);

      if ($news > '0') {
        j.post(_root_ + 'admin/newsletters/getNewsletter', {
          id: id
        }, function(data) {
          if (data) {
            j.get(data[0].guid, function(content) {
              tinyMCE.activeEditor.setContent(content);
              spinner.stop();
            });
          }
        }, 'json');

      } else {
        j.post(_root_ + 'admin/newsletters/selectTemplate', {
          id: id
        }, function(data) {
          if (data) {
            j.get(data[0].guid, function(content) {
              tinyMCE.activeEditor.setContent(content);
              spinner.stop();
            });
          }
        }, 'json');
      }
    });

    j('body').on('click', '.delete', function(ev){
      ev.preventDefault();
      var $this = j(this);

      spinner.spin(target);

      jConfirm('¿Desea eliminar este registro?', '¡Eliminación!', function(r) {
        if (r) {
          var id = $this.data('id');

          j.post(_root_ + 'admin/newsletters/delete', {
            id: id
          }, function(data) {
            spin.stop();
            if (data) {
              j('.content').load(_root_ + 'admin/newsletters/displayAjax');
            }
          });
        } else {
          spin.stop();
        }
      });
    });

    j('body').on('click', '.ico-status', function(ev){
      ev.preventDefault();
      var status = j(this).data('status');
      status = (status == '1') ? 0 : 1;
      var id = j(this).data('id');

      spinner.spin(target);

      j.post(_root_ + 'admin/newsletters/action', {
          id: id,
          status: status
        }, function(data) {
          if (data) {
            j('.content').load(_root_ + 'admin/newsletters/displayAjax');
            spinner.stop();
          }
      });
    });

    j('#send-test').on('click', function(ev) {
      ev.preventDefault();
      var email = j('#test-email').val();
      var name = j('#test-name').val();
      var id = j(this).data('id');

      // Realizamos validaciones
      if (!validEmail(email)) return;

      if (name.length == 0) return;

      spinner.spin(target);

      j.post(_root_ + 'admin/newsletters/sendEmail', {
        email: email,
        name: name,
        id: id
      }, function(data) {
        if (data) {
          j('#test-email').val('');
          j('#test-name').val('');
          jAlert('Se envio correctamente el mensaje', 'Aviso');
        }

        spinner.stop();
      });
    });

    loadContent();
	});
})(jQuery);

function loadContent() {
  // Obtenemos la url del documento y determinamos si estamos editando para luego cargar el contenido del newsletter
  $url = document.URL;

  if ($url.substr(0, $url.length - 1) == _root_ + 'admin/newsletters/edit/') {
    $id = $url.slice(-1);

    j.post(_root_ + 'admin/newsletters/getNewsletter', {
      id: $id
    }, function(data) {
      if (data) {
        j.get(data[0].guid, function(content) {
          tinyMCE.activeEditor.setContent(content);
        });
      }
    }, 'json');
  }
}