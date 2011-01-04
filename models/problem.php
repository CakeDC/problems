<?php
/**
 * Copyright 2009-2010, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009-2010, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Problem Model
 *
 * @package problems
 * @subpackage problems.models
 */
class Problem extends AppModel {

/**
 * Name
 *
 * @var string $name
 */
	public $name = 'Problem';

/**
 * modelTypes
 *
 * @var string
 */
	public $modelTypes = array();

/**
 * Types
 *
 * @var string The possible problem types
 */
	public $types = array();

/**
 * Different offensive states: ignore, yes, no
 *
 * @see Problem::__construct()
 * @var array
 */
	public $offensiveStatuses = array();

/**
 * Validation parameters
 *
 * @var array
 */
	public $validate = array();

/**
 * Custom find methods to use
 *
 * @var array
 */
	public $_findMethods = array('totals' => true);

/**
 * Constructor
 *
 * @param mixed $id Model ID
 * @param string $table Table name
 * @param string $ds Datasource
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->validate = array(
			'user_id' => array(
				'notempty' => array('rule' => array('notempty'), 'required' => true, 'allowEmpty' => false, 'message' => __d('problems', 'Please enter a User'))),
			'foreign_key' => array(
				'notempty' => array('rule' => array('notempty'), 'required' => true, 'allowEmpty' => false, 'message' => __d('problems', 'Please select item'))),
			'type' => array(
				'inList' => array('rule' => array('validType'), 'required' => false, 'allowEmpty' => true, 'message' => __d('problems', 'Please enter a valid problem type'))),
			'description' => array(
				'notempty' => array('rule' => array('notempty'), 'required' => true, 'allowEmpty' => false, 'message' => __d('problems', 'Please enter a description of the problem.'))),
		);

		$this->offensiveStatuses = array(
			-1 => __d('problems', 'Ignore'),
			0 => __d('problems', 'No'),
			1 => __d('problems', 'Yes'));

		$this->types = array(
			'spam' => __d('problems', 'Spam'),
			'sexual' => __d('problems', 'Sexual Content'),
			'insult_racism' => __d('problems', 'Insult/Racism'),
			'stolen' => __d('problems', 'Stolen Content'),
			'other' => __d('problems', 'Other'));
	}

/**
 * Adds a new record to the database
 *
 * @param string $foreignKey, object id
 * @param string $userId, user id
 * @param array post data, should be Contoller->data
 * @return array
 */
	public function add($model, $foreignKey = null, $userId = null, $data = null) {
		if (!in_array($model, $this->modelTypes)) {
			throw new OutOfBoundsException(__d('problems', 'Could not save the Problem of unallowed type.'));;
		}

		$options = array(
			'contain' => array(),
			'conditions' => array(
				$this->alias . '.foreign_key' => $foreignKey,
				$this->alias . '.model' => $model,
				$this->alias . '.user_id' => $userId));
		if (!empty($data['Problem']['type'])) {
			$options['conditions'][$this->alias . '.type'] = $data['Problem']['type'];
		}
		$problem = $this->find('count', $options);

		if (!empty($problem) && !empty($data['Problem']['type'])) {
			$objectHumanName = Inflector::humanize(Inflector::underscore($model));
			throw new LogicException(sprintf(__d('problems', 'You have already reported this %s!'), __(strtolower($objectHumanName))));
		}

		if (!empty($data)) {
			$data[$this->alias]['model'] = $model;
			$data[$this->alias]['foreign_key'] = $foreignKey;
			$data[$this->alias]['user_id'] = $userId;
			$this->create();
			$result = $this->save($data);
			if ($result !== false) {
				$this->data = array_merge($data, $result);
				return true;
			} else {
				throw new OutOfBoundsException(__d('problems', 'Could not save the Problem, please check your inputs.'));
			}
			return $return;
		}
	}

/**
 * Edits an existing Problem.
 *
 * @param string $id, problem id
 * @param string $userId, user id
 * @param array $data, controller post data usually $this->data
 * @return mixed True on successfully save else post data as array
 */
	public function edit($id = null, $userId = null, $data = null) {
		$this->_bindAssociatedModels();
		$options = array(
			'contain' => array('User'),
			'conditions' => array(
				$this->alias . '.id' => $id));
		if ($userId !== false) {
			$options['conditions'][$this->alias . '.user_id'] = $userId;
		}
		$problem = $this->find('first', $options);

		if (empty($problem)) {
			throw new OutOfBoundsException(__d('problems', 'Invalid Problem'));
		}

		$this->set($problem);

		if (!empty($data)) {
			$this->set($data);
			$result = $this->save(null, true);
			if ($result) {
				$this->data = Set::merge($problem, $result);
				return true;
			} else {
				$this->data = Set::merge($problem, $result);
			}
		} else {
			$this->data = $problem;
		}
	}

/**
 * Returns the record of a Problem.
 *
 * @param string $id, problem id
 * @return array
 */
	public function view($id = null) {
		$binded = $this->_bindAssociatedModels();
		$problem = $this->find('first', array(
			'contain' => $binded,
			'conditions' => array(
				"{$this->alias}.id" => $id)));

		if (empty($problem)) {
			throw new OutOfBoundsException(__d('problems', 'Invalid Problem'));
		}

		return $problem;
	}

/**
 * Validates the deletion
 *
 * @param string $id, problem id
 * @param string $userId, user id
 * @param array $data, controller post data usually $this->request->data
 * @return boolean True on success
 */
	public function validateAndDelete($id = null, $userId = null, $data = array()) {
		$conditions = array(
			$this->alias . '.id' => $id,
			$this->alias . '.user_id' => $userId);
		$problem = $this->find('first', compact('conditions')); 

		if (empty($problem)) {
			throw new OutOfBoundsException(__d('problems', 'Invalid Problem'));
		}

		$this->data['problem'] = $problem;
		if (!empty($data)) {
			$data[$this->alias]['id'] = $id;
			$tmp = $this->validate;
			$this->validate = array(
				'id' => array('rule' => 'notEmpty'),
				'confirm' => array('rule' => '[1]'));

			$this->set($data);
			if ($this->validates()) {
				if ($this->delete($data[$this->alias]['id'])) {
					return true;
				}
			}
			$this->validate = $tmp;
			throw new Exception(__d('problems', 'You need to confirm to delete this Problem'));
		}
	}

/**
 * Marks a problem as accepted
 *
 * @param string $id the problem identifier
 * @param bolean $accept wheter accept or unaccept the problem report
 * @return mixed edited problem or boolean false
 */
	public function accept($id, $accept = true) {
		$data = array($this->alias => array('accepted' => $accept));
		return (bool) $this->edit($id, false, $data);
	}

/**
 * Marks a problem as accepted
 *
 * @param string $id the problem identifier
 * @param bolean $accept wheter accept or unaccept the problem report
 * @return mixed edited problem or boolean false
 */
	public function acceptAll($model, $foreignKey, $accept = true) {
		$model = Inflector::camelize($model);
		$actualModel = Configure::read('Problems.Models.' . $model);		
		if (!ClassRegistry::isKeySet($model)) {
			$actualModel = ClassRegistry::init($actualModel);
		} else {
			$actualModel= ClassRegistry::getObject($model);
		}
		
		if (!$actualModel->Behaviors->enabled('Reportable')) {
			throw new OutOfBoundsException(__d('problems', 'Invalid object type for problem report'));
		}

		$sample = $this->find('first', array(
			'recursive' => -1,
			'conditions' => array(
				$this->alias . '.accepted' => null,
				$this->alias . '.model' => $model,
				$this->alias . '.foreign_key' => $foreignKey)));

		if (!empty($sample)) {
			//Triggering the behavior (un)acceptReport method on just one report should have the same effect: the record it's problematic
			$action = ($accept) ? 'acceptReport' : 'unAcceptReport';
			$actualModel->{$action}($sample[$this->alias]['id']);
		}

		$this->recursive = -1;
		return $this->updateAll(array('accepted' => $this->getDataSource()->value($accept, 'boolean')),
			array($this->alias . '.model' => $model, $this->alias . '.foreign_key' => $foreignKey));
	}

/**
 * Checks that the submitted type is valid
 *
 * @param array $check array containing the type to chek
 * @return boolean whether the type is valid or not
 */
	public function validType($check) {
		$type = current($check);
		return isset($this->types[$type]);
	}

/**
 * Custom find method to return all reported objects along with totals for each report type
 *
 * @param string $state
 * @param array $query
 * @param array $results
 * @return mixed array for $state before containning the query, results when state is after
 */
	public function _findTotals($state, $query, $results = array()) {
		if ($state == 'before') {
			if (!empty($query['operation']) && $query['operation'] === 'count') {
				unset($query['limit']);
				$query['recursive'] = -1;
				$query['fields'] = array('COUNT(DISTINCT model, foreign_key) AS count');
				return $query;
			}
			$query['fields'] = array('model', 'foreign_key');
			$this->virtualFields['total_reports'] = "COUNT({$this->alias}.foreign_key)";
			$query['fields'][] = 'total_reports';
			$query['group'] = array($this->alias . '.model', $this->alias . '.foreign_key');

			$tableName = $this->tablePrefix . $this->table;
			foreach ($this->types as $type => $description) {
				$this->virtualFields[$type . '_total'] = 'SELECT COUNT(*) FROM ' . $tableName . " WHERE type = '{$type}' and foreign_key = {$this->alias}.foreign_key";
				$query['fields'][] = $type . '_total';
			}
			$models = Configure::read('Problems.Models');

			foreach ((array)$models as $alias => $model) {
				$assoc = ClassRegistry::init($model);
				if ($assoc->Behaviors->enabled('Reportable')) {
					$query['contain'][$assoc->alias] = array('fields' => '*');
				}
			}
			$query['conditions'][$this->alias . '.accepted'] = null;
			return $query;
		}

		$this->virtualFields = array();
		if (isset($query['operation']) && $query['operation'] == 'count') {
			return $this->_findCount('after', $query, $results);
		}
		return $results;
	}

/**
 * After Find callback, creates a new entri in results named "object_title" containing the value of the display field
 * for the associated model to this problem
 *
 * @param array $results
 * @param boolean $primary
 * @return $results modified with the "object_title" key for each record
 */
	function afterFind($results, $primary = false) {
		if (empty($results[0][$this->alias]['model'])) {
			return $results;
		}
		foreach ($results as &$result) {
			if (empty($result[$this->alias]['model'])) {
				continue;
			}
			$model = $result[$this->alias]['model'];
			if (!empty($result[$model][$this->{$model}->displayField])) {
				$result[$this->alias]['object_title'] = $result[$model][$this->{$model}->displayField];
			} else {
				$result[$this->alias]['object_title'] = __d('problems', 'Unknown');
			}
		}
		return $results;
	}

/**
 * Binds models that are declared in the config for this plugin under the key "Problems.Model"
 * Binding is actually done in the ReportableBehavior, so models not having this behavior won't be binded
 *
 * @return array Binded models
 */
	protected function _bindAssociatedModels() {
		$binded = array();
		$models = Configure::read('Problems.Models');
		foreach ((array)$models as $alias => $model) {
			$assoc = ClassRegistry::init($model);
			if (is_a($assoc, 'Model')) {
				$binded[] = $alias;
			}
		}
		return $binded;
	}
}
