<?php
/**
 * Short summary of the action that occurred
 *
 * @uses $vars['item'] ElggRiverItem
 * @uses $vars['summary']     Alternate summary (the short text summary of action)
 */

$item = elgg_extract('item', $vars);

$summary = elgg_extract('summary', $vars);
if ($summary === null) {
	$subject = $item->getSubjectEntity();
	$object = $item->getObjectEntity();
	
	$subject_link = elgg_view('output/url', array(
		'href' => $subject->getURL(),
		'text' => $subject->name,
		'class' => 'elgg-river-subject',
		'is_trusted' => true,
	));
	
	$object_text = $object->title ? $object->title : $object->name;
	$object_link = elgg_view('output/url', array(
		'href' => $object->getURL(),
		'text' => elgg_get_excerpt($object_text, 100),
		'class' => 'elgg-river-object',
		'is_trusted' => true,
	));
	
	$action = $item->action_type;
	$type = $item->type;
	$subtype = $item->subtype ? $item->subtype : 'default';
	
	// check summary translation keys.
	// will use the $type:$subtype if that's defined, otherwise just uses $type:default
	$key = "river:$action:$type:$subtype";
	$summary = elgg_echo($key, array($subject_link, $object_link));
	
	if ($summary == $key) {
		$key = "river:$action:$type:default";
		$summary = elgg_echo($key, array($subject_link, $object_link));
	}
}

if ($summary === false) {
	$subject = $item->getSubjectEntity();
	$summary = elgg_view('output/url', array(
		'href' => $subject->getURL(),
		'text' => $subject->name,
		'class' => 'elgg-river-subject',
		'is_trusted' => true,
	));
}

$summary_parts = [$summary];

$container = $item->getObjectEntity()->getContainerEntity();
if ($container instanceof ElggGroup && $container->guid != elgg_get_page_owner_guid()) {
	$group_link = elgg_view('output/url', array(
		'href' => $container->getURL(),
		'text' => $container->name,
		'is_trusted' => true,
	));
	$summary_parts[] = elgg_echo('river:ingroup', array($group_link));
}

$summary_parts[] = elgg_format_element('span', ['class' => 'elgg-river-timestamp'], elgg_view_friendly_time($item->getTimePosted()));

echo elgg_format_element('div', [
	'class' => 'elgg-river-summary',
], implode(' ', $summary_parts));
