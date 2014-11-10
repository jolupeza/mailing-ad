var j = jQuery.noConflict();

(function($){
  j('body').on('click', '#select-menu', function(ev){
    ev.preventDefault();
    var menu = j('#list-menus').val();
    var href = j(this).data('href');
    window.location = _root_ + href + menu;
  });

  j('body').on('click', '.sh-settings', function() {
    var liParent = j(this).parent().parent().parent().parent();

    if (liParent.hasClass('menu-item-edit-inactive')) {
      liParent.removeClass('menu-item-edit-inactive').addClass('menu-item-edit-active');
      j(this).removeClass('glyphicon-arrow-down').addClass('glyphicon-arrow-up');
    } else {
      liParent.removeClass('menu-item-edit-active').addClass('menu-item-edit-inactive');
      j(this).removeClass('glyphicon-arrow-down').addClass('glyphicon-arrow-down');
    }
  });

  j('body').on('click', '.del-mnu-item', function(ev){
    ev.preventDefault();
    var id = j(this).data('id');
    var idMenu = j(this).data('idmenu');
    idMenu = (idMenu == "undefined") ? 0 : j(this).data('idmenu');

    j.post(_root_ + 'admin/menus/deleteItemMenu', {id: id, idMenu: idMenu}, function(data) {
      if (data) {
        j('#menu-item-' + id).remove();

        var li = j('.ui-sorteable li');
        li.each(function(index, el) {
          var position = index + 1;
          j(el).find('.menu-item-data-position').val(position);
        });
      }
    });
  });

  j('body').on('click', '.cancel-mnu-item', function(ev){
    ev.preventDefault();
    var id = j(this).data('id');

    if (j('#menu-item-' + id).hasClass('menu-item-edit-inactive')) {
      j('#menu-item-' + id).removeClass('menu-item-edit-inactive').addClass('menu-item-edit-active');
      j('#sh-settings-' + id).removeClass('glyphicon-arrow-down').addClass('glyphicon-arrow-up');
    } else {
      j('#menu-item-' + id).removeClass('menu-item-edit-active').addClass('menu-item-edit-inactive');
      j('#sh-settings-' + id).removeClass('glyphicon-arrow-down').addClass('glyphicon-arrow-down');
    }
  });

  j(".ui-sorteable" ).sortable({
    opacity: 0.7,
    update: function() {
      //var ordenElementos = j(this).sortable("toArray").toString();
      var li = j(this).children('li');
      li.each(function(index, el) {
        var position = index + 1;
        j(el).find('.menu-item-data-position').val(position);
      });
    }
  });

  j(".ui-sorteable" ).disableSelection();

  /*j(".menu-item").draggable({
    containment: 'parent'
  });*/

  // Agregamos un link personalizado a la tabla posts y luego lo agregamos como elemento del menú actual
  j('body').on('click', '#add-menu-item-link', function(ev) {
    ev.preventDefault();
    var type = j('input[name="type"]').val();
    var url = j('#link-url').val();
    if (url.length == 0) {
      j('#link-url').focus();
      return;
    }
    var pro = (url.substr(0, 7) == 'http://') ? url.substr(0, 7) : url.substr(0, 8);

    if (pro == 'http://' || pro == 'https://') {
      var sinpro = url.substr(pro.length);
      if (sinpro.length == 0) {
        j('#link-url').focus();
        return ;
      }

      var name = j('#link-name').val();
      if (name.length == 0) {
        j('#link-name').focus();
        return;
      }

      j('#link_url').val('http://');
      j('#link_name').val('');

      j.post(_root_ + 'admin/menus/addLinkCustom', {
        url: url,
        name: name
      }, function(data) {
        // Traemos el id la última y luego consultamos los datos y traemos via ajax
        j.post(_root_ + 'admin/menus/getPostMeta', {id: data.id}, function(data2) {
          // Ahora debemos insertar al área de la estructura del menú
          html = container_menu_item(type, data.id, name, data2, url);
          j('.structure-menu .panel-body ul.ui-sorteable').append(html);
        }, 'json');
      }, 'json');
    } else {
      j('#link-url').focus();
      return;
    }
  })

  j('body').on('click', ".add-menu-item", function(ev){
    ev.preventDefault();

    j('.chk-opt').each(function(){
      if (j(this).is(":checked")) {
        var html = '';
        var type = j(this).data('type');
        var id = j(this).val();
        var title = j(this).data('title');

        j(this).removeProp('checked');

        j.post(_root_ + 'admin/menus/addMenuItem', {
          id: id,
          object: j(this).data('object')
        }, function(data) {
          j.post(_root_ + 'admin/menus/getPostMeta', {id: data.id}, function(data2) {
            html = container_menu_item(type, data.id, title, data2);
            j('.structure-menu .panel-body ul.ui-sorteable').append(html);
          }, 'json');
        }, 'json');
      }
    });
  });

  j('body').on('click', '#delMenu', function(ev){
    ev.preventDefault();
    var idMenu = j(this).data('idmenu');

    j.post(_root_ + 'admin/menus/delMenu', {
      id: idMenu
    }, function(data) {
      if (data) {
        window.location = _root_ + 'admin/menus';
      }
    });
  });
})(jQuery);

function container_menu_item (type, id, title, data, url) {
  var html = '';

  url = (url) ? url : '';

  var typeItem = '';
  switch (type) {
    case 'Página':
    case 'Page':
      typeItem = 'page';
      break;
    case 'Categoría':
    case 'Category':
      typeItem = 'category';
      break;
    case 'Personalizado':
    case 'Custom':
      typeItem = 'custom';
      break;
  }

  // Contamos los li disponibles para colocar el menu_order
  var li = j('.ui-sorteable li').length;
  li++;

  html += '<li id="menu-item-' + id + '" class="menu-item menu-item-depth-0 menu-item-' + typeItem + ' menu-item-edit-inactive">';
  html += '<dl class="menu-item-bar">';
  html += '<dt class="menu-item-handle">';
  html += '<span class="item-title">';
  html += '<span class="menu-item-title">' + title + '</span>';
  html += '<span class="is-submenu">subelemento</span>';
  html += '</span>';
  html += '<span class="item-controls pull-right">';
  html += '<span class="item-type">' + type + '</span>';
  // Aqui va la flecha y debe ir cambiando  lo desplegamos o comprimimos
  html += '<span class="sh-settings glyphicon glyphicon-arrow-down" id="sh-settings-' + id  + '"></span>';
  html += '</span>';
  html += '</dt>';
  html += '</dl>';
  html += '<div class="menu-item-settings" id="menu-item-settings-' + id + '">';

  if (typeItem == 'custom') {
    html += '<div class="row">';
    html += '<div class="col-md-12">';
    html += '<div class="form-group">';
    html += '<label for="edit-menu-item-url-' + id + '"><em>URL</em></label>';
    html += '<input type="text" class="form-control edit-menu-item-url" name="menu-item-url[' + id + ']" id="edit-menu-item-url-' + id + '" value="' + url + '" />';
    html += '</div><!-- end form-group -->';
    html += '</div><!-- end col-md-6 -->';
    html += '</div><!-- end row -->';
  }
  html += '<div class="row">';
  html += '<div class="col-md-6">';
  html += '<div class="form-group">';
  html += '<label for="edit-menu-item-' + id + '"><em>Etiqueta de navegación</em></label>';
  html += '<input type="text" class="form-control edit-menu-item-title" name="menu-item-title[' + id + ']" id="edit-menu-item-' + id + '" value="' + title + '" />';
  html += '<div class="checkbox">';
  html += '<label>';
  html += '<input type="checkbox" id="edit-menu-item-target-' + id + '" name="menu-item-target[' + id + ']"> Abrir enlace en una nueva ventana / pestaña';
  html += '</label>';
  html += '</div><!-- end checkbox -->';
  html += '</div><!-- end form-group -->';
  html += '</div><!-- end col-md-6 -->';
  html += '<div class="col-md-6">';
  html += '<div class="form-group">';
  html += '<label for="edit-menu-item-attr-title-' + id + '"><em>Atributos del título</em></label>';
  html += '<input type="text" class="form-control edit-menu-item-attr-title" name="menu-item-attr-title[' + id + ']" id="edit-menu-item-attr-title-' + id + '" />';
  html += '</div><!-- end form-group -->';
  html += '</div><!-- end col-md-6 -->';
  html += '</div><!-- end row -->';
  html += '<div class="row">';
  html += '<div class="col-md-6">';
  html += '<div class="form-group">';
  html += '<label for="edit-menu-item-classes-' + id + '"><em>Clases CSS (Opcional)</em></label>';
  html += '<input type="text" class="form-control edit-menu-item-classes" name="menu-item-classes[' + id + ']" id="edit-menu-item-classes-' + id + '" />';
  html += '</div><!-- end form-group -->';
  html += '</div><!-- end col-md-6 -->';
  html += '</div><!-- end row -->';
  html += '<div class="menu-item-footer">';
  html += '<p><a href="#" class="del-mnu-item text-danger" data-id="' + id + '">Eliminar</a> | <a href="#" data-id="' + id + '" class="cancel-mnu-item">Cancelar</a></p>';
  html += '</div>';

  html += '<input type="hidden" class="menu-item-data-db-id" name="menu-item-db-id[' + id + ']" value="' + id + '" />';
  html += '<input type="hidden" class="menu-item-data-object-id" name="menu-item-object-id[' + id + ']" value="' + data[2].meta_value + '" />';
  html += '<input type="hidden" class="menu-item-data-object" name="menu-item-object[' + id + ']" value="' + data[3].meta_value + '" />';
  html += '<input type="hidden" class="menu-item-data-parent-id" name="menu-item-parent-id[' + id + ']" value="' + data[1].meta_value + '" />';
  html += '<input type="hidden" class="menu-item-data-position" name="menu-item-position[' + id + ']" value="' + li + '" />';
  html += '<input type="hidden" class="menu-item-data-type" name="menu-item-type[' + id + ']" value="' + data[0].meta_value + '" />';

  html += '</div><!-- end menu-item-settings-->';
  html += '</li>';

  return html;
}