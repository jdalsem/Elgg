CKEditor
========


Plugin settings
---------------
You can configure the following in the plugin settings.

- File uploading: if enabled users can upload inline images in the content
- Mentions: if enabled users and groups can be mentioned in the content
- Content linking: xxxxxxxxx
- Toolbar: 

Configuration options
----------------------
CKEditor default configuration is set in the views in the folder `ckeditor/config/`. 
A plugin can modify the configuration object by replacing the view, or by registering
a hook callback function to run on the javascript `'config', 'ckeditor'` hook.
This is where toolbar options and the skin are set.

Content CSS
------------
The content CSS is stored in the view `ckeditor/editor.css`. This view is extended by the
`elements/reset.css` and `elements/typography.css` views so that content appears the
same when editing and viewing. It also contains all the CSS applied to the 
elgg-output class for the same reason (see the typography view).
