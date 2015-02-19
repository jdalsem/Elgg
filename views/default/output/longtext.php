<?php
/**
 * Elgg display long text
 * Displays a large amount of text, with new lines converted to line breaks
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] The text to display
 * @uses $vars['parse_urls'] Whether to turn urls into links. Default is true.
 * @uses $vars['parse_emails'] Whether to turn emails into links. Default is the behaviour of 'parse_urls'.
 * @uses $vars['class']
 */

$class = 'elgg-output';
$additional_class = elgg_extract('class', $vars, '');
if ($additional_class) {
	$vars['class'] = "$class $additional_class";
} else {
	$vars['class'] = $class;
}

$parse_urls = elgg_extract('parse_urls', $vars, true);
$parse_emails = elgg_extract('parse_emails', $vars, $parse_urls);
unset($vars['parse_urls']);
unset($vars['parse_emails']);

$text = $vars['value'];
unset($vars['value']);

if ($parse_urls) {
	$text = parse_urls($text);
}

if ($parse_emails) {
	$text = elgg_parse_emails($text);
}

$text = filter_tags($text);

$text = elgg_autop($text);

$attributes = elgg_format_attributes($vars);

echo "<div $attributes>$text</div>";
