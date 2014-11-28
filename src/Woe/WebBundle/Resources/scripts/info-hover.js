'use strict';

(function(){
	
		$('.event-info .event-image').mouseEnter(function(){
			$(this).fadeOut('fast');
		});
		
		$('.event-info .event-image').mouseLeave(function(){
			$(this).fadeIn('fast');
		});
})();
