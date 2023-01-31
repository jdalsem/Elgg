<?php

/* @var ElggPlugin $plugin */
$plugin = elgg_extract('entity', $vars);

$fields = [
	[
		'#type' => 'text',
		'#label' => elgg_echo('ckeditor:settings:toolbar_default'),
		'#help' => elgg_echo('ckeditor:settings:toolbar_default:help'),
		'name' => 'params[toolbar_default]',
		'value' => $plugin->toolbar_default,
	],
	[
		'#type' => 'text',
		'#label' => elgg_echo('ckeditor:settings:toolbar_simple'),
		'#help' => elgg_echo('ckeditor:settings:toolbar_simple:help'),
		'name' => 'params[toolbar_simple]',
		'value' => $plugin->toolbar_simple,
	],
	[
		'#type' => 'hidden',
		'name' => 'flush_cache',
		'value' => 1,
	],
];

foreach ($fields as $field) {
	echo elgg_view_field($field);
}

