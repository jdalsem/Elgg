define(['jquery', 'ckeditor/config/base', 'ckeditor/config/mentions', 'ckeditor/config/file_upload'], function($, base, mentions, file_upload) {
	return $.extend(base, mentions, file_upload, {
		toolbar: {
			items: [
				'Bold', 'Italic', 'Underline', 'Strikethrough',
				'|',
				'NumberedList', 'BulletedList', 'outdent', 'indent',
				'|',
				'Link', 'imageUpload', 'blockQuote', 'insertTable', 'undo', 'redo',
				'|',
				'RemoveFormat', 'sourceEditing'
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
