$(document).ready(function () {

	var tablas = $('.acordeon > table');
	//tablas.not('.exp').hide();
	
	// para expandir/contraer todas las tablas
	$('.expContraer').click(function() {				
		tablas.first().is(':visible') ? tablas.hide() : tablas.show();
	});		
	
	// para expandir/contraer al hacer click sobre el t�tulo de una categor�a
	$('.acordeon > a').click(function() {
		$(this).next('table').toggle();
		$(this).toggleClass('expanded');
	});
	
	// para expandir al hacer click en un enlace del �ndice
	$('.indiceContenido a').click(function() {
		var enlace = $(this).attr('href').substring(1);
		$('a[name="'+enlace+'"]').next('table').show();
	});
});