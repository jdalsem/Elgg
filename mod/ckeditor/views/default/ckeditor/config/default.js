define(['jquery', 'ckeditor/config/base', 'ckeditor/config/mentions', 'ckeditor/config/file_upload'], function($, base, mentions, file_upload) {
	return $.extend(base, mentions, file_upload, {
		toolbar: {
			items: [
				'Bold', 'Italic', 'Underline', 'Strikethrough',
				'|',
				'NumberedList', 'BulletedList', 'outdent', 'indent',
				'|',
				'Link', 'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo',
				// 'Paste', 'PasteFromWord', 'Maximize',
				'|',
				'RemoveFormat'
			]
		},
		image: {
			toolbar: ['toggleImageCaption', 'imageTextAlternative', 'imageStyle:inline', 'imageStyle:block', 'imageStyle:side', 'linkImage']
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
// 					disableNativeSpellChecker: false,
// 					disableNativeTableHandles: false,
// 					removeDialogTabs: 'image:advanced;image:Link;link:advanced;link:target',
// 					customConfig: false, //no additional config.js
// 					stylesSet: false, //no additional styles.js
