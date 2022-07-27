$(document).ready(function(){
    $('.add-link').click(function(e){
		e.preventDefault();

		window.location=$(this).find("a").attr("href"); 
		return false;
	});
});