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

class ProblematicArticleFixture extends CakeTestFixture {
/**
 * Name
 *
 * @var string
 * @access public
 */
	public $name = 'ProblematicArticle';

/**
 * Fields
 *
 * @var array
 * @access public
 */
	public $fields = array(
		'id' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
		'title' => array('type'=>'string', 'null' => true, 'default' => NULL, 'length' => 200),
		'body' => array('type'=>'string', 'null' => true, 'default' => NULL),
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
			'id' => 'article-1',
			'title' => 'Article 1 title',
			'body' => 'Article 1 Boby',
			'created' => '2010-03-10 12:04:12',
			'modified' => '2010-03-10 12:04:12'
		),
		array(
			'id' => 'article-2',
			'title' => 'Article 2 title',
			'body' => 'Article 2 Boby',
			'created' => '2010-03-10 12:04:12',
			'modified' => '2010-03-10 12:04:12'
		),
	);

}
?>