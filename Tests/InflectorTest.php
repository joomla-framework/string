<?php
/**
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\String\Tests;

use Doctrine\Common\Inflector\Inflector as DoctrineInflector;
use Joomla\String\Inflector;
use Joomla\Test\TestHelper;
use PHPUnit\Framework\TestCase;

/**
 * Test for the Inflector class.
 *
 * @link   http://en.wikipedia.org/wiki/English_plural
 * @since  1.0
 */
class InflectorTest extends TestCase
{
	/**
	 * @var    Inflector
	 * @since  1.0
	 */
	protected $inflector;

	/**
	 * Method to seed data to testIsCountable.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function seedIsCountable()
	{
		return array(
			array('id', true),
			array('title', false),
		);
	}

	/**
	 * Method to seed data to testToPlural.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function seedSinglePlural()
	{
		return array(
			// Regular plurals
			array('bus', 'buses'),
			array('notify', 'notifies'),
			array('click', 'clicks'),

			// Almost regular plurals.
			array('photo', 'photos'),
			array('zero', 'zeros'),

			// Irregular identicals
			array('salmon', 'salmon'),

			// Irregular plurals
			array('ox', 'oxen'),
			array('quiz', 'quizzes'),
			array('status', 'statuses'),
			array('matrix', 'matrices'),
			array('index', 'indices'),
			array('vertex', 'vertices'),
			array('hive', 'hives'),

			// Ablaut plurals
			array('foot', 'feet'),
			array('goose', 'geese'),
			array('louse', 'lice'),
			array('man', 'men'),
			array('mouse', 'mice'),
			array('tooth', 'teeth'),
			array('woman', 'women'),
		);
	}

	/**
	 * Sets up the fixture.
	 *
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->inflector = Inflector::getInstance(true);
		DoctrineInflector::reset();
	}

	/**
	 * Tears down the fixture, for example, close a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function tearDown()
	{
		DoctrineInflector::reset();

		parent::tearDown();
	}

	/**
	 * Method to test Inflector::addRule().
	 *
	 * @return  void
	 *
	 * @covers  Joomla\String\Inflector::addRule
	 * @expectedException  InvalidArgumentException
	 * @since   1.0
	 */
	public function testAddRuleException()
	{
		TestHelper::invoke($this->inflector, 'addRule', new \stdClass, 'singular');
	}

	/**
	 * Method to test Inflector::addCountableRule().
	 *
	 * @return  void
	 *
	 * @covers  Joomla\String\Inflector::addCountableRule
	 * @since   1.0
	 */
	public function testAddCountableRule()
	{
		// Add string.
		$this->inflector->addCountableRule('foo');

		$rules = TestHelper::getValue($this->inflector, 'rules');

		$this->assertContains(
			'foo',
			$rules['countable'],
			'Checks a countable rule was added.'
		);

		// Add array.
		$this->inflector->addCountableRule(array('goo', 'car'));

		$rules = TestHelper::getValue($this->inflector, 'rules');

		$this->assertContains(
			'car',
			$rules['countable'],
			'Checks a countable rule was added by array.'
		);
	}

	/**
	 * Method to test Inflector::addWord().
	 *
	 * @return  void
	 *
	 * @covers  Joomla\String\Inflector::addWord
	 * @since   1.2.0
	 */
	public function testAddWordWithoutPlural()
	{
		$this->assertSame(
			$this->inflector,
			$this->inflector->addWord('foo')
		);

		$plural = TestHelper::getValue(DoctrineInflector::class, 'plural');

		$this->assertTrue(
			in_array('foo', $plural['uninflected'])
		);

		$singular = TestHelper::getValue(DoctrineInflector::class, 'singular');

		$this->assertTrue(
			in_array('foo', $singular['uninflected'])
		);
	}

	/**
	 * Method to test Inflector::addWord().
	 *
	 * @return  void
	 *
	 * @covers  Joomla\String\Inflector::addWord
	 * @since   1.2.0
	 */
	public function testAddWordWithPlural()
	{
		$this->assertEquals(
			$this->inflector,
			$this->inflector->addWord('bar', 'foo')
		);

		$plural = TestHelper::getValue(DoctrineInflector::class, 'plural');

		$this->assertArrayHasKey(
			'foo',
			$plural['irregular']
		);

		$singular = TestHelper::getValue(DoctrineInflector::class, 'singular');

		$this->assertArrayHasKey(
			'bar',
			$singular['irregular']
		);
	}

	/**
	 * Method to test Inflector::addPluraliseRule().
	 *
	 * @return  void
	 *
	 * @covers  Joomla\String\Inflector::addPluraliseRule
	 * @since   1.0
	 */
	public function testAddPluraliseRule()
	{
		$this->assertSame(
			$this->inflector->addPluraliseRule(['/^(custom)$/i' => '\1izables']),
			$this->inflector,
			'Checks chaining.'
		);

		$plural = TestHelper::getValue(DoctrineInflector::class, 'plural');

		$this->assertArrayHasKey(
			'/^(custom)$/i',
			$plural['rules'],
			'Checks a pluralisation rule was added.'
		);
	}

	/**
	 * Method to test Inflector::addSingulariseRule().
	 *
	 * @return  void
	 *
	 * @covers  Joomla\String\Inflector::addSingulariseRule
	 * @since   1.0
	 */
	public function testAddSingulariseRule()
	{
		$this->assertSame(
			$this->inflector->addSingulariseRule(['/^(inflec|contribu)tors$/i' => '\1ta']),
			$this->inflector,
			'Checks chaining.'
		);

		$singular = TestHelper::getValue(DoctrineInflector::class, 'singular');

		$this->assertArrayHasKey(
			'/^(inflec|contribu)tors$/i',
			$singular['rules'],
			'Checks a singularisation rule was added.'
		);
	}

	/**
	 * Method to test Inflector::getInstance().
	 *
	 * @return  void
	 *
	 * @covers  Joomla\String\Inflector::getInstance
	 * @since   1.0
	 */
	public function testGetInstance()
	{
		$this->assertInstanceOf(
			'Joomla\\String\\Inflector',
			Inflector::getInstance(),
			'Check getInstance returns the right class.'
		);

		// Inject an instance an test.
		TestHelper::setValue($this->inflector, 'instance', new \stdClass);

		$this->assertThat(
			Inflector::getInstance(),
			$this->equalTo(new \stdClass),
			'Checks singleton instance is returned.'
		);

		$this->assertInstanceOf(
			'Joomla\\String\\Inflector',
			Inflector::getInstance(true),
			'Check getInstance a fresh object with true argument even though the instance is set to something else.'
		);
	}

	/**
	 * Method to test Inflector::isCountable().
	 *
	 * @param   string   $input     A string.
	 * @param   boolean  $expected  The expected result of the function call.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\String\Inflector::isCountable
	 * @dataProvider  seedIsCountable
	 * @since   1.0
	 */
	public function testIsCountable($input, $expected)
	{
		$this->assertThat(
			$this->inflector->isCountable($input),
			$this->equalTo($expected)
		);
	}

	/**
	 * Method to test Inflector::isPlural().
	 *
	 * @param   string  $singular  The singular form of a word.
	 * @param   string  $plural    The plural form of a word.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\String\Inflector::isPlural
	 * @dataProvider  seedSinglePlural
	 * @since   1.0
	 */
	public function testIsPlural($singular, $plural)
	{
		$this->assertTrue(
			$this->inflector->isPlural($plural),
			"'$plural' should be reported as plural"
		);

		if ($singular !== $plural)
		{
			$this->assertFalse(
				$this->inflector->isPlural($singular),
				"'$singular' should not be reported as a plural form in comparison to '$plural'"
			);
		}
	}

	/**
	 * Method to test Inflector::isSingular().
	 *
	 * @param   string  $singular  The singular form of a word.
	 * @param   string  $plural    The plural form of a word.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\String\Inflector::isSingular
	 * @dataProvider  seedSinglePlural
	 * @since   1.0
	 */
	public function testIsSingular($singular, $plural)
	{
		$this->assertTrue(
			$this->inflector->isSingular($singular),
			"'$singular' should be reported as singular"
		);

		if ($singular !== $plural)
		{
			$this->assertFalse(
				$this->inflector->isSingular($plural),
				"'$plural' should not be reported as a singular form in comparison to '$singular'"
			);
		}
	}

	/**
	 * Method to test Inflector::toPlural().
	 *
	 * @param   string  $singular  The singular form of a word.
	 * @param   string  $plural    The plural form of a word.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\String\Inflector::toPlural
	 * @dataProvider  seedSinglePlural
	 * @since   1.0
	 */
	public function testToPlural($singular, $plural)
	{
		$this->assertSame(
			$plural,
			$this->inflector->toPlural($singular),
			"'$plural' should be the plural form of '$singular'"
		);
	}

	/**
	 * Method to test Inflector::toPlural().
	 *
	 * @return  void
	 *
	 * @covers  Joomla\String\Inflector::toPlural
	 * @since   1.2.0
	 */
	public function testToPluralAlreadyPlural()
	{
		$this->assertSame(
			'buses',
			$this->inflector->toPlural('buses'),
			"'buses' should not be pluralised'"
		);
	}

	/**
	 * Method to test Inflector::toSingular().
	 *
	 * @param   string  $singular  The singular form of a word.
	 * @param   string  $plural    The plural form of a word.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\String\Inflector::toSingular
	 * @dataProvider  seedSinglePlural
	 * @since   1.0
	 */
	public function testToSingular($singular, $plural)
	{
		$this->assertSame(
			$singular,
			$this->inflector->toSingular($plural),
			"'$singular' should be the singular form of '$plural'"
		);
	}

	/**
	 * Method to test Inflector::toSingular().
	 *
	 * @return  void
	 *
	 * @covers  Joomla\String\Inflector::toSingular
	 * @since   1.2.0
	 */
	public function testToSingularAlreadySingular()
	{
		$this->assertSame(
			'bus',
			$this->inflector->toSingular('bus'),
			"'bus' should not be singularised'"
		);
	}
}
