<?php
/**
 * Layout of a river item
 *
 * @uses $vars['item'] ElggRiverItem
 */

$item = elgg_extract('item', $vars);
/* @var ElggRiverItem $item */

echo elgg_view('page/components/image_block', [
	'image' => elgg_view('river/elements/image', $vars),
	'body' => elgg_view('river/elements/summary', $vars),
	'image_alt' => elgg_view_menu('river', [
		'item' => $item,
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz elgg-menu-icon-only',
	]),
]);

echo elgg_view('river/elements/body', $vars);

echo elgg_view('river/elements/social', $vars);

$responses = elgg_view('river/elements/responses', $vars);
if ($responses) {
	echo elgg_format_element('div', ['class' => 'elgg-river-responses'], $responses);
}
