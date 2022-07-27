$(document).ready(function(){

			$('.register-here-l').click(function(e){
				e.preventDefault();

				$('.register_l').toggleClass('active_l');

			});

			$('.login-here-l').click(function(){

				$('.register_l').toggleClass('active_l');
			
			});

			$('.close').click(function(){
				$('.errors-ul').fadeOut(500);
			});

			var register_l = document.getElementById('register_l');
			var login = document.getElementById('login');

			window.onclick=function(event){
		    	if (event.target == register_l || event.target == login){

					$('.login').toggleClass('active');
					$('.register_l').toggleClass('active_l');

    			}
			};

			
});