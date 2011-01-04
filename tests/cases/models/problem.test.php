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
 * @package   plugins.problems.test.cases.models
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::import('Behavior', 'Problem.Reportable');


class ProblematicArticle extends CakeTestModel {
	public $name = 'ProblematicArticle';
	public $actsAs = array('Problems.Reportable' => array('userClass' => 'ProblemModelUser'));
}

class ProblemModelUser extends CakeTestModel {
	public $name = 'User';
	public $useTable = 'users';
}
/* Problem Test cases generated on: 2010-03-10 12:03:14 : 1268219054*/

class ProblemTestCase extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 * @access public
 */
	public $fixtures = array(
		'plugin.problems.problematic_article',
		'plugin.problems.problem',
		'plugin.users.user',
		'plugin.users.detail'
	);


/**
 * Setup method
 *
 * @return void
 * @access public
 */
	public function setUp() {
		Configure::write('Problems.Models', array('ProblematicArticle' => 'ProblematicArticle'));
		$this->Problem = ClassRegistry::init('Problems.Problem');
		$this->Problem->modelTypes = array('ProblematicArticle');
		$fixture = new ProblemFixture();
		ClassRegistry::init('ProblematicArticle');
		$this->record = array('Problem' => $fixture->records[0]);
	}

/**
 * tearDown method
 *
 * @return void
 * @access public
 */
	public function tearDown() {
		unset($this->Problem);
		ClassRegistry::flush();
	}

/**
 * Test adding a Problem
 *
 * @expectedException OutOfBoundsException
 * @access public
 */
	public function testAddBadData() {
		$userId = 3;
		$data = $this->record;
		unset($data['Problem']['id']);
		$result = $this->Problem->add('ProblematicArticle', 'article-1', $userId, $data);
		$this->assertTrue($result);
		$this->Problem->recursive = 1;
		$data = $this->Problem->read();

		$this->assertEqual($data['Problem']['model'], 'ProblematicArticle');
		$this->assertEqual($data['Problem']['foreign_key'], 'article-1');
		$data = $this->record;
		unset($data['Problem']['id']);
		$data['Problem']['description'] = null;
		$result = $this->Problem->add('ProblematicArticle', 'article-1', 4, $data);
	}

/**
 * Tests that adding a duplicate report throws an exception
 *
 * @expectedException LogicException
 */
	public function testAddDuplicatedReport() {
		$data = $this->record;
		$userId = 3;
		unset($data['Problem']['id']);
		$result = $this->Problem->add('ProblematicArticle', 'article-1', $userId, $data);
		$this->Problem->add('ProblematicArticle', 'article-1', $userId, $data);
	}

/**
 * Test editing a Problem
 *
 * @expectedException OutOfBoundsException
 */
	public function testEdit() {
		$userId = '1';
		$this->Problem->edit(1, $userId, null);
		$this->assertEqual($this->Problem->data['Problem']['id'], 1);

		// put invalidated data here
		$data = $this->record;
		$data['Problem']['description'] = null;

		$result = $this->Problem->edit(1, $userId, $data);
		$this->assertNull($result);
		$data = $this->record;

		$result = $this->Problem->edit(1, $userId, $data);
		$this->assertTrue($result);

		$result = $this->Problem->read(null, 1);
		$this->Problem->edit('wrong_id', $userId, $data);
	}

/**
 * Test viewing a single  Problem
 *
 * @expectedException OutOfBoundsException
 */
	public function testViewNotFound() {
		$result = $this->Problem->view(2);
		$this->assertTrue(isset($result['Problem']));
		$this->assertEqual($result['Problem']['id'], 2);
		$result = $this->Problem->view('wrong_id');
	}

/**
 * Test ValidateAndDelete method for an invalid Problem
 *
 * @expectedException OutOfBoundsException
 */
	public function testValidateAndDeleteInvalid() {
		$userId = '1';
		$postData = array();
		$this->Problem->validateAndDelete('invalidProblemId', $userId, $postData);
	}

/**
 * Test validateAndDelete with no confirmation sent by the user
 *
 * @expectedException Exception
 */
	public function testValidateAndDeleteNoConfirmation() {
		$userId = '1';
		$postData = array(
			'Problem' => array(
				'confirm' => 0
			)
		);
		$result = $this->Problem->validateAndDelete(1, $userId, $postData);
	}

/**
 * Test validateAndDelete with confirmation
 *
 */
	public function testValidateAndDelete() {
		$userId = '1';
		$postData = array(
			'Problem' => array(
				'confirm' => 1
			)
		);
		$result = $this->Problem->validateAndDelete(1, $userId, $postData);
		$this->assertTrue($result);
	}
	

/**
 * Tests accept method
 *
 * @expectedException OutOfBoundsException
 */
	public function testAccept() {
		$result = $this->Problem->field('accepted', array('id' => 1));
		$this->assertNull($result);
		$this->assertTrue($this->Problem->accept(1));
		$result = $this->Problem->field('accepted', array('id' => 1));
		$this->assertTrue((bool)$result);
		
		$this->assertTrue($this->Problem->accept(1, false));
		$result = $this->Problem->field('accepted', array('id' => 1));
		$this->assertFalse((bool)$result);
		$this->Problem->accept('WROG_ID');
	}

/**
 * Tests acceptAll method
 *
 */
	public function testAcceptAll() {
		$data = $this->record;
		unset($data['Problem']['id']);
		$this->Problem->add('ProblematicArticle', 'article-1', 3, $data);
		$data['Problem']['type'] = 'other';
		$this->Problem->add('ProblematicArticle', 'article-1', 4, $data);

		$result = $this->Problem->acceptAll('ProblematicArticle', 'article-1');
		$this->assertTrue($result);
		$result = $this->Problem->find('all',
			array('fields' => 'accepted', 'conditions' => array('foreign_key' => 'article-1')));
		$this->assertEqual(3, Set::apply('/Problem/accepted', $result, 'array_sum'));

		$result = $this->Problem->acceptAll('ProblematicArticle', 'article-1', false);
		$this->assertTrue($result);
		$result = $this->Problem->find('all',
			array('fields' => 'accepted', 'conditions' => array('foreign_key' => 'article-1')));
		$this->assertEqual(0, Set::apply('/Problem/accepted', $result, 'array_sum'));
	}

/**
 * Tests find('totals') method
 *
 */
	public function testFindTotals() {
		$data = $this->record;
		unset($data['Problem']['id']);
		$this->Problem->add('ProblematicArticle', 'article-1', 3, $data);
		$data['Problem']['type'] = 'other';
		$this->Problem->add('ProblematicArticle', 'article-1', 4, $data);
		$data['Problem']['type'] = 'spam';
		$this->Problem->add('ProblematicArticle', 'article-1', 5, $data);

		$result = $this->Problem->find('totals');
		$this->assertEqual($result[0]['Problem']['foreign_key'], 1);
		$this->assertEqual($result[0]['Problem']['model'], 'ProblematicArticle');
		$this->assertEqual($result[0]['Problem']['total_reports'], 1);
		$this->assertEqual($result[0]['Problem']['spam_total'], 1);

		$this->assertEqual($result[1]['Problem']['foreign_key'], 'article-1');
		$this->assertEqual($result[1]['Problem']['model'], 'ProblematicArticle');
		$this->assertEqual($result[1]['Problem']['total_reports'], 4);
		$this->assertEqual($result[1]['Problem']['spam_total'], 2);
		$this->assertEqual($result[1]['Problem']['other_total'], 1);
		$this->assertEqual($result[1]['Problem']['stolen_total'], 1);
	}
}
?>