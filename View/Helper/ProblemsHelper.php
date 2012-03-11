<?php
/**
 * Problems CakePHP Plugin
 *
 * Copyright 2010, Cake Development Corporation
 *                 1785 E. Sahara Avenue, Suite 490-423
 *                 Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright 2010, Cake Development Corporation
 * @link      http://github.com/CakeDC/Comments
 * @package   plugins.problems.views.helpers
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * A helper to assist in the creation of links or widgets to report problems on a model record
 *
 * @package		problems
 * @subpackage	views.helpers
 */

class ProblemsHelper extends AppHelper {

	public $helpers = array('Html');

/**
 * Returns a link to the form for adding a problem for a model record
 *
 * @param string $model
 * @param string $id
 * @param string $title
 * @return string link to the action for adding a new problem for a model record
 * @access public
 */
	public function link($model, $id, $title = null, $options = array()) {
		return $this->Html->link($title,
			array(
			'plugin' => 'problems',
			'controller' => 'problems',
			'action' => 'add', $model, $id),
			$options);
	}

}
?>