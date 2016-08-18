
$(document).ready(function(){
	$('.toggle').on('click', function() {
	  $('.login-form').stop().addClass('active');
	});

	$('.close').on('click', function() {
	  $('.login-form').stop().removeClass('active');
	});
});