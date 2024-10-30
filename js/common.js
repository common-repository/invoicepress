jQuery(document).ready(function($){

	$('body').on('click', '.format-selection-wrapper input[name="format"]', function(){
		
		var v = $(this).val();		
		
		$('.format-selection-wrapper').find('.selected').removeClass('selected');
		
		$(this).parent().addClass('selected');
		
	});
	
	

});