<?php

namespace Elgg\CKEditor;

/**
 * Hook callbacks for views
 *
 * @since 5.0
 * @internal
 */
class Views {

	/**
	 * Adds an ID to the view vars if not set
	 *
	 * @param \Elgg\Event $event 'view_vars', 'input/longtext'
	 *
	 * @return array
	 */
	public static function setInputLongTextIDViewVar(\Elgg\Event $event) {
		$vars = $event->getValue();
		$id = elgg_extract('id', $vars);
		if ($id !== null) {
			return;
		}
		
		// input/longtext view vars need to contain an id for editors to be initialized
		// random id generator is the same as in input/longtext
		$vars['id'] = 'elgg-input-' . base_convert(mt_rand(), 10, 36);
	
		return $vars;
	}

	/**
	 * Sets the toolbar config if configured
	 *
	 * @param \Elgg\Event $event 'elgg.data', 'site'
	 *
	 * @return array
	 */
	public static function setToolbarConfig(\Elgg\Event $event) {
		$result = $event->getValue();
		
		$cleanup = function(string $text) {
			$buttons = explode(',', trim($text));
			
			$buttons = array_map(function($val) {
				return trim(trim($val), "'\"");
			}, $buttons);
						
			return array_values(array_filter($buttons));
		};
		
		$result['ckeditor'] = [
			'toolbar_default' => $cleanup((string) elgg_get_plugin_setting('toolbar_default', 'ckeditor')) ?: null,
			'toolbar_simple' => $cleanup((string) elgg_get_plugin_setting('toolbar_simple', 'ckeditor')) ?: null,
		];
		
		return $result;
	}
}
