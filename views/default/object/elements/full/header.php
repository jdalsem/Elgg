<?php
/**
 * Object full view header
 *
 * @uses $vars['icon']          HTML for the content icon (optional, defaults to icon of owner entity)
 * @uses $vars['summary']       HTML for the content summary
 * @uses $vars['header_params'] Vars to pass to image block/header wrapper
 */

$summary = elgg_extract('summary', $vars);

$header_params = (array) elgg_extract('header_params', $vars, []);
$header_params['class'] = elgg_extract_class($header_params, 'elgg-listing-full-header');

echo elgg_format_element('div', $header_params, $summary);
