$(document).ready(function(){
	
	$('.categorise-type').click(function(e){
		e.preventDefault();

		window.location=$(this).find("a").attr("href"); 
		return false;
	});

	$('.close').click(function(e){
		e.preventDefault();
		$('.errors-ul').fadeOut();
	})
});

//Script for slide show..
  
$(document).ready(function() {
	$(".rslides").responsiveSlides();
});
 

$(document).ready(function(){
	$('#cart_update').click(function(e){
		window.location=$(this).find("a").attr("href"); 
		return false;
	})	;
});



