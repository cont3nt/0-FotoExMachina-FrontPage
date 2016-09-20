jQuery(document).ready(function($) {
    "user strict";
});

function checkAuth(){
	var hash = window.location.hash;
	var public_tickets = hash.indexOf("/public-tickets") > -1;
	var public_ticket = hash.indexOf("/public-tickets/") > -1;
	if(token == 'invalid' && hash != '#/home' && !public_tickets && !public_ticket){
	    window.location = '#/login';
	}
}

function loading(state){
	if(state == 'show'){
		$('.angular-load').fadeIn(50);
	}else{
		$('.angular-load').fadeOut(250);
	}
}

function debugapp(data){
	if(debug){
		console.log(data);
	}
}