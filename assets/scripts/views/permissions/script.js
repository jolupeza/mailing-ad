var j = jQuery.noConflict();

(function($){
	j(document).on("ready", function(){
    // Activamos el switch
    j("[name='status']").bootstrapSwitch({
      size:       'small',
      onColor:    'success',
      radioAllOff:  true
    });

    j(document).on('click', '#goto-btn', function(ev){
    		ev.preventDefault();
    		var $page = parseInt(j('.goto').val());
    		var $no_of_pages = parseInt(j(this).data('maxpage'));
    		if ($page > 0 && $page <= $no_of_pages) {
      		var href = j(this).data('href');
      		var $offset = ($page - 1) * parseInt(j(this).data('limit'));
          $offset = (parseInt($offset) == 0) ? '' : $offset;
          window.location = href + $offset;
    		} else {
          jAlert('Ingrese una p&aacute;gina entre 1 y ' + $no_of_pages, 'Advertencia');
          j('.goto').val("");
          return false;
        }
  	});

  	j(document).on('click', '#search-btn', function(ev){
    		ev.preventDefault();
    		var href = j(this).data('href');
    		var search = j(this).parent().prev().val();
        window.location = href + search;
  	});

    j('body').on('click', '.ico-status', function(ev){
      ev.preventDefault();
      spinner.spin(target);

      var $this = j(this);

      var status = j(this).data('status');
      status = (status == '1') ? 0 : 1;
      var id = j(this).data('id');

      j.post(_root_ + 'admin/permissions/action', {
          id: id,
          status: status
        }, function(data) {
          if (data) {
            $this.html('');
            $this.data('status', status);

            content = '';
            if (status) {
              content = '<i class="fa fa-check"></i>';
            } else {
              content = '<i class="fa fa-times"></i>';
            }

            $this.html(content);
            //j('.content').load(_root_ + 'admin/users/displayAjax');
          }
      });
      spinner.stop();
    });

    j('body').on('click', '.ico-del', function(ev) {
      ev.preventDefault();
      var id = j(this).data('id');

      jConfirm('¿Desea eliminar este registro?', '¡Eliminación!', function(r) {
        if (r) {
          j.post(_root_ + 'admin/permissions/delete', {
            id: id
          }, function(data) {
            if (data) {
              window.location = _root_ + 'admin/permissions'
            }
          });
        }
      });
    });

    j('body').on('click', '.del-perm', function(ev) {
      ev.preventDefault();
      var id = j(this).data('id');

      jConfirm('¿Desea eliminar este registro?', '¡Eliminación!', function(r) {
        if (r) {
          j.post(_root_ + 'admin/permissions/delPerm', {
            id: id
          }, function(data) {
            if (data) {
              window.location = _root_ + 'admin/permissions/perms'
            } else {
              jAlert('No se pudo eliminar el registro seleccionado. Por favor vuelva a intentarlo.', 'Aviso');
            }
          });
        }
      });
    });

    j('#add-perm').on('click', function(ev){
      ev.preventDefault();
      spinner.spin(target);

      // Verificamos que hayamos ingresado tanto el title como el name o key
      var title = j('#title').val();
      var name = j('#name').val();

      if (title.length == 0) {
        j('#title').focus();
        spinner.stop();
        return;
      }

      if (name.length == 0) {
        j('#name').focus();
        spinner.stop();
        return;
      }

      // Verificamos la clave ingresada ya no existe en la base de datos
      j.post(_root_ + 'admin/permissions/verifyKey', {
        name: name
      }, function(data) {
        if (data) {
          j('#name').val('Clave ya existe. Por favor ingresa otra.');
        } else {
          j.post(_root_ + 'admin/permissions/addPerm', {
            title: title,
            name: name
          }, function(data) {
            j('#modalAddPerm').modal('hide');

            if (data) {
              window.location = _root_ + 'admin/permissions/perms';
              //jAlert('Se agregó correctamente el permiso.', 'Aviso');
            } else {
              jAlert('No se pudo agregar el nuevo permiso. Por favor verifique y vuelva a intentar.', 'Aviso');
            }
          });
        }
      });

      spinner.stop();
    });

    j('#modalAddPerm').on('hidden.bs.modal', function (e) {
      j('#title').val('');
      j('#name').val('');
    });

    j('#modalAddPerm').on('shown.bs.modal', function (e) {
      j('#title').focus();
    });

    j('#edit-perm').on('click', function(ev){
      ev.preventDefault();

      // Verificamos que hayamos ingresado tanto el title como el name o key
      var title = j('#titleEdit').val();
      var name = j('#nameEdit').val();
      var id = j('#perm_id').val();

      if (title.length == 0) {
        j('#titleEdit').focus();
        return;
      }

      if (name.length == 0) {
        spinner.stop();
        return;
      }

      //Traemos key actual antes de actualización
      j.post(_root_ + 'admin/permissions/getPerm', {
        id: id
      }, function(data) {
        if (data) {
          if (data.name != name) {
            j.post(_root_ + 'admin/permissions/verifyKey', {
              name: name
            }, function(data2) {
              console.log(data2);
              if (data2) {
                j('#nameEdit').val(data.name).focus();
              } else {
                j.post(_root_ + 'admin/permissions/editPerm', {
                  id: id,
                  title: title,
                  name: name
                }, function(data) {
                  j('#modalEditPerm').modal('hide');
                  if (data) {
                    tr = j('#perm-' + id);
                    tr.find('td').eq(1).html(data.title);
                    tr.find('td').eq(2).html(data.name);
                  } else {
                    jAlert('No se pudo modificar los datos del permiso. Por favor verifique y vuelva a intentar.', 'Aviso');
                  }
                }, 'json');
              }
            });
          } else {
            j.post(_root_ + 'admin/permissions/editPerm', {
              id: id,
              title: title,
              name: name
            }, function(data) {
              j('#modalEditPerm').modal('hide');
              if (data) {
                tr = j('#perm-' + id);
                tr.find('td').eq(1).html(data.title);
                tr.find('td').eq(2).html(data.name);
              } else {
                jAlert('No se pudo modificar los datos del permiso. Por favor verifique y vuelva a intentar.', 'Aviso');
              }
            }, 'json');
          }
        }
      }, 'json');
    });

    j('#modalEditPerm').on('hidden.bs.modal', function (e) {
      j('#titleEdit').val('');
      j('#nameEdit').val('');
    });

    j('#modalEditPerm').on('shown.bs.modal', function (e) {
      var $this = e.relatedTarget;
      id = $this.attributes[2];
      id = id.value;

      j.post(_root_ + 'admin/permissions/getPerm', {
        id: id
      }, function(data) {
        if (data) {
          j('#titleEdit').val(data.title);
          j('#nameEdit').val(data.name);
          j('#perm_id').val(id);
        }
      }, 'json');

      j('#titleEdit').focus();
    });
	});
})(jQuery);