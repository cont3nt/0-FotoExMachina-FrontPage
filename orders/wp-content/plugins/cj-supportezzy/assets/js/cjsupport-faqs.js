jQuery(document).ready(function($){
	$('#cjsupport-faqs #products a.product-title').on('click', function(){
		var el = $(this).attr('data-id');
		$('#cjsupport-faqs #products').animate({"left":"-100%"}, 500, function(){
			$(this).css({'position' : 'absolute'});
		});
		$('#'+el+'.faqs-panel').animate({"left" : 0}, 500, function(){
			$(this).css({'position' : 'relative'});
		});
		return false;
	});
	$('a.faq-back').on('click', function(){
		$('#cjsupport-faqs #products').animate({"left":"0"}, 500, function(){
			$(this).css({'position' : 'relative'});
		});
		$('.faqs-panel').animate({"left":'100%'}, 500, function(){
			$(this).css({'position' : 'absolute'});
		});
		return false;
	});

	$(".cjsupport-toggle-id").on('click', function(){
		var id = $(this).attr('data-id');
		$('.cjsupport-toggle').hide(0);
		$('#'+id).slideToggle(500);
	});
});