<?php

$vars['class'] = 'elgg-menu-hz';

$menu = elgg_view_menu('social', $vars);
if (!$menu) {
	return;
}

echo elgg_format_element('div', ['class' => 'elgg-listing-full-social'], $menu);
