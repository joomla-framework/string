<?php
/**
 * @copyright  Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\String\Tests;

use Joomla\String\Normalise;
use PHPUnit\Framework\TestCase;

/**
 * NormaliseTest
 *
 * @since  1.0
 */
class NormaliseTest extends TestCase
{
	/**
	 * Method to seed data to testFromCamelCase.
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestFromCamelCase(): \Generator
	{
		// Note: string, expected
		yield ['FooBarABCDef', ['Foo', 'Bar', 'ABC', 'Def']];
		yield ['JFooBar', ['J', 'Foo', 'Bar']];
		yield ['J001FooBar002', ['J001', 'Foo', 'Bar002']];
		yield ['abcDef', ['abc', 'Def']];
		yield ['abc_defGhi_Jkl', ['abc_def', 'Ghi_Jkl']];
		yield ['ThisIsA_NASAAstronaut', ['This', 'Is', 'A_NASA', 'Astronaut']];
		yield ['JohnFitzgerald_Kennedy', ['John', 'Fitzgerald_Kennedy']];
	}

	/**
	 * Method to seed data to testFromCamelCase.
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestFromCamelCase_nongrouped(): \Generator
	{
		yield ['Foo Bar', 'FooBar'];
		yield ['foo Bar', 'fooBar'];
		yield ['Foobar', 'Foobar'];
		yield ['foobar', 'foobar'];
	}

	/**
	 * Method to seed data to testToCamelCase.
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestToCamelCase(): \Generator
	{
		yield ['FooBar', 'Foo Bar'];
		yield ['FooBar', 'Foo-Bar'];
		yield ['FooBar', 'Foo_Bar'];
		yield ['FooBar', 'foo bar'];
		yield ['FooBar', 'foo-bar'];
		yield ['FooBar', 'foo_bar'];
	}

	/**
	 * Method to seed data to testToDashSeparated.
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestToDashSeparated(): \Generator
	{
		yield ['Foo-Bar', 'Foo Bar'];
		yield ['Foo-Bar', 'Foo-Bar'];
		yield ['Foo-Bar', 'Foo_Bar'];
		yield ['foo-bar', 'foo bar'];
		yield ['foo-bar', 'foo-bar'];
		yield ['foo-bar', 'foo_bar'];
		yield ['foo-bar', 'foo   bar'];
		yield ['foo-bar', 'foo---bar'];
		yield ['foo-bar', 'foo___bar'];
	}

	/**
	 * Method to seed data to testToSpaceSeparated.
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestToSpaceSeparated(): \Generator
	{
		yield ['Foo Bar', 'Foo Bar'];
		yield ['Foo Bar', 'Foo-Bar'];
		yield ['Foo Bar', 'Foo_Bar'];
		yield ['foo bar', 'foo bar'];
		yield ['foo bar', 'foo-bar'];
		yield ['foo bar', 'foo_bar'];
		yield ['foo bar', 'foo   bar'];
		yield ['foo bar', 'foo---bar'];
		yield ['foo bar', 'foo___bar'];
	}

	/**
	 * Method to seed data to testToUnderscoreSeparated.
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestToUnderscoreSeparated(): \Generator
	{
		yield ['Foo_Bar', 'Foo Bar'];
		yield ['Foo_Bar', 'Foo-Bar'];
		yield ['Foo_Bar', 'Foo_Bar'];
		yield ['foo_bar', 'foo bar'];
		yield ['foo_bar', 'foo-bar'];
		yield ['foo_bar', 'foo_bar'];
		yield ['foo_bar', 'foo   bar'];
		yield ['foo_bar', 'foo---bar'];
		yield ['foo_bar', 'foo___bar'];
	}

	/**
	 * Method to seed data to testToVariable.
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestToVariable(): \Generator
	{
		yield ['myFooBar', 'My Foo Bar'];
		yield ['myFooBar', 'My Foo-Bar'];
		yield ['myFooBar', 'My Foo_Bar'];
		yield ['myFooBar', 'my foo bar'];
		yield ['myFooBar', 'my foo-bar'];
		yield ['myFooBar', 'my foo_bar'];
		yield ['abc3def4', '1abc3def4'];
	}

	/**
	 * Method to seed data to testToKey.
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestToKey(): \Generator
	{
		yield ['foo_bar', 'Foo Bar'];
		yield ['foo_bar', 'Foo-Bar'];
		yield ['foo_bar', 'Foo_Bar'];
		yield ['foo_bar', 'foo bar'];
		yield ['foo_bar', 'foo-bar'];
		yield ['foo_bar', 'foo_bar'];
	}

	/**
	 * Method to test Normalise::fromCamelCase(string, false).
	 *
	 * @param   string  $expected  The expected value from the method.
	 * @param   string  $input     The input value for the method.
	 *
	 * @return  void
	 *
	 * @covers        Joomla\String\Normalise::fromCamelcase
	 * @dataProvider  seedTestFromCamelCase_nongrouped
	 * @since         1.0
	 */
	public function testFromCamelCase_nongrouped($expected, $input)
	{
		$this->assertEquals($expected, Normalise::fromCamelcase($input));
	}

	/**
	 * Method to test Normalise::fromCamelCase(string, true).
	 *
	 * @param   string  $input     The input value for the method.
	 * @param   string  $expected  The expected value from the method.
	 *
	 * @return  void
	 *
	 * @covers        Joomla\String\Normalise::fromCamelcase
	 * @dataProvider  seedTestFromCamelCase
	 * @since         1.0
	 */
	public function testFromCamelCase_grouped($input, $expected)
	{
		$this->assertEquals($expected, Normalise::fromCamelcase($input, true));
	}

	/**
	 * Method to test Normalise::toCamelCase().
	 *
	 * @param   string  $expected  The expected value from the method.
	 * @param   string  $input     The input value for the method.
	 *
	 * @return  void
	 *
	 * @covers        Joomla\String\Normalise::toCamelcase
	 * @dataProvider  seedTestToCamelCase
	 * @since         1.0
	 */
	public function testToCamelCase($expected, $input)
	{
		$this->assertEquals($expected, Normalise::toCamelcase($input));
	}

	/**
	 * Method to test Normalise::toDashSeparated().
	 *
	 * @param   string  $expected  The expected value from the method.
	 * @param   string  $input     The input value for the method.
	 *
	 * @return  void
	 *
	 * @covers        Joomla\String\Normalise::toDashSeparated
	 * @dataProvider  seedTestToDashSeparated
	 * @since         1.0
	 */
	public function testToDashSeparated($expected, $input)
	{
		$this->assertEquals($expected, Normalise::toDashSeparated($input));
	}

	/**
	 * Method to test Normalise::toSpaceSeparated().
	 *
	 * @param   string  $expected  The expected value from the method.
	 * @param   string  $input     The input value for the method.
	 *
	 * @return  void
	 *
	 * @covers        Joomla\String\Normalise::toSpaceSeparated
	 * @dataProvider  seedTestToSpaceSeparated
	 * @since         1.0
	 */
	public function testToSpaceSeparated($expected, $input)
	{
		$this->assertEquals($expected, Normalise::toSpaceSeparated($input));
	}

	/**
	 * Method to test Normalise::toUnderscoreSeparated().
	 *
	 * @param   string  $expected  The expected value from the method.
	 * @param   string  $input     The input value for the method.
	 *
	 * @return  void
	 *
	 * @covers        Joomla\String\Normalise::toUnderscoreSeparated
	 * @dataProvider  seedTestToUnderscoreSeparated
	 * @since         1.0
	 */
	public function testToUnderscoreSeparated($expected, $input)
	{
		$this->assertEquals($expected, Normalise::toUnderscoreSeparated($input));
	}

	/**
	 * Method to test Normalise::toVariable().
	 *
	 * @param   string  $expected  The expected value from the method.
	 * @param   string  $input     The input value for the method.
	 *
	 * @return  void
	 *
	 * @covers        Joomla\String\Normalise::toVariable
	 * @dataProvider  seedTestToVariable
	 * @since         1.0
	 */
	public function testToVariable($expected, $input)
	{
		$this->assertEquals($expected, Normalise::toVariable($input));
	}

	/**
	 * Method to test Normalise::toKey().
	 *
	 * @param   string  $expected  The expected value from the method.
	 * @param   string  $input     The input value for the method.
	 *
	 * @return  void
	 *
	 * @covers        Joomla\String\Normalise::toKey
	 * @dataProvider  seedTestToKey
	 * @since         1.0
	 */
	public function testToKey($expected, $input)
	{
		$this->assertEquals($expected, Normalise::toKey($input));
	}
}
