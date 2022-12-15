define(['jquery', 'ckeditor/config/base', 'ckeditor/config/mentions', 'ckeditor/config/file_upload'], function($, base, mentions, file_upload) {
	return $.extend(base, mentions, file_upload, {
		toolbar: {
			items: [
				'Bold', 'Italic', 'Underline', 'Strikethrough',
				'|',
				'NumberedList', 'BulletedList', 'outdent', 'indent',
				'|',
				'Link', 'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo',
				//,  'Image', 'Blockquote', 'Paste', 'PasteFromWord', 'Maximize',
				'|',
				'RemoveFormat'
			]
		},
		image: {
			toolbar: ['imageTextAlternative', 'imageStyle:inline', 'imageStyle:block', 'imageStyle:side', 'linkImage']
		},
		table: {
			contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableCellProperties', 'tableProperties']
		}
	});
});

// 					allowedContent: true,
// 					entities: false,
// 					baseHref: elgg.get_site_url(),
// 					extraPlugins: 'blockimagepaste',
// 					contentsCss: elgg.get_simplecache_url('elgg/wysiwyg.css'),
// 					disableNativeSpellChecker: false,
// 					disableNativeTableHandles: false,
// 					removeDialogTabs: 'image:advanced;image:Link;link:advanced;link:target',
// 					customConfig: false, //no additional config.js
// 					stylesSet: false, //no additional styles.js
