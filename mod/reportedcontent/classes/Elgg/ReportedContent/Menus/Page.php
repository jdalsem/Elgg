<?php

namespace Elgg\ReportedContent\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Page {
	
	/**
	 * Add report user link to hover menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:page'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		if (!elgg_in_context('admin')) {
			return;
		}
				
		$return = $event->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'administer_utilities:reportedcontent',
			'text' => elgg_echo('admin:administer_utilities:reportedcontent'),
			'href' => 'admin/administer_utilities/reportedcontent',
			'section' => 'administer',
			'parent_name' => 'administer_utilities',
		]);
	
		return $return;
	}
}
