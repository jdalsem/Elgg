<?php
/**
 * Elgg bookmark view
 *
 * @package ElggBookmarks
 */

$full = elgg_extract('full_view', $vars, false);
$bookmark = elgg_extract('entity', $vars, false);

if (!$bookmark) {
	return;
}

$link = elgg_view('output/url', array('href' => $bookmark->address));
$description = elgg_view('output/longtext', array('value' => $bookmark->description, 'class' => 'pbl'));

$subtitle = elgg_view('page/elements/by_line', $vars);
$subtitle .= elgg_view('output/categories', $vars);

$metadata = '';
if (!elgg_in_context('widgets') && !elgg_in_context('gallery')) {
	// only show entity menu outside of widgets and gallery view
	$metadata = elgg_view_menu('entity', array(
		'entity' => $vars['entity'],
		'handler' => 'bookmarks',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));
}

if ($full && !elgg_in_context('gallery')) {

	$params = array(
		'entity' => $bookmark,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
	);
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	$bookmark_icon = elgg_view_icon('push-pin-alt');
	$body = <<<HTML
<div class="bookmark elgg-content mts">
	$bookmark_icon<span class="elgg-heading-basic mbs">$link</span>
	$description
</div>
HTML;

	$responses = '';
	if (elgg_extract('show_responses', $vars, false)) {
		$responses = elgg_view_comments($bookmark);
	}
	echo elgg_view('object/elements/full', array(
		'entity' => $bookmark,
		'summary' => $summary,
		'body' => $body,
		'responses' => $responses,
	));

} elseif (elgg_in_context('gallery')) {
	$title = elgg_format_element('h3', [], $bookmark->title);
	$subtitle = elgg_format_element('p', ['class' => 'subtitle'], "$owner_link $date");
	
	echo elgg_format_element('div', ['class' => 'bookmarks-gallery-item'], $title . $subtitle);
} else {
	// brief view
	$url = $bookmark->address;
	$display_text = $url;
	$excerpt = elgg_get_excerpt($bookmark->description);
	if ($excerpt) {
		$excerpt = " - $excerpt";
	}

	if (strlen($url) > 25) {
		$bits = parse_url($url);
		if (isset($bits['host'])) {
			$display_text = $bits['host'];
		} else {
			$display_text = elgg_get_excerpt($url, 100);
		}
	}

	$link = elgg_view('output/url', array(
		'href' => $bookmark->address,
		'text' => $display_text,
	));

	$content = elgg_view_icon('push-pin-alt') . "$link{$excerpt}";

	$params = array(
		'entity' => $bookmark,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'content' => $content,
	);
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
}
