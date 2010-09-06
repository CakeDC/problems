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
 * @package   plugins.problems.tests.cases.behaviors
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::import('Behavior', 'Problems.Reportable');

class ProblematicArticle extends Model {
	public $name = 'ProblematicArticle';
	public $alias = 'ProblematicArticle';
	public $useTable = 'problematic_articles';
	public $actsAs = array('Problems.Reportable' => array(
		'userClass' => 'ProblemTestUser',
		'problemTypes' => array('spam' => 'Spam', 'stolen' => 'Stolen Content')
	));

	public function afterReport($id, $originalData, $savedData) {
		$this->mockAfterReport = compact('id', 'originalData', 'savedData');
	}

	public function afterAcceptReport($reportId, $data) {
		$this->mockAfterAcceptReport = compact('reportId', 'data');
	}
}

class ProblemTestUser extends Model {
	public $name = 'User';
	public $useTable = 'users';
}

class ReportableTest extends CakeTestCase {

/**
 * Holds the instance of the model
 *
 * @var mixed
 * @access public
 */
	public $Article = null;

/**
 * Fixtures
 */
	public $fixtures = array(
		'plugin.problems.problematic_article',
		'plugin.problems.problem',
		'core.user');

/**
 * Method executed before each test
 *
 * @access public
 */
	public function startTest() {
		Configure::write('Problems.Models', array('ProblematicArticle' => 'ProblematicArticle'));
		$this->Article = ClassRegistry::init('ProblematicArticle');
	}

/**
 * Method executed after each test
 *
 * @access public
 */
	public function endTest() {
		unset($this->Article);
		ClassRegistry::flush();
	}

/**
 * Tests that the behavior sets up the binds correctly
 *
 * @access public
 */
	public function testSetup() {
		$this->assertTrue(is_a($this->Article->Problem, 'Problem'));
		$this->assertTrue(is_a($this->Article->Problem->ProblematicArticle, 'ProblematicArticle'));
		$settings = $this->Article->Behaviors->Reportable->settings['ProblematicArticle'];
		
		$expected = array('spam' => 'Spam', 'stolen' => 'Stolen Content');
		$this->assertEqual($expected, $settings['problemTypes']);
		$this->assertEqual($expected, $this->Article->Problem->types);
	}

/**
 * Tests report method
 *
 * @access public
 */
	public function testReport() {
		$data = array('Problem' => array('description' => 'My problem'));
		$result = $this->Article->report(1, 1, $data);
		$this->assertTrue($result);

		unset($this->Article->mockAfterReport['savedData']['Problem']['created']);
		unset($this->Article->mockAfterReport['savedData']['Problem']['modified']);

		$expected = array(
			'id' => 1,
			'originalData' => $data,
			'savedData' => array(
				'Problem' => array(
					'model' => 'ProblematicArticle',
					'offensive' => 0,
					'request_to_edit' => 0,
					'user_id' => 1,
					'foreign_key' => 1,
					'description' => 'My problem'
				)
			)
		);

		$this->assertEqual($expected, $this->Article->mockAfterReport);
		$this->assertFalse(empty($this->Article->Problem->id));
	}

/**
 * Tests acceptReport method
 *
 * @access public
 */
	public function testAcceptReport() {
		$data = array('Problem' => array('description' => 'My problem'));
		$result = $this->Article->report(1, 1, $data);
		$id = $this->Article->Problem->id;
		
		$result = $this->Article->acceptReport($id);
		$this->assertTrue($result);
		$expected = array(
			'reportId' => $id,
			'data' => array(
				'Problem' => array(
				  'id' => $id,
			      'model' => 'ProblematicArticle',
			      'foreign_key' => '1',
			      'user_id' => '1',
			      'type' => NULL,
			      'description' => 'My problem',
			      'offensive' => false,
			      'request_to_edit' => false,
			      'accepted' => true)));

		unset($this->Article->mockAfterAcceptReport['data']['Problem']['modified']);
		unset($this->Article->mockAfterAcceptReport['data']['Problem']['created']);
		unset($this->Article->mockAfterAcceptReport['data']['User']);

		unset($this->Article->mockAfterAcceptReport['data']['Problem']['object_title']);
		$this->assertEqual($this->Article->mockAfterAcceptReport, $expected);
	}
}
?>