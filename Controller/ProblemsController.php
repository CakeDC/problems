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
 * Problems Controller
 *
 * @package problems
 * @subpackage problems.controllers
 */
class ProblemsController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Problems';

/**
 * Components
 *
 * @var array
 */
	public $components = array('Utils.Referer');

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array('Html', 'Form', 'Time', 'Text');
	
/**
 * Before filter callback
 * Restricts the add and edit actions to logged in users only
 * 
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->deny('add', 'edit');
	}

/**
 * Add for problem.
 *
 * @param string $foreignKey, Book id 
 */
	public function add($objectType, $foreignKey) {
		try {
			$model = Inflector::classify($objectType);

			if (!ClassRegistry::isKeySet($model)) {
				$this->{$model} = ClassRegistry::init(Configure::read('Problems.Models.' . $model));
			} else {
				$this->{$model} = ClassRegistry::getObject($model);
			}
			
			if (get_class($this->{$model}) === 'AppModel') {
				throw new Exception(__d('problems', 'Could not save the Problem of unallowed type.'));
			}
			
			$result = $this->{$model}->report($foreignKey, $this->Auth->user('id'), $this->request->data);
			if ($result === true) {
				$this->Session->setFlash(__d('problems', 'The problem has been saved'));
				$this->Referer->redirect('/');
			}
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
		} catch (LogicException $e) {
			$this->Session->setFlash($e->getMessage());
			$this->Referer->redirect('/');
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect('/');
		}

		$types = $this->{$model}->Problem->types;
		$this->set(compact('foreignKey', 'types', 'objectType')); 
	}

/**
 * Edit for problem.
 *
 * @param string $id, problem id 
 */
	public function edit($id = null) {	
		try {
			$problem = $this->Problem->view($id);
			$model = Inflector::classify($problem['Problem']['model']);
			$this->{$model} = ClassRegistry::init(Configure::read('Problems.Models.' . $model));
			$result = $this->{$model}->Problem->edit($id, $this->Auth->user('id'), $this->request->data);
			if ($result === true) {
				$foreignKey = $this->Problem->data['Problem']['foreign_key'];
				$this->Session->setFlash(__d('problems', 'Problem saved'));
				$this->Referer->redirect('/');				
			} else {
				$this->request->data = $this->Problem->data;
				$foreignKey = $this->request->data['Problem']['foreign_key'];
				$model = $this->request->data['Problem']['model'];
			}
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect('/');
		}
		$this->set('type', strtolower($model));
		$this->set(compact('foreignKey')); 
		$this->set('types', $this->{$model}->Problem->types); 
	}

/**
 * Admin index for problem.
 *
 * @param string $objectType
 */
	public function admin_index($objectType = null) {
		$this->paginate = array('totals', 'order' => 'Problem.model DESC');

		if (!empty($objectType)) {
			$this->paginate['conditions']['Problem.model'] = Inflector::classify($objectType);
		}

		$this->set('problems', $this->paginate());
		$this->set('objectType', $objectType);
		$this->set('reportTypes', $this->Problem->types);
	}

/**
 * Admin view for problem.
 *
 * @param string $id, problem id 
 */
	public function admin_view($id = null) {
		try {
			$problem = $this->Problem->view($id);
			$foreignKey = $problem['Problem']['foreign_key'];
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect('/');
		}
		$this->set(compact('problem', 'foreignKey'));
	}

/**
 * Admin edit for problem.
 *
 * @param string $id, problem id 
 */
	public function admin_edit($id = null) {
		try {
			$problem = $this->Problem->view($id);
			$model = Inflector::classify($problem['Problem']['model']);
			$this->{$model} = ClassRegistry::init(Configure::read('Problems.Models.' . $model));
			if ($this->{$model}->Problem->edit($id, $this->Auth->user('id'), $this->request->data)) {
				$this->Session->setFlash(__d('problems', 'Problem saved'));
			}
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect('/');
		}

		$this->request->data = $this->Problem->data;
		$this->set('problem', $this->Problem->data);
		$this->set('type', strtolower($model));
		$this->set('types', $this->{$model}->Problem->types); 
	}

/**
 * Admin delete for problem.
 *
 * @param string $id, problem id 
 */	
	public function admin_delete($id = null) {
		try {
			$problem = $this->Problem->view($id);
			$foreignKey = $problem['Problem']['foreign_key'];
			$model = Inflector::underscore($problem['Problem']['model']);
			$this->set(compact('foreignKey')); 
			$result = $this->Problem->validateAndDelete($id, $this->Auth->user('id'), $this->request->data);
			if ($result === true) {
				$this->Session->setFlash(__d('problems', 'Problem deleted'));
				$this->redirect(array('action' => 'index', $model));
			}
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect('/');
		}

		if (!empty($this->Problem->data['problem'])) {
			$this->set('problem', $this->Problem->data['problem']);
		}
	}

/**
 * Lists all problems for a object type record
 *
 * @param string $objectType the name of the object with problems associated
 * @param string $foreignKey
 */
	public function admin_review($objectType, $foreignKey) {
		$objectType = Inflector::camelize($objectType);
		if (!$model = Configure::read('Problems.Models.' . $objectType)) {
			$this->redirect($this->referer('/'));
		}
		$this->{$model} = ClassRegistry::init($model);
		$this->paginate['conditions'] = array(
			'Problem.model' => $objectType,
			'Problem.foreign_key' => $foreignKey
		);

		$this->set('problems', $this->paginate());
		$this->set('reportTypes', $this->Problem->types);
	}

/**
 * Accepts a problem report as valid
 *
 * @param string $id the Problem identifier
 */
	public function admin_accept($id) {
		try {
			$model = $this->Problem->field('model', array('Problem.id' => $id));
			if (!$model = Configure::read('Problems.Models.' . $model)) {
				$this->redirect($this->referer('/'));
			}
			$result = ClassRegistry::init($model)->acceptReport($id);
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect('/');
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect($this->referer('/'));
		}
		$this->Session->setFlash(__d('problems', 'Problem report was accepted'));
		$this->redirect($this->referer('/'));
	}

/**
 * Marks a problem report as not valid
 *
 * @param string $id the Problem identifier
 */
	public function admin_unaccept($id) {
		try {
			$result = $this->Problem->accept($id, false);
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect('/');
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect($this->referer('/'));
		}
		$this->Session->setFlash(__d('problems', 'Problem report was unaccepted'));
		$this->redirect($this->referer('/'));
	}

/**
 * Accepts all problem reports for a object type and foreignKey as valid
 *
 * @param string $objectType the name of the object with problems associated
 * @param string $foreignKey
 */
	public function admin_accept_all($objectType, $foreignKey) {
		try {
			$result = $this->Problem->acceptAll($objectType, $foreignKey, true);
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect('/');
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect($this->referer('/'));
		}

		$this->Session->setFlash(__d('problems', 'Problem reports were accepted'));
		$this->redirect($this->referer('/'));
	}

/**
 * Marks all problem reports for a object type and foreignKey as not valid
 *
 * @param string $objectType the name of the object with problems associated
 * @param string $foreignKey
 */
	public function admin_unaccept_all($objectType, $foreignKey) {
		try {
			$result = $this->Problem->acceptAll($objectType, $foreignKey, false);
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect('/');
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect($this->referer('/'));
		}

		$this->Session->setFlash(__d('problems', 'Problem reports were unaccepted'));
		$this->redirect($this->referer('/'));
	}

/**
 * Redirects to the page permitting to view the reported object
 *
 * @param string $id, problem id 
 */
	public function admin_view_object($objectType, $foreignKey) {
		$objectType = Inflector::camelize($objectType);
		if (!$model = Configure::read('Problems.Models.' . $objectType)) {
			$this->redirect($this->referer('/'));
		}
		$this->redirect(ClassRegistry::init($model)->reportedObjectUrl($foreignKey));
	}
}
