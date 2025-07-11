<?php

/**
 * @copyright  Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\String\Tests;

use Joomla\String\Normalise;
use PHPUnit\Framework\Attributes\DataProvider;
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
     * @return  array
     *
     * @since   1.0
     */
    public static function seedTestFromCamelCase(): array
    {
        return [
            // Note: string, expected
            ['FooBarABCDef', ['Foo', 'Bar', 'ABC', 'Def']],
            ['JFooBar', ['J', 'Foo', 'Bar']],
            ['J001FooBar002', ['J001', 'Foo', 'Bar002']],
            ['abcDef', ['abc', 'Def']],
            ['abc_defGhi_Jkl', ['abc_def', 'Ghi_Jkl']],
            ['ThisIsA_NASAAstronaut', ['This', 'Is', 'A_NASA', 'Astronaut']],
            ['JohnFitzgerald_Kennedy', ['John', 'Fitzgerald_Kennedy']],
        ];
    }

    /**
     * Method to seed data to testFromCamelCase.
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function seedTestFromCamelCase_nongrouped(): array
    {
        return [
            ['Foo Bar', 'FooBar'],
            ['foo Bar', 'fooBar'],
            ['Foobar', 'Foobar'],
            ['foobar', 'foobar'],
        ];
    }

    /**
     * Method to seed data to testToCamelCase.
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function seedTestToCamelCase(): array
    {
        return [
            ['FooBar', 'Foo Bar'],
            ['FooBar', 'Foo-Bar'],
            ['FooBar', 'Foo_Bar'],
            ['FooBar', 'foo bar'],
            ['FooBar', 'foo-bar'],
            ['FooBar', 'foo_bar'],
        ];
    }

    /**
     * Method to seed data to testToDashSeparated.
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function seedTestToDashSeparated(): array
    {
        return [
            ['Foo-Bar', 'Foo Bar'],
            ['Foo-Bar', 'Foo-Bar'],
            ['Foo-Bar', 'Foo_Bar'],
            ['foo-bar', 'foo bar'],
            ['foo-bar', 'foo-bar'],
            ['foo-bar', 'foo_bar'],
            ['foo-bar', 'foo   bar'],
            ['foo-bar', 'foo---bar'],
            ['foo-bar', 'foo___bar'],
        ];
    }

    /**
     * Method to seed data to testToSpaceSeparated.
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function seedTestToSpaceSeparated(): array
    {
        return [
            ['Foo Bar', 'Foo Bar'],
            ['Foo Bar', 'Foo-Bar'],
            ['Foo Bar', 'Foo_Bar'],
            ['foo bar', 'foo bar'],
            ['foo bar', 'foo-bar'],
            ['foo bar', 'foo_bar'],
            ['foo bar', 'foo   bar'],
            ['foo bar', 'foo---bar'],
            ['foo bar', 'foo___bar'],
        ];
    }

    /**
     * Method to seed data to testToUnderscoreSeparated.
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function seedTestToUnderscoreSeparated(): array
    {
        return [
            ['Foo_Bar', 'Foo Bar'],
            ['Foo_Bar', 'Foo-Bar'],
            ['Foo_Bar', 'Foo_Bar'],
            ['foo_bar', 'foo bar'],
            ['foo_bar', 'foo-bar'],
            ['foo_bar', 'foo_bar'],
            ['foo_bar', 'foo   bar'],
            ['foo_bar', 'foo---bar'],
            ['foo_bar', 'foo___bar'],
        ];
    }

    /**
     * Method to seed data to testToVariable.
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function seedTestToVariable(): array
    {
        return [
            ['myFooBar', 'My Foo Bar'],
            ['myFooBar', 'My Foo-Bar'],
            ['myFooBar', 'My Foo_Bar'],
            ['myFooBar', 'my foo bar'],
            ['myFooBar', 'my foo-bar'],
            ['myFooBar', 'my foo_bar'],
            ['abc3def4', '1abc3def4'],
        ];
    }

    /**
     * Method to seed data to testToKey.
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function seedTestToKey(): array
    {
        return [
            ['foo_bar', 'Foo Bar'],
            ['foo_bar', 'Foo-Bar'],
            ['foo_bar', 'Foo_Bar'],
            ['foo_bar', 'foo bar'],
            ['foo_bar', 'foo-bar'],
            ['foo_bar', 'foo_bar'],
        ];
    }

    /**
     * @testdox  A non-grouped string is converted from its camel case representation
     *
     * @param   string  $expected  The expected value from the method.
     * @param   string  $input     The input value for the method.
     */
    #[DataProvider('seedTestFromCamelCase_nongrouped')]
    public function testFromCamelCase_nongrouped(string $expected, string $input)
    {
        $this->assertEquals($expected, Normalise::fromCamelcase($input));
    }

    /**
     * @testdox  A grouped string is converted from its camel case representation
     *
     * @param   string        $input     The input value for the method.
     * @param   array|string  $expected  The expected value from the method.
     */
    #[DataProvider('seedTestFromCamelCase')]
    public function testFromCamelCase_grouped(string $input, $expected)
    {
        $this->assertEquals($expected, Normalise::fromCamelcase($input, true));
    }

    /**
     * @testdox  A string is converted to its camel case representation
     *
     * @param   string  $expected  The expected value from the method.
     * @param   string  $input     The input value for the method.
     */
    #[DataProvider('seedTestToCamelCase')]
    public function testToCamelCase(string $expected, string $input)
    {
        $this->assertEquals($expected, Normalise::toCamelcase($input));
    }

    /**
     * @testdox  A string is converted to its dash separated representation
     *
     * @param   string  $expected  The expected value from the method.
     * @param   string  $input     The input value for the method.
     */
    #[DataProvider('seedTestToDashSeparated')]
    public function testToDashSeparated(string $expected, string $input)
    {
        $this->assertEquals($expected, Normalise::toDashSeparated($input));
    }

    /**
     * @testdox  A string is converted to its space separated representation
     *
     * @param   string  $expected  The expected value from the method.
     * @param   string  $input     The input value for the method.
     */
    #[DataProvider('seedTestToSpaceSeparated')]
    public function testToSpaceSeparated(string $expected, string $input)
    {
        $this->assertEquals($expected, Normalise::toSpaceSeparated($input));
    }

    /**
     * @testdox  A string is converted to its underscore separated representation
     *
     * @param   string  $expected  The expected value from the method.
     * @param   string  $input     The input value for the method.
     */
    #[DataProvider('seedTestToUnderscoreSeparated')]
    public function testToUnderscoreSeparated(string $expected, string $input)
    {
        $this->assertEquals($expected, Normalise::toUnderscoreSeparated($input));
    }

    /**
     * @testdox  A string is converted to a value suitable for use as a variable name
     *
     * @param   string  $expected  The expected value from the method.
     * @param   string  $input     The input value for the method.
     */
    #[DataProvider('seedTestToVariable')]
    public function testToVariable(string $expected, string $input)
    {
        $this->assertEquals($expected, Normalise::toVariable($input));
    }

    /**
     * @testdox  A string is converted to a value suitable for use as a key name
     *
     * @param   string  $expected  The expected value from the method.
     * @param   string  $input     The input value for the method.
     */
    #[DataProvider('seedTestToKey')]
    public function testToKey(string $expected, string $input)
    {
        $this->assertEquals($expected, Normalise::toKey($input));
    }
}
