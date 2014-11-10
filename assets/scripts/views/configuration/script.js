var j = jQuery.noConflict();

(function($){
  // Al selecionar un formato de fecha actualizamos el campo date_format_custom con este.
  j('body').on('click', 'input[name="date_format"]', function() {
    var fecha = date(j(this).val(), new Date());
    if (j(this).val() == "\\c\\u\\s\\t\\o\\m") return;
    j('input[name="date_format_custom"]').val(j(this).val()).next().text(' ' + fecha);
  });

  // Al seleccionar un formato de hora actualizamos el campo time_format_custom con este.
  j('input[name="time_format"]').on('click', function() {
    var fecha = date(j(this).val(), new Date());
    if (j(this).val() == "\\c\\u\\s\\t\\o\\m") return;
    j('input[name="time_format_custom"]').val(j(this).val()).next().text(' ' + fecha);
  });

  // Al obtener el focus el campo date_format_custom seleccionamos el option Personalizado
  j('input[name="date_format_custom"]').on('focus', function() {
    j(this).prev().find('input[name="date_format"]').prop('checked', 'TRUE');
  })

  // Al perder el focus el campo date_format_custom actualizamos el texto continuo con el formato indicado en el campo
  j('input[name="date_format_custom"]').on('focusout', function(){
    var fecha = date(j(this).val(), new Date());
    j(this).next().text(' ' + fecha);
  });

  // Al obtener el focus el campo time_format_custom seleccionamos el option Personalizado
  j('input[name="time_format_custom"]').on('focus', function() {
    j(this).prev().find('input[name="time_format"]').prop('checked', 'TRUE');
  })

  // Al perder el focus el campo time_format_custom actualizamos el texto continuo con el formato indicado en el campo
  j('input[name="time_format_custom"]').on('focusout', function(){
    var fecha = date(j(this).val(), new Date());
    j(this).next().text(' ' + fecha);
  });
})(jQuery);