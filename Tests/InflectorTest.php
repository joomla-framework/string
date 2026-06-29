<?php

/**
 * @copyright  Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\String\Tests;

use Joomla\String\Inflector;
use Joomla\Test\TestHelper;
use PHPUnit\Framework\Attributes\DataProvider;
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
     * @var  Inflector
     */
    protected $inflector;

    /**
     * Method to seed data to testIsCountable.
     *
     * @return  array
     */
    public static function seedIsCountable(): array
    {
        return [
            ['id', true],
            ['title', false],
        ];
    }

    /**
     * Method to seed data to testToPlural.
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function seedSinglePlural(): array
    {
        return [
            // Regular plurals
            ['glass', 'glasses'],
            ['notify', 'notifies'],
            ['click', 'clicks'],

            // Almost regular plurals.
            ['photo', 'photos'],
            ['zero', 'zeros'],

            // Irregular identicals
            ['salmon', 'salmon'],

            // Irregular plurals
            ['ox', 'oxen'],
            ['quiz', 'quizzes'],
            ['status', 'statuses'],
            ['matrix', 'matrices'],
            ['index', 'indices'],
            ['vertex', 'vertices'],
            ['hive', 'hives'],

            // Ablaut plurals
            ['foot', 'feet'],
            ['louse', 'lice'],
            ['man', 'men'],
            ['mouse', 'mice'],
            ['tooth', 'teeth'],
            ['woman', 'women'],
        ];
    }

    /**
     * Sets up the fixture.
     *
     * This method is called before a test is executed.
     *
     * @return  void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->inflector = new Inflector();
    }

    /**
     * @testdox  A rule cannot be added to the inflector if it is of an unsupported type
     */
    public function testAddRuleException()
    {
        $this->expectException(\InvalidArgumentException::class);

        /** @noinspection PhpParamsInspection */
        TestHelper::invoke($this->inflector, 'addRule', new \stdClass(), 'singular');
    }

    /**
     * @testdox  A countable rule can be added to the inflector
     */
    public function testAddCountableRule()
    {
        // Add string.
        $this->inflector->addCountableRule('foo');

        $countable = TestHelper::getValue($this->inflector, 'countable');

        $this->assertContains(
            'foo',
            $countable['rules'],
            'Checks a countable rule was added.'
        );

        // Add array.
        $this->inflector->addCountableRule(['goo', 'car']);

        $countable = TestHelper::getValue($this->inflector, 'countable');

        $this->assertContains(
            'car',
            $countable['rules'],
            'Checks a countable rule was added by array.'
        );
    }

    /**
     * @testdox  A string is checked to determine if it a countable word
     *
     * @param   string   $input     A string.
     * @param   boolean  $expected  The expected result of the function call.
     */
    #[DataProvider('seedIsCountable')]
    public function testIsCountable(string $input, bool $expected)
    {
        $this->assertEquals(
            $expected,
            $this->inflector->isCountable($input)
        );
    }

    /**
     * @testdox  A string is checked to determine if it is in plural form
     *
     * @param   string  $singular  The singular form of a word.
     * @param   string  $plural    The plural form of a word.
     */
    #[DataProvider('seedSinglePlural')]
    public function testIsPlural(string $singular, string $plural)
    {
        $this->assertTrue(
            $this->inflector->isPlural($plural),
            "'$plural' should be reported as plural"
        );

        if ($singular !== $plural) {
            $this->assertFalse(
                $this->inflector->isPlural($singular),
                "'$singular' should not be reported as a plural form in comparison to '$plural'"
            );
        }
    }

    /**
     * @testdox  A string is checked to determine if it is in singular form
     *
     * @param   string  $singular  The singular form of a word.
     * @param   string  $plural    The plural form of a word.
     */
    #[DataProvider('seedSinglePlural')]
    public function testIsSingular(string $singular, string $plural)
    {
        $this->assertTrue(
            $this->inflector->isSingular($singular),
            "'$singular' should be reported as singular"
        );

        if ($singular !== $plural) {
            $this->assertFalse(
                $this->inflector->isSingular($plural),
                "'$plural' should not be reported as a singular form in comparison to '$singular'"
            );
        }
    }

    /**
     * @testdox  A string is converted to its plural form
     *
     * @param   string  $singular  The singular form of a word.
     * @param   string  $plural    The plural form of a word.
     */
    #[DataProvider('seedSinglePlural')]
    public function testPluralize(string $singular, string $plural)
    {
        $this->assertSame(
            $plural,
            $this->inflector->pluralize($singular),
            "'$plural' should be the plural form of '$singular'"
        );
    }

    /**
     * @testdox  A string that is already plural is returned in the same form
     */
    public function testPluralizeAlreadyPlural()
    {
        $this->assertSame(
            'glasses',
            $this->inflector->pluralize('glasses'),
            "'glasses' should not be pluralised'"
        );
    }

    /**
     * @testdox  A string is converted to its singular form
     *
     * @param   string  $singular  The singular form of a word.
     * @param   string  $plural    The plural form of a word.
     */
    #[DataProvider('seedSinglePlural')]
    public function testSingularize(string $singular, string $plural)
    {
        $this->assertSame(
            $singular,
            $this->inflector->singularize($plural),
            "'$singular' should be the singular form of '$plural'"
        );
    }

    /**
     * @testdox  A string that is already singular is returned in the same form
     */
    public function testSingularizeAlreadySingular()
    {
        $this->assertSame(
            'glass',
            $this->inflector->singularize('glass'),
            "'glass' should not be singularised'"
        );
    }
}
