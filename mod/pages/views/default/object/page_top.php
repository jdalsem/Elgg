<?php
/**
 * View for page object
 *
 * @package ElggPages
 *
 * @uses $vars['entity']    The page object
 * @uses $vars['full_view'] Whether to display the full view
 * @uses $vars['revision']  This parameter not supported by elgg_view_entity()
 */


$full = elgg_extract('full_view', $vars, false);
$page = elgg_extract('entity', $vars, false);
$revision = elgg_extract('revision', $vars, false);

if (!$page) {
	return;
}

// pages used to use Public for write access
if ($page->write_access_id == ACCESS_PUBLIC) {
	// this works because this metadata is public
	$page->write_access_id = ACCESS_LOGGED_IN;
}


if ($revision) {
	$annotation = $revision;
} else {
	$annotation = $page->getAnnotations(array(
		'annotation_name' => 'page',
		'limit' => 1,
		'reverse_order_by' => true,
	));
	if ($annotation) {
		$annotation = $annotation[0];
	} else {
		elgg_log("Failed to access annotation for page with GUID {$page->guid}", 'WARNING');
		return;
	}
}

$page_icon = elgg_view('pages/icon', array('annotation' => $annotation, 'size' => 'small'));

$editor = get_entity($annotation->owner_guid);
$editor_link = elgg_view('output/url', array(
	'href' => "pages/owner/$editor->username",
	'text' => $editor->name,
	'is_trusted' => true,
));

$date = elgg_view_friendly_time($annotation->time_created);
$editor_text = elgg_echo('pages:strapline', array($date, $editor_link));
$categories = elgg_view('output/categories', $vars);

$subtitle = "$editor_text $categories";

$metadata = '';
// do not show the metadata and controls in widget view
if (!elgg_in_context('widgets')) {
	// If we're looking at a revision, display annotation menu
	if ($revision) {
		$metadata = elgg_view_menu('annotation', array(
			'annotation' => $annotation,
			'sort_by' => 'priority',
			'class' => 'elgg-menu-hz float-alt',
		));
	} else {
		// Regular entity menu
		$metadata = elgg_view_menu('entity', array(
			'entity' => $vars['entity'],
			'handler' => 'pages',
			'sort_by' => 'priority',
			'class' => 'elgg-menu-hz',
		));
	}
}

if ($full) {
	$body = elgg_view('output/longtext', array('value' => $annotation->value));

	$params = array(
		'entity' => $page,
		'metadata' => $metadata,
		'title' => false,
		'subtitle' => $subtitle,
	);

	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	$responses = '';
	if (elgg_extract('show_responses', $vars, false)) {
		$responses = elgg_view_comments($page);
	}

	echo elgg_view('object/elements/full', array(
		'entity' => $page,
		'icon' => $page_icon,
		'summary' => $summary,
		'body' => $body,
		'responses' => $responses,
	));

} else {
	// brief view

	$excerpt = elgg_get_excerpt($page->description);

	$params = array(
		'entity' => $page,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'content' => $excerpt,
		'icon' => $page_icon,
	);
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);

}
