<?php

/**
 * @copyright  Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
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
     * @testdox  A non-grouped string is converted from its camel case representation
     *
     * @param   string  $expected  The expected value from the method.
     * @param   string  $input     The input value for the method.
     *
     * @dataProvider  seedTestFromCamelCase_nongrouped
     */
    public function testFromCamelCase_nongrouped(string $expected, string $input)
    {
        $this->assertEquals($expected, Normalise::fromCamelcase($input));
    }

    /**
     * @testdox  A grouped string is converted from its camel case representation
     *
     * @param   string        $input     The input value for the method.
     * @param   array|string  $expected  The expected value from the method.
     *
     * @dataProvider  seedTestFromCamelCase
     */
    public function testFromCamelCase_grouped(string $input, $expected)
    {
        $this->assertEquals($expected, Normalise::fromCamelcase($input, true));
    }

    /**
     * @testdox  A string is converted to its camel case representation
     *
     * @param   string  $expected  The expected value from the method.
     * @param   string  $input     The input value for the method.
     *
     * @dataProvider  seedTestToCamelCase
     */
    public function testToCamelCase(string $expected, string $input)
    {
        $this->assertEquals($expected, Normalise::toCamelcase($input));
    }

    /**
     * @testdox  A string is converted to its dash separated representation
     *
     * @param   string  $expected  The expected value from the method.
     * @param   string  $input     The input value for the method.
     *
     * @dataProvider  seedTestToDashSeparated
     */
    public function testToDashSeparated(string $expected, string $input)
    {
        $this->assertEquals($expected, Normalise::toDashSeparated($input));
    }

    /**
     * @testdox  A string is converted to its space separated representation
     *
     * @param   string  $expected  The expected value from the method.
     * @param   string  $input     The input value for the method.
     *
     * @dataProvider  seedTestToSpaceSeparated
     */
    public function testToSpaceSeparated(string $expected, string $input)
    {
        $this->assertEquals($expected, Normalise::toSpaceSeparated($input));
    }

    /**
     * @testdox  A string is converted to its underscore separated representation
     *
     * @param   string  $expected  The expected value from the method.
     * @param   string  $input     The input value for the method.
     *
     * @dataProvider  seedTestToUnderscoreSeparated
     */
    public function testToUnderscoreSeparated(string $expected, string $input)
    {
        $this->assertEquals($expected, Normalise::toUnderscoreSeparated($input));
    }

    /**
     * @testdox  A string is converted to a value suitable for use as a variable name
     *
     * @param   string  $expected  The expected value from the method.
     * @param   string  $input     The input value for the method.
     *
     * @dataProvider  seedTestToVariable
     */
    public function testToVariable(string $expected, string $input)
    {
        $this->assertEquals($expected, Normalise::toVariable($input));
    }

    /**
     * @testdox  A string is converted to a value suitable for use as a key name
     *
     * @param   string  $expected  The expected value from the method.
     * @param   string  $input     The input value for the method.
     *
     * @dataProvider  seedTestToKey
     */
    public function testToKey(string $expected, string $input)
    {
        $this->assertEquals($expected, Normalise::toKey($input));
    }
}
