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
 * @link      http://github.com/CakeDC/problems
 * @package   plugins.problems.config.schema
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class ProblemsSchema extends CakeSchema {
	var $name = 'Problems';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $problems = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20),
		'foreign_key' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'key' => 'index'),
		'user_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'index'),
		'type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'offensive' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'request_to_edit' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'accepted' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'USER_INDEX' => array('column' => 'user_id', 'unique' => 0), 'FK_INDEX' => array('column' => 'foreign_key', 'unique' => 0)),
	);
}
?>