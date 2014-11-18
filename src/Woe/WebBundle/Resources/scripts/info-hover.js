'use strict';

(function(){
	
		$('.event-info').mouseEnter(function(){
			$(this).fadeOut('fast');
		});
		
		$('.event-info').mouseLeave(function(){
			$(this).fadeIn('fast');
		});
	
	});
})();
