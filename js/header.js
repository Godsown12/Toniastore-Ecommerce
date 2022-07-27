$(document).ready(function(){
	$('.toggle-menu').click(function(e){
		e.preventDefault();

		$('nav').toggleClass('active');
	});

});