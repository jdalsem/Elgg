define(['elgg'], function(elgg) {
	
	var topbar_height = $('.elgg-page-topbar').height();
	
	return {
		language: elgg.config.current_language,
		ui: {
			viewportOffset: {
				top: topbar_height
			}
		},
		removePlugins: [
			'MediaEmbedToolbar', // is not yet supported in CKEditor5 but throws console warning
			'MediaEmbed' // this plugin only provides backend oembed support. Can be enabled by plugin that provides frontend support
		]
	};
});
