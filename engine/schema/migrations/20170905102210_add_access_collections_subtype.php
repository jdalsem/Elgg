<?php

use Phinx\Migration\AbstractMigration;

class AddAccessCollectionsSubtype extends AbstractMigration {
    /**
     * Adds the subtype column and index to the access_collections table
     */
    public function change() {
		$table = $this->table('access_collections');

		if (!$table->hasColumn('subtype')) {
			$table->addColumn('subtype', 'string', [
				'null' => false,
				'default' => '',
				'limit' => 255,
				'after' => 'name',
			]);
		}

		if (!$table->hasIndex('subtype')) {
			$table->addIndex(['subtype'], [
				'name' => 'subtype',
				'unique' => false,
			]);
		}

		$table->save();
    }
}
