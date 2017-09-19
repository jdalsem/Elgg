<?php

namespace Elgg\Upgrades;

use Elgg\Upgrade\Batch;
use Elgg\Upgrade\Result;

/**
 * Change
 */
class RiverChangeFriendsAccessToACL implements Batch {

	/**
	 * {@inheritdoc}
	 */
	public function getVersion() {
		return 2017090500;
	}

	/**
	 * {@inheritdoc}
	 */
	public function needsIncrementOffset() {
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function shouldBeSkipped() {
		if ($this->countItems()) {
			return false;
		}
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function countItems() {
		return count($this->defaults);
	}

	/**
	 * {@inheritdoc}
	 */
	public function run(Result $result, $offset) {

		foreach ($this->defaults as $name => $value) {
			$existing_value = elgg_get_config($name);
			if (is_null($existing_value)) {
				elgg_save_config($name, $value);
			}

			$result->addSuccesses();
		}

		return $result;

	}

}
