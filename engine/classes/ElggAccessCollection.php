<?php

use Elgg\Exceptions\RuntimeException as ElggRuntimeException;

/**
 * Access collection class
 *
 * @property-read int    $id         The unique identifier (read-only)
 * @property      int    $owner_guid GUID of the owner
 * @property      string $name       Name of the collection
 */
class ElggAccessCollection extends ElggData {

	/**
	 * Create an access collection object
	 *
	 * @param stdClass $row Database row
	 */
	public function __construct(stdClass $row = null) {
		$this->initializeAttributes();

		foreach ((array) $row as $key => $value) {
			$this->attributes[$key] = $value;
		}
	}

	/**
	 * Initialize the attributes array
	 *
	 * @see ElggData::initializeAttributes()
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['id'] = null;
		$this->attributes['owner_guid'] = null;
		$this->attributes['name'] = null;
		$this->attributes['subtype'] = null;
	}

	/**
	 * Set an attribute
	 *
	 * @param string $name  Name
	 * @param mixed  $value Value
	 *
	 * @return void
	 * @throws \Elgg\Exceptions\RuntimeException
	 */
	public function __set($name, $value) {
		if (in_array($name, ['id', 'owner_guid', 'subtype'])) {
			throw new ElggRuntimeException("$name can not be set at runtime");
		}
		$this->attributes[$name] = $value;
	}

	/**
	 * Get an attribute
	 *
	 * @param string $name Name
	 * @return mixed
	 */
	public function __get($name) {
		if (array_key_exists($name, $this->attributes)) {
			return $this->attributes[$name];
		}

		return null;
	}

	/**
	 * Returns owner entity of the collection
	 * @return \ElggEntity|null
	 */
	public function getOwnerEntity(): ?\ElggEntity {
		return _elgg_services()->entityTable->get($this->owner_guid);
	}

	/**
	 * Get readable access level name for this collection
	 * @return string
	 */
	public function getDisplayName(): string {

		$filter = function($name = null) {
			if (!isset($name)) {
				$name = _elgg_services()->translator->translate('access:limited:label');
			}
			$params = [
				'access_collection' => $this,
			];
			return (string) _elgg_services()->hooks->trigger('access_collection:name', $this->getType(), $params, $name);
		};

		$user = _elgg_services()->session->getLoggedInUser();
		$owner = $this->getOwnerEntity();
		if (!$user || !$owner) {
			// User is not logged in or does not access to the owner entity:
			// return default 'Limited' label
			return $filter();
		}
		
		if ($user->isAdmin() || $owner->guid == $user->guid) {
			return $filter($this->name);
		}

		return $filter();
	}

	/**
	 * {@inheritdoc}
	 */
	public function save(): bool {
		if ($this->id > 0) {
			return _elgg_services()->accessCollections->rename($this->id, $this->name);
		} else {
			return (bool) _elgg_services()->accessCollections->create($this->name, $this->owner_guid);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete(): bool {
		return _elgg_services()->accessCollections->delete($this->id);
	}

	/**
	 * Check if user can edit this collection
	 *
	 * @param int $user_guid GUID of the user
	 *
	 * @return bool
	 */
	public function canEdit(int $user_guid = null): bool {
		return _elgg_services()->accessCollections->canEdit($this->id, $user_guid);
	}

	/**
	 * Returns members of the access collection
	 *
	 * @param array $options ege options
	 * @return ElggEntity[]|int|false
	 */
	public function getMembers(array $options = []) {
		return _elgg_services()->accessCollections->getMembers($this->id, $options);
	}

	/**
	 * Checks if user is already in access collection
	 *
	 * @param int $member_guid GUID of the user
	 * @return bool
	 */
	public function hasMember(int $member_guid = 0): bool {
		return _elgg_services()->accessCollections->hasUser($member_guid, $this->id);
	}

	/**
	 * Adds a user to access collection
	 *
	 * @param int $member_guid GUID of the user
	 * @return bool
	 */
	public function addMember(int $member_guid = 0): bool {
		return _elgg_services()->accessCollections->addUser($member_guid, $this->id);
	}

	/**
	 * Removes a user from access collection
	 *
	 * @param int $member_guid GUID of the user
	 * @return bool
	 */
	public function removeMember(int $member_guid = 0): bool {
		return _elgg_services()->accessCollections->removeUser($member_guid, $this->id);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getURL(): string {
		$type = $this->getType();
		$params = [
			'access_collection' => $this,
		];
		$url = _elgg_services()->hooks->trigger('access_collection:url', $type, $params);
		return elgg_normalize_url($url);
	}

	/**
	 * {@inheritdoc}
	 */
	public function toObject(array $params = []) {
		$object = new \Elgg\Export\AccessCollection();
		$object->type = $this->getType();
		$object->subtype = $this->getSubtype();
		$object->id = $this->id;
		$object->owner_guid = $this->owner_guid;
		$object->name = $this->name;

		$params['access_collection'] = $this;

		return _elgg_services()->hooks->trigger('to:object', 'access_collection', $params, $object);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSystemLogID(): int {
		return $this->id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getObjectFromID(int $id) {
		return _elgg_services()->accessCollections->get($id);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getType(): string {
		return 'access_collection';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSubtype(): string {
		if (isset($this->subtype)) {
			return $this->subtype;
		}
		
		return $this->name;
	}
}
