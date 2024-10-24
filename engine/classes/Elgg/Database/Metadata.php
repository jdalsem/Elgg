<?php

namespace Elgg\Database;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Elgg\Database\Clauses\AnnotationWhereClause;
use Elgg\Database\Clauses\EntityWhereClause;
use Elgg\Database\Clauses\MetadataWhereClause;
use Elgg\Database\Clauses\RelationshipWhereClause;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Exceptions\InvalidParameterException;
use Elgg\Exceptions\LogicException;

/**
 * Metadata repository contains methods for fetching metadata from database or performing
 * calculations on entity properties.
 *
 * @internal
 */
class Metadata extends Repository {

	/**
	 * {@inheritdoc}
	 */
	public function count() {
		$qb = Select::fromTable('metadata', 'n_table');

		$count_expr = $this->options->distinct ? "DISTINCT n_table.id" : "*";
		$qb->select("COUNT({$count_expr}) AS total");

		$qb = $this->buildQuery($qb);

		$result = _elgg_services()->db->getDataRow($qb);

		if (empty($result)) {
			return 0;
		}

		return (int) $result->total;
	}

	/**
	 * Performs a mathematical calculation on metadata or metadata entity's properties
	 *
	 * @param string $function      Valid numeric function
	 * @param string $property      Property name
	 * @param string $property_type 'attribute'|'metadata'|'annotation'
	 *
	 * @return int|float
	 * @throws InvalidParameterException
	 */
	public function calculate($function, $property, $property_type = null) {

		if (!in_array(strtolower($function), QueryBuilder::$calculations)) {
			throw new InvalidArgumentException("'$function' is not a valid numeric function");
		}

		if (!isset($property_type)) {
			$property_type = 'metadata';
		}

		$qb = Select::fromTable('metadata', 'n_table');

		switch ($property_type) {
			case 'attribute':
				if (!in_array($property, \ElggEntity::PRIMARY_ATTR_NAMES)) {
					throw new InvalidParameterException("'$property' is not a valid attribute");
				}

				/**
				 * @todo When no entity constraints are present, do we need to ensure that entity access clause is added?
				 */
				$alias = $qb->joinEntitiesTable('n_table', 'entity_guid', 'inner', 'e');
				$qb->addSelect("{$function}({$alias}.{$property}) AS calculation");
				break;

			case 'metadata' :
				$alias = 'n_table';
				if (!empty($this->options->metadata_name_value_pairs) && $this->options->metadata_name_value_pairs[0]->names != $property) {
					$alias = $qb->joinMetadataTable('n_table', 'entity_guid', $property);
				}
				$qb->addSelect("{$function}($alias.value) AS calculation");
				break;

			case 'annotation' :
				$alias = $qb->joinAnnotationTable('n_table', 'entity_guid', $property);
				$qb->addSelect("{$function}({$alias}.value) AS calculation");
				break;
		}

		$qb = $this->buildQuery($qb);

		$result = _elgg_services()->db->getDataRow($qb);

		if (empty($result)) {
			return 0;
		}

		return (int) $result->calculation;
	}

	/**
	 * Fetch metadata
	 *
	 * @param int      $limit    Limit
	 * @param int      $offset   Offset
	 * @param callable $callback Custom callback
	 *
	 * @return \ElggMetadata[]
	 */
	public function get($limit = null, $offset = null, $callback = null) {

		$qb = Select::fromTable('metadata', 'n_table');

		$distinct = $this->options->distinct ? "DISTINCT" : "";
		$qb->select("$distinct n_table.*");

		$this->expandInto($qb, 'n_table');

		$qb = $this->buildQuery($qb);

		// Keeping things backwards compatible
		$original_order = elgg_extract('order_by', $this->options->__original_options);
		if (empty($original_order) && $original_order !== false) {
			$qb->addOrderBy('n_table.time_created', 'asc');
			$qb->addOrderBy('n_table.id', 'asc');
		}

		if ($limit > 0) {
			$qb->setMaxResults((int) $limit);
			$qb->setFirstResult((int) $offset);
		}

		$callback = $callback ? : $this->options->callback;
		if (!isset($callback)) {
			$callback = function ($row) {
				return new \ElggMetadata($row);
			};
		}

		return _elgg_services()->db->getData($qb, $callback);
	}

	/**
	 * Execute the query resolving calculation, count and/or batch options
	 *
	 * @return array|\ElggData[]|\ElggMetadata[]|false|int
	 * @throws LogicException
	 */
	public function execute() {

		if ($this->options->annotation_calculation) {
			$clauses = $this->options->annotation_name_value_pairs;
			if (count($clauses) > 1 && $this->options->annotation_name_value_pairs_operator !== 'OR') {
				throw new LogicException("Annotation calculation can not be performed on multiple annotation name value pairs merged with AND");
			}

			$clause = array_shift($clauses);

			return $this->calculate($this->options->annotation_calculation, $clause->names, 'annotation');
		} else if ($this->options->metadata_calculation) {
			$clauses = $this->options->metadata_name_value_pairs;
			if (count($clauses) > 1 && $this->options->metadata_name_value_pairs_operator !== 'OR') {
				throw new LogicException("Metadata calculation can not be performed on multiple metadata name value pairs merged with AND");
			}

			$clause = array_shift($clauses);

			return $this->calculate($this->options->metadata_calculation, $clause->names, 'metadata');
		} else if ($this->options->count) {
			return $this->count();
		} else if ($this->options->batch) {
			return $this->batch($this->options->limit, $this->options->offset, $this->options->callback);
		} else {
			return $this->get($this->options->limit, $this->options->offset, $this->options->callback);
		}
	}

	/**
	 * Build a database query
	 *
	 * @param QueryBuilder $qb
	 *
	 * @return QueryBuilder
	 */
	protected function buildQuery(QueryBuilder $qb) {

		$ands = [];

		foreach ($this->options->joins as $join) {
			$join->prepare($qb, 'n_table');
		}

		foreach ($this->options->wheres as $where) {
			$ands[] = $where->prepare($qb, 'n_table');
		}

		$ands[] = $this->buildPairedMetadataClause($qb, $this->options->metadata_name_value_pairs, $this->options->metadata_name_value_pairs_operator);
		$ands[] = $this->buildEntityWhereClause($qb);
		$ands[] = $this->buildPairedMetadataClause($qb, $this->options->search_name_value_pairs, 'OR');
		$ands[] = $this->buildPairedAnnotationClause($qb, $this->options->annotation_name_value_pairs, $this->options->annotation_name_value_pairs_operator);
		$ands[] = $this->buildPairedRelationshipClause($qb, $this->options->relationship_pairs);

		$ands = $qb->merge($ands);

		if (!empty($ands)) {
			$qb->andWhere($ands);
		}

		return $qb;
	}

	/**
	 * Process entity attribute wheres
	 * Joins entities table on entity guid in metadata table and applies where clauses
	 *
	 * @param QueryBuilder $qb Query builder
	 *
	 * @return \Closure|CompositeExpression|mixed|null|string
	 */
	protected function buildEntityWhereClause(QueryBuilder $qb) {

		// Even if all of these properties are empty, we want to add this clause regardless,
		// to ensure that entity access clauses are appended to the query

		$joined_alias = $qb->joinEntitiesTable('n_table', 'entity_guid', 'inner', 'e');
		return EntityWhereClause::factory($this->options)->prepare($qb, $joined_alias);
	}

	/**
	 * Process metadata name value pairs
	 * Applies where clauses to the selected metadata table
	 *
	 * @param QueryBuilder          $qb      Query builder
	 * @param MetadataWhereClause[] $clauses Where clauses
	 * @param string                $boolean Merge boolean
	 *
	 * @return CompositeExpression|string
	 */
	protected function buildPairedMetadataClause(QueryBuilder $qb, $clauses, $boolean = 'AND') {
		$parts = [];


		foreach ($clauses as $clause) {
			$parts[] = $clause->prepare($qb, 'n_table');
		}

		return $qb->merge($parts, $boolean);
	}

	/**
	 * Process annotation name value pairs
	 * Joins annotation table on entity_guid in the metadata table and applies where clauses
	 *
	 * @param QueryBuilder            $qb      Query builder
	 * @param AnnotationWhereClause[] $clauses Where clauses
	 * @param string                  $boolean Merge boolean
	 *
	 * @return CompositeExpression|string
	 */
	protected function buildPairedAnnotationClause(QueryBuilder $qb, $clauses, $boolean = 'AND') {
		$parts = [];

		foreach ($clauses as $clause) {
			if (strtoupper($boolean) === 'OR' || count($clauses) > 1) {
				$joined_alias = $qb->joinAnnotationTable('n_table', 'entity_guid');
			} else {
				$joined_alias = $qb->joinAnnotationTable('n_table', 'entity_guid', $clause->names);
			}
			$parts[] = $clause->prepare($qb, $joined_alias);
		}

		return $qb->merge($parts, $boolean);
	}

	/**
	 * Process relationship name value pairs
	 * Joins relationship table on entity_guid in the metadata table and applies where clauses
	 *
	 * @note Note that $relationship_join_on does not apply here, as there is only one guid column in the metadata table
	 *
	 * @param QueryBuilder              $qb      Query builder
	 * @param RelationshipWhereClause[] $clauses Where clauses
	 * @param string                    $boolean Merge boolean
	 *
	 * @return CompositeExpression|string
	 */
	protected function buildPairedRelationshipClause(QueryBuilder $qb, $clauses, $boolean = 'AND') {
		$parts = [];

		foreach ($clauses as $clause) {
			if (strtoupper($boolean) == 'OR' || count($clauses) > 1) {
				$joined_alias = $qb->joinRelationshipTable('n_table', 'entity_guid', null, $clause->inverse);
			} else {
				$joined_alias = $qb->joinRelationshipTable('n_table', 'entity_guid', $clause->names, $clause->inverse);
			}
			$parts[] = $clause->prepare($qb, $joined_alias);
		}

		return $qb->merge($parts, $boolean);
	}
}
