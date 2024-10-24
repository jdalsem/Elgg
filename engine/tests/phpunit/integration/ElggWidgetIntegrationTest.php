<?php

use Elgg\IntegrationTestCase;

/**
 * Elgg Test \ElggWidget
 */
class ElggWidgetIntegrationTest extends IntegrationTestCase {
	/**
	 * @var ElggWidget
	 */
	protected $widget;

	/**
	 * @var ElggUser
	 */
	protected $user;

	public function up() {
		$this->user = $this->createUser();
		_elgg_services()->session->setLoggedInUser($this->user);
		
		$this->widget = $this->createObject([
			'subtype' => 'widget',
		], [
			'tags' => 'tag',
		]);
		_elgg_services()->session->removeLoggedInUser();
	}

	public function testSettingAllowedMetadata() {
		$this->widget->title = 'test_title';
		$this->assertEquals('test_title', $this->widget->title);
		$this->assertEquals('test_title', $this->widget->getMetadata('title'));
		$this->widget->description = 'test_description';
		$this->assertEquals('test_description', $this->widget->description);
		$this->assertEquals('test_description', $this->widget->getMetadata('description'));
	}
	
	public function testSettingGuidIsNotAllowed() {
		$current_guid = $this->widget->guid;
		$this->widget->guid = 12345;
		$this->assertEquals($current_guid, $this->widget->guid);
	}
		
	public function testUnsettingMetadata() {
		$this->widget->title = 'testing';
		$this->assertEquals('testing', $this->widget->title);
		$this->assertTrue(isset($this->widget->title));
		unset($this->widget->title);
		$this->assertEmpty($this->widget->title);
		$this->assertFalse(isset($this->widget->title));
	}
		
	public function testGetTitle() {
		$this->widget->title = 'get_title';
		$this->widget->handler = 'widget_handler';
		$this->widget->context = 'widget_context';
		$this->assertEquals('get_title', $this->widget->getDisplayName());
		
		unset($this->widget->title);
		
		elgg_register_widget_type([
			'id' => 'widget_handler',
			'name' => 'title_from_definition',
			'description' => 'widget_description',
			'context' => ['widget_context'],
		]);
		$this->assertEquals('title_from_definition', $this->widget->getDisplayName());
	}
	
	public function testSaveSettings() {
		$params = [
			'setting_1' => 'value_1',
			'setting_1' => 'value_2',
		];
		
		$this->assertFalse($this->widget->saveSettings($params));
		
		elgg_call(ELGG_IGNORE_ACCESS, function() use ($params) {
			$this->assertTrue($this->widget->saveSettings($params));
			
			foreach ($params as $name => $value) {
				$this->assertEquals($value, $this->widget->$name);
			}
		});
	}
	
	public function testHandlerRegistration() {
		$this->assertFalse(elgg_is_widget_type('test_handler'));
		elgg_unregister_widget_type('test_handler');
		
		elgg_register_widget_type(['id' => 'test_handler', 'context' => []]);
		$this->assertTrue(elgg_is_widget_type('test_handler'));
		
		elgg_unregister_widget_type('test_handler');
		$this->assertFalse(elgg_is_widget_type('test_handler'));
	}
}
