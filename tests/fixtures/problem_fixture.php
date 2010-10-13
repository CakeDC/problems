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
 * @package   plugins.problems.tests.fixtures
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class ProblemFixture extends CakeTestFixture {
/**
 * Name
 *
 * @var string
 * @access public
 */
	public $name = 'Problem';

/**
 * Fields
 *
 * @var array
 * @access public
 */
	public $fields = array(
		'id' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
		'model' => array('type'=>'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'foreign_key' => array('type'=>'string', 'null' => true, 'default' => NULL, 'length' => 36),
		'user_id' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 36),
		'type' => array('type'=>'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'offensive' => array('type' => 'boolean', 'null' => false, 'default' => 0),
		'request_to_edit' => array('type' => 'boolean', 'null' => false, 'default' => 0),
		'accepted' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type'=>'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
	);

/**
 * Records
 *
 * @var array
 * @access public
 */
	public $records = array(
		array(
			'id' => '1',
			'model' => 'ProblematicArticle',
			'foreign_key' => '1',
			'user_id' => '1',
			'type' => 'spam',
			'description' => 'Article is problematic.',
			'offensive' => 0,
			'request_to_edit' => 0,
			'accepted' => null,
			'created' => '2010-03-10 12:04:12',
			'modified' => '2010-03-10 12:04:12'
		),
		array(
			'id' => '2',
			'model' => 'ProblematicArticle',
			'foreign_key' => 'article-1',
			'user_id' => '2',
			'type' => 'stolen',
			'description' => 'Article is problematic.',
			'offensive' => 0,
			'request_to_edit' => 1,
			'accepted' => null,
			'created' => '2010-03-10 12:04:12',
			'modified' => '2010-03-10 12:04:12'
		),
	);

}
?>