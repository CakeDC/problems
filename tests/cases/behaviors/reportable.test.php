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

class ProblematicArticle extends CakeTestModel {
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

	public function afterUnAcceptReport($reportId) {
		$this->mockAfterUnAcceptReport = compact('reportId');
	}
}

class ProblemTestUser extends CakeTestModel {
	public $name = 'User';
	public $useTable = 'users';
}

class ReportableTest extends CakeTestCase {

/**
 * Holds the instance of the model
 *
 * @var mixed
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
 */
	public function setUp() {
		Configure::write('Problems.Models', array('ProblematicArticle' => 'ProblematicArticle'));
		$this->Article = ClassRegistry::init('ProblematicArticle');
	}

/**
 * Method executed after each test
 *
 */
	public function tearDown() {
		unset($this->Article);
		ClassRegistry::flush();
	}

/**
 * Tests that the behavior sets up the binds correctly
 *
 */
	public function testSetup() {
		$this->assertTrue(is_a($this->Article->Problem, 'Problem'));
		$this->assertTrue(is_a($this->Article->Problem->ProblematicArticle, 'ProblematicArticle'));
		$settings = $this->Article->Behaviors->Reportable->settings['ProblematicArticle'];
		
		$expected = array('spam' => 'Spam', 'stolen' => 'Stolen Content');
		$this->assertEquals($expected, $settings['problemTypes']);
		$this->assertEquals($expected, $this->Article->Problem->types);
	}

/**
 * Tests report method
 *
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

		$this->assertEquals($expected, $this->Article->mockAfterReport);
		$this->assertFalse(empty($this->Article->Problem->id));
	}

/**
 * Tests acceptReport method
 *
 */
	public function testAcceptReport() {
		$data = array('Problem' => array('description' => 'My problem'));
		$result = $this->Article->report(1, 1, $data);
		$id = $this->Article->Problem->id;
		
		$result = $this->Article->acceptReport($id);
		$this->assertTrue($result);
		$expected = array(
			'Problem' => array(
				'id' => $id,
				'model' => 'ProblematicArticle',
				'foreign_key' => '1',
				'user_id' => '1',
				'type' => NULL,
				'description' => 'My problem',
				'offensive' => 0,
				'request_to_edit' => 0,
				'accepted' => 1
			)
		);

		unset($this->Article->mockAfterAcceptReport['data']['Problem']['modified']);
		unset($this->Article->mockAfterAcceptReport['data']['Problem']['created']);
		unset($this->Article->mockAfterAcceptReport['data']['Problem']['object_title']);

		$this->assertEquals($this->Article->mockAfterAcceptReport['reportId'], $id);
		$this->assertEquals($expected['Problem']['id'], $this->Article->mockAfterAcceptReport['data']['Problem']['id']);
		$this->assertEquals($expected['Problem']['model'], $this->Article->mockAfterAcceptReport['data']['Problem']['model']);
		$this->assertEquals($expected['Problem']['foreign_key'], $this->Article->mockAfterAcceptReport['data']['Problem']['foreign_key']);
		$this->assertEquals($expected['Problem']['user_id'], $this->Article->mockAfterAcceptReport['data']['Problem']['user_id']);
		$this->assertNull($expected['Problem']['type']);
		$this->assertEquals($expected['Problem']['description'], $this->Article->mockAfterAcceptReport['data']['Problem']['description']);
		$this->assertEquals($expected['Problem']['offensive'], 0);
		$this->assertEquals($expected['Problem']['request_to_edit'], 0);
		$this->assertEquals($expected['Problem']['accepted'], 1);
	}
	
/**
 * Tests unAcceptReport method
 *
 */
	public function testUnAcceptReport() {
		$data = array('Problem' => array('description' => 'My problem'));
		$result = $this->Article->report(1, 1, $data);
		$id = $this->Article->Problem->id;

		$result = $this->Article->unAcceptReport($id);
		$this->assertTrue($result);
		$expected = array('reportId' => $id);
		$this->assertEquals($this->Article->mockAfterUnAcceptReport, $expected);
	}

}
