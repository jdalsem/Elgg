<?php
/**
 * Elgg 1.9.0 upgrade 2013012200
 * convert_to_innodb
 *
 * Fixes http://trac.elgg.org/ticket/4993
 * Removes fulltext indexes and change table engine to innodb
 */

$db_prefix = elgg_get_config('dbprefix');

if(get_data("SHOW INDEX FROM {$db_prefix}groups_entity WHERE KEY_NAME = 'name_2'")){
	$q = "ALTER TABLE {$db_prefix}groups_entity DROP INDEX name_2";
	update_data($q);
}

if(get_data("SHOW INDEX FROM {$db_prefix}objects_entity WHERE KEY_NAME = 'title'")){
	$q = "ALTER TABLE {$db_prefix}objects_entity DROP INDEX title";
	update_data($q);
}

if(get_data("SHOW INDEX FROM {$db_prefix}sites_entity WHERE KEY_NAME = 'name'")){
	$q = "ALTER TABLE {$db_prefix}sites_entity DROP INDEX name";
	update_data($q);
}

if(get_data("SHOW INDEX FROM {$db_prefix}users_entity WHERE KEY_NAME = 'name'")){
	$q = "ALTER TABLE {$db_prefix}users_entity DROP INDEX name";
	update_data($q);
}

if(get_data("SHOW INDEX FROM {$db_prefix}users_entity WHERE KEY_NAME = 'name_2'")){
	$q = "ALTER TABLE {$db_prefix}users_entity DROP INDEX name_2";
	update_data($q);
}

$tables = array("access_collection_membership","access_collections","annotations","api_users", "config", 
				"datalists", "entities", "entity_relationships", "entity_subtypes", "groups_entity", 
				"metadata", "metastrings", "objects_entity", "private_settings", "river", 
				"sites_entity", "users_entity", "users_sessions");

foreach($tables as $table){
	$q = "ALTER TABLE {$db_prefix}{$table} ENGINE = INNODB";
	update_data($q);
}

