define(['jquery', 'ckeditor/config/base', 'ckeditor/config/mentions', 'ckeditor/config/file_upload'], function($, base, mentions, file_upload) {
	return $.extend(base, mentions, file_upload, {
		toolbar: {
			items: [
				'Bold', 'Italic', 'Underline', 'Strikethrough', 'RemoveFormat'
			]
		}
	});
});
//
//		toolbar: [['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat']],
//		removeButtons: 'Subscript,Superscript', // To have Underline back
//		allowedContent: true,
//		entities: false,
//		baseHref: elgg.get_site_url(),
//		removePlugins: 'elementspath', // no need to see elementspath
//		extraPlugins: 'blockimagepaste',
//		defaultLanguage: 'en',
//		language: elgg.config.current_language,
//		skin: 'moono-lisa',
//		contentsCss: elgg.get_simplecache_url('elgg/wysiwyg.css'),
//		disableNativeSpellChecker: false,
//		disableNativeTableHandles: false,
//		removeDialogTabs: 'image:advanced;image:Link;link:advanced;link:target',
//		customConfig: false, //no additional config.js
//		stylesSet: false, //no additional styles.js
