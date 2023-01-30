/**
 * This module can be used to bind CKEditor to a textarea
 * <code>
 *	  require(['elgg-ckeditor'], function(editor) {
 *	      editor.bind('textarea');
 *	  });
 * </code>
 */
define(['jquery', 'elgg', 'elgg/hooks', 'ckeditor/ckeditor'], function ($, elgg, hooks, CKEDITOR) {
	return {
		init: function (selector, editor_type) {
			if ($(selector).length === 0) {
				return;
			}
				
			editor_type = editor_type || 'default';

			require(['ckeditor/config/' + editor_type], function (config) {
				config = hooks.trigger('config', 'ckeditor', {'editor': editor_type}, config);
			
				CKEDITOR.create(document.querySelector(selector), config)
					.then(editor => {
						window.editor = editor;
						
						editor.model.document.on('change', () => {
							// on change updateSourceElement()
							editor.updateSourceElement();
						});
						
					});
			});
		}
	};
});
