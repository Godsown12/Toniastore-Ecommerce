$(document).ready(function(){
			$('.login-here').click(function(e){
				e.preventDefault();

				$('.login').toggleClass('active');

			});

			$('.register-here').click(function(){

				$('.login').toggleClass('active');
			});

			$('.close').click(function(){
				$('.errors-ul').fadeOut(500);
			});

			var login = document.getElementById('login');

			/*window.onclick=function(event){
		    	if (event.target==login){

		    		$('.login').toggleClass('active');
		    		

    			}
    		}*/
});