<?php
/**
 * Avatar crop form
 *
 * @uses $vars['entity']
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof \ElggUser)) {
	return;
}

elgg_load_js('cropper/cropper');

$master_img = elgg_view('output/img', array(
	'src' => $entity->getIconUrl('master'),
	'alt' => elgg_echo('avatar'),
	'class' => 'mrl',
	'id' => 'user-avatar-cropper',
));

$preview_img = elgg_view('output/img', array(
	'src' => $entity->getIconUrl('master'),
	'alt' => elgg_echo('avatar'),
));

$coords = ['x1', 'x2', 'y1', 'y2'];
foreach ($coords as $coord) {
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => $coord,
		'value' => $entity->$coord,
	]);
}

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $entity->guid,
]);


?>
<div class="clearfix">
	<?php echo $master_img; ?>
	<div id="user-avatar-preview-title"><label><?php echo elgg_echo('avatar:preview'); ?></label></div>
	<div id="user-avatar-preview"><?php echo $preview_img; ?></div>
</div>
<script>
	require(['cropper/cropper'], function(cropper) {
		$('#user-avatar-cropper').cropper({
			mode: 1,
			aspectRatio: 1,
			crop: function (data) {
				$('input[data-coord="x1"]', $('.elgg-form-avatar-crop')).val(data.x);
				$('input[data-coord="x2"]', $('.elgg-form-avatar-crop')).val((data.x + data.width));
				$('input[data-coord="y1"]', $('.elgg-form-avatar-crop')).val(data.y);
				$('input[data-coord="y2"]', $('.elgg-form-avatar-crop')).val((data.y + data.height));
			}
		});
	});
</script>
<?php

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('avatar:create'),
]);

elgg_set_form_footer($footer);
