<?php
/**
 * View for blog objects
 *
 * @package Blog
 */

$full = elgg_extract('full_view', $vars, false);
$blog = elgg_extract('entity', $vars);

if (!$blog) {
	return;
}

$excerpt = $blog->excerpt;
if (!$excerpt) {
	$excerpt = elgg_get_excerpt($blog->description);
}

$subtitle = elgg_view('page/elements/by_line', $vars);
$subtitle .= elgg_view('output/categories', $vars);

$metadata = '';
if (!elgg_in_context('widgets')) {
	// only show entity menu outside of widgets
	$metadata = elgg_view_menu('entity', array(
		'entity' => $vars['entity'],
		'handler' => 'blog',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));
}

$params = [
	'entity' => $blog,
	'metadata' => $metadata,
	'subtitle' => $subtitle,
	'content' => $excerpt,
];

if ($full) {

	$summary_params = $vars;
	$summary_params['title'] = false;
	$summary_params['metadata'] = $metadata;
	$summary_params['subtitle'] = $subtitle;
	
	$params['summary'] = elgg_view('object/elements/summary', $summary_params);
	
	$params['body'] = elgg_view('output/longtext', [
		'value' => $blog->description,
		'class' => 'blog-post',
	]);

	if (elgg_extract('show_responses', $vars, false)) {
		// check to see if we should allow comments
		if ($blog->comments_on != 'Off' && $blog->status == 'published') {
			$params['responses'] = elgg_view_comments($blog);
		}
	}

	echo elgg_view('object/elements/full', $params);

} else {
	// brief view
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
}
