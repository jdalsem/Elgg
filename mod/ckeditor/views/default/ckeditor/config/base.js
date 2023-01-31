define(['elgg'], function(elgg) {
	
	var topbar_height = $('.elgg-page-topbar').height();
	
	return {
		language: elgg.config.current_language,
		ui: {
			viewportOffset: {
				top: topbar_height
			}
		}
	};
});
