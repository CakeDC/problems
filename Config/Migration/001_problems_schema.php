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
 * @package   plugins.problems.config.migrations
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class M4c31fc230f2040c1821506fa0e8f3d6d extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Initial Problems plugin schema';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'problems' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
					'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
					'foreign_key' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'key' => 'index'),
					'user_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'index'),
					'type' => array('type'=>'string', 'null' => true, 'default' => NULL, 'length' => 50),
					'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'offensive' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'request_to_edit' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'accepted' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'USER_INDEX' => array('column' => 'user_id', 'unique' => 0),
						'FK_INDEX' => array('column' => 'foreign_key', 'unique' => 0),
					)
				),
			)
		),
		'down' => array(
			'drop_table'=> array(
				'problems'
			) 
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}
}
?>