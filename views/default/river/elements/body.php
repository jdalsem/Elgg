<?php
/**
 * Body of river item
 *
 * @uses $vars['message']     Optional message (usually excerpt of text)
 * @uses $vars['attachments'] Optional attachments (displaying icons or other non-text data)
 */

$message = elgg_extract('message', $vars, '');
if ($message !== '') {
	echo elgg_format_element('div', ['class' => 'elgg-river-message'], $message);
}

$attachments = elgg_extract('attachments', $vars, '');
if ($attachments !== '') {
	echo elgg_format_element('div', ['class' => 'elgg-river-attachments clearfix'], $attachments);
}
