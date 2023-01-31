define(['jquery', 'ckeditor/config/base', 'ckeditor/config/mentions', 'ckeditor/config/file_upload'], function($, base, mentions, file_upload) {
	return $.extend(base, mentions, file_upload, {
		toolbar: {
			items: [
				'Bold', 'Italic', 'Underline', 'Strikethrough', 'RemoveFormat'
			]
		}
	});
});
