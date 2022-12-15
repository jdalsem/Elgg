<?php

namespace Elgg\CKEditor;

/**
 * Extends the HTMLawed rules for CKEditor features
 *
 * @since 5.0
 * @internal
 */
class HTMLawed {
	
	/**
	 * Allows additional styles
	 *
	 * @param \Elgg\Event $hook 'allowed_styles', 'htmlawed'
	 *
	 * @return array
	 */
	public static function extendAllowedStyles(\Elgg\Event $event) {
		$allowed_styles = $event->getValue();
		
		switch ($event->getParam('tag')) {
			case 'ul':
			case 'ol':
				$allowed_styles[] = 'list-style-type';
				break;
		}
			
		return $allowed_styles;
	}
}
