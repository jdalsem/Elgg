<?php
/**
 * Group entity view
 *
 * @package ElggGroups
 */

$group = elgg_extract('entity', $vars);
if (!($group instanceof \ElggGroup)) {
	return;
}

if ($vars['full_view']) {
	echo elgg_view('groups/profile/summary', $vars);
	return;
}

// brief view
if (!elgg_in_context('owner_block') && !elgg_in_context('widgets')) {
	// only show entity menu outside of widgets and owner block
	$vars['metadata'] = elgg_view_menu('entity', [
		'entity' => $group,
		'handler' => 'groups',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	]);
}

$vars['subtitle'] = $group->briefdescription;

echo elgg_view('group/elements/summary', $vars);
