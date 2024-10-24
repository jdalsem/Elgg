<?php

namespace Elgg\Page;

use Elgg\Database\EntityTable;
use Elgg\Database\UsersTable;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Http\Request;
use Elgg\Invoker;
use Elgg\PluginHooksService;
use Elgg\Router\Route;

/**
 * Holds page owner related functions
 *
 * @internal
 *
 * @since 3.1
 */
class PageOwnerService {

	/**
	 * @var Request
	 */
	protected $request;

	/**
	 * @var EntityTable
	 */
	protected $entity_table;

	/**
	 * @var UsersTable
	 */
	protected $users_table;

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;

	/**
	 * @var Invoker
	 */
	protected $invoker;

	/**
	 * @var int
	 */
	protected $page_owner_guid = 0;
	
	/**
	 * Constructor
	 *
	 * @param Request            $request      Request
	 * @param EntityTable        $entity_table Entity table
	 * @param PluginHooksService $hooks        Hooks
	 * @param UsersTable         $users_table  Users table
	 * @param Invoker            $invoker      Invoker
	 */
	public function __construct(
			Request $request,
			EntityTable $entity_table,
			PluginHooksService $hooks,
			UsersTable $users_table,
			Invoker $invoker
	) {
		$this->request = $request;
		$this->entity_table = $entity_table;
		$this->hooks = $hooks;
		$this->users_table = $users_table;
		$this->invoker = $invoker;
		
		$this->page_owner_guid = $this->detectPageOwnerFromRoute();
	}
	
	/**
	 * Detects page owner from route
	 *
	 * @return int detected page owner guid or void if none detected
	 */
	protected function detectPageOwnerFromRoute(): int {
		$route = $this->request->getRoute();
		if (!$route instanceof Route) {
			return 0;
		}
		
		$page_owner = $route->resolvePageOwner();
		if (!$page_owner instanceof \ElggEntity) {
			return 0;
		}
		
		return $page_owner->guid;
	}
	
	/**
	 * Sets a new page owner guid
	 *
	 * @param int $guid the new page owner
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return void
	 */
	public function setPageOwnerGuid(int $guid = 0) {
		if ($guid < 0) {
			throw new InvalidArgumentException(__METHOD__ . ' requires a positive integer.');
		}
		$this->page_owner_guid = $guid;
	}
	
	/**
	 * Return the current page owner guid
	 *
	 * @return int
	 */
	public function getPageOwnerGuid() {
		return $this->page_owner_guid;
	}
	
	/**
	 * Returns the page owner entity
	 *
	 * @return \ElggEntity the current page owner or null if none.
	 */
	public function getPageOwnerEntity(): ?\ElggEntity {
		return $this->entity_table->get($this->getPageOwnerGuid());
	}
}
