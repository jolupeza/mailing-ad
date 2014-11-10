var j = jQuery.noConflict();

(function($){
	// Al seleccionar Editar tanto en la pantalla de Edición como de Agregar post nos muestra los campos para editar
    j('.open_edit').on('click', function(ev){
      ev.preventDefault();
      j(this).hide();
      j(this).next().fadeIn();
    });

    // Ocultamos los campos para editar
    j('.cancel').on('click', function(ev){
      ev.preventDefault();
      j(this).parent().fadeOut().prev().show();
    });

    // Si actualizamos el estatus del post actualizamos el texto y cerramos los campos de edición.
    j('#edit-status').on('click', function(ev){
      ev.preventDefault();
      var status_name = j('select[name="post_status"] option:selected').html();
      j(this).parent().fadeOut().prev().show().prev().text(status_name);
    });

    // Modificamos la fecha de publicación y actualizamos el texto con la nueva fecha
    j('#edit-published').on('click', function(ev){
      ev.preventDefault();
      var date_publish = j('div[id^="datetimepicker"]').data("DateTimePicker").getDate();
      var fecha = date('d F Y h:i a', new Date(date_publish._d));
      j(this).parent().fadeOut().prev().show().prev().text(fecha);
    })

    // Al posicionarnos encima de un post nos mostrará las opciones que podemos realizar con el post (Editar, Papelera, Ver)
    j(document).on({
      mouseenter: function() {
        j(this).find('.opt-post').show();
      },
      mouseleave: function() {
        j(this).find('.opt-post').hide();
      }
    }, '.view-option-post');

    tinymce.init({
      selector: "textarea.edit-wysiwg",
      plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
      ],
      toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    });

    // Activamos el datetimepicker al momento de editar post
    j('#datetimepicker1').datetimepicker({
      defaultDate:  moment(j("#datetimepicker1").data('datetime'))
    });

    // Activams el datetimepicker al momento de agregar post
    j('#datetimepicker2').datetimepicker({
      defaultDate:  moment()
    });

    // Cargamos el contenido del grid
    j('.content').load(_root_ + 'admin/pages/displayPageAjax');

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

    j('.remove-img-featured').on('click', function(ev){
      ev.preventDefault();
      var $this = j(this);
      var id = j(this).data('id');
      j.post(_root_ + 'admin/pages/deleteFeaturedImages', {
        'id' : id
      }, function(data){
        if (data) {
          $this.parent().addClass('hidden').prev().addClass('hidden').parent().append('<input type="file" name="image" id="image" />');
        }
      });
    });
})(jQuery);