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
 * Reportable Behavior
 *
 * @package problems
 * @subpackage problems.models.behaviors
 */
class ReportableBehavior extends ModelBehavior {

/**
 * Settings array
 *
 * @var array
 */
	public $settings = array();

/**
 * Default settings
 *
 * modelClass		- must be set in the case of a plugin model to make the behavior work with plugin models like 'Plugin.Model'
 * problemClass		- name of the problem model
 * userClass		- name of the user model
 *
 * @var array
 */
	protected $_defaults = array(
		'modelClass' => null,
		'userClass' => 'Users.User',
		'problemClass' => 'Problems.Problem',
		'foreignKey' => 'foreign_key',
		'problemTypes' => array()
	);

/**
 * Setup
 *
 * @param AppModel $Model
 * @param array $settings
 * @return void
 */
	public function setup(Model $Model, array $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = $this->_defaults;
		}
		$this->settings[$Model->alias] = Set::merge($this->settings[$Model->alias], $settings);
		if (empty($this->settings[$Model->alias]['modelClass'])) {
			$this->settings[$Model->alias]['modelClass'] = $Model->name;
		}

		$Model->bindModel(
			array('hasMany' => array(
				'Problem' => array(
					'className' => $this->settings[$Model->alias]['problemClass'],
					'foreignKey' => $this->settings[$Model->alias]['foreignKey'],
					'conditions' => array('Problem.model' => $Model->alias),
					'unique' => true,
					'fields' => '',
					'dependent' => true))), false);

		$Model->Problem->bindModel(array(
			'belongsTo' => array(
				$Model->alias => array(
					'className' => $this->settings[$Model->alias]['modelClass'],
					'foreignKey' => 'foreign_key'),
				'User' => array(
					'className' => $this->settings[$Model->alias]['userClass'],
					'foreignKey' => 'user_id'))), false);

		if (!empty($this->settings[$Model->alias]['problemTypes'])) {
			$Model->Problem->types = $this->settings[$Model->alias]['problemTypes'];
		}
		$Model->Problem->modelTypes[] = $Model->alias;
	}

/**
 * Saves problem data ssociated to a model record
 *
 * @param AppModel $Model
 * @param string $id identifier of reported model
 * @param string $userId identifier of reporting user
 * @param array $data problem data
 * @return void
 */
	public function report(Model $Model, $id, $userId, $data) {
		$result = $Model->Problem->add($Model->alias, $id, $userId, $data);
		if ($result && method_exists($Model, 'afterReport')) {
			$Model->afterReport($id, $data, $Model->Problem->data);
		}
		return $result;
	}

/**
 * Marks a problem report as accepted
 *
 * @param AppModel $Model
 * @param string $reportId
 * @return mixed edited report or false on failure
 */
	public function acceptReport(Model $Model, $reportId) {
		$result = $Model->Problem->accept($reportId);
		if ($result && method_exists($Model, 'afterAcceptReport')) {
			$Model->afterAcceptReport($reportId, $Model->Problem->data);
		}
		return $result;
	}

/**
 * Marks a problem report as un-accepted
 *
 * @param AppModel $Model
 * @param string $reportId 
 * @return mixed edited report or false on failure
 */
	public function unAcceptReport(Model $Model, $reportId) {
		$result = $Model->Problem->accept($reportId, false);
		if ($result && method_exists($Model, 'afterUnAcceptReport')) {
			$Model->afterUnAcceptReport($reportId);
		}
		return $result;
	}

/**
 * Generates an array of params to be used in Router::url() to get a link to the reported object view page
 *
 * @param AppModel $Model
 * @param string $id the reported object identifier
 * @return array
 */
	public function reportedObjectUrl(Model $Model, $id) {
		$modelName = Configure::read('Problems.Models.' . $Model->alias);

		if ($Model->hasField('slug')) {
			$id = $Model->field('slug', array('id' => $id));
		}

		return array(
			'admin' => false,
			'controller' => Inflector::tableize($Model->name),
			'action' => 'view',
			$id,
			'plugin' => current(pluginSplit($modelName))
		);
	}
}
