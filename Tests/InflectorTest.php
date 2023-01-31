<?php

/**
 * @copyright  Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
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
     * @var  Inflector
     */
    protected $inflector;

    /**
     * Method to seed data to testIsCountable.
     *
     * @return  \Generator
     */
    public function seedIsCountable(): \Generator
    {
        yield ['id', true];
        yield ['title', false];
    }

    /**
     * Method to seed data to testToPlural.
     *
     * @return  \Generator
     *
     * @since   1.0
     */
    public function seedSinglePlural(): \Generator
    {
        // Regular plurals
        yield ['bus', 'buses'];
        yield ['notify', 'notifies'];
        yield ['click', 'clicks'];

        // Almost regular plurals.
        yield ['photo', 'photos'];
        yield ['zero', 'zeros'];

        // Irregular identicals
        yield ['salmon', 'salmon'];

        // Irregular plurals
        yield ['ox', 'oxen'];
        yield ['quiz', 'quizzes'];
        yield ['status', 'statuses'];
        yield ['matrix', 'matrices'];
        yield ['index', 'indices'];
        yield ['vertex', 'vertices'];
        yield ['hive', 'hives'];

        // Ablaut plurals
        yield ['foot', 'feet'];
        yield ['louse', 'lice'];
        yield ['man', 'men'];
        yield ['mouse', 'mice'];
        yield ['tooth', 'teeth'];
        yield ['woman', 'women'];
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

        $this->inflector = Inflector::getInstance(true);
        DoctrineInflector::reset();
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown(): void
    {
        DoctrineInflector::reset();

        parent::tearDown();
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
     * @testdox  A word can be added to the inflector without a plural form
     */
    public function testAddWordWithoutPlural()
    {
        if (!$this->checkInflectorImplementation($this->inflector)) {
            $this->markTestSkipped('This test depends on the library\'s implementation');
        }

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
     * @testdox  A word can be added to the inflector with a plural form
     */
    public function testAddWordWithPlural()
    {
        if (!$this->checkInflectorImplementation($this->inflector)) {
            $this->markTestSkipped('This test depends on the library\'s implementation');
        }

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
     * @testdox  A pluralisation rule can be added to the inflector
     */
    public function testAddPluraliseRule()
    {
        if (!$this->checkInflectorImplementation($this->inflector)) {
            $this->markTestSkipped('This test depends on the library\'s implementation');
        }

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
     * @testdox  A singularisation rule can be added to the inflector
     */
    public function testAddSingulariseRule()
    {
        if (!$this->checkInflectorImplementation($this->inflector)) {
            $this->markTestSkipped('This test depends on the library\'s implementation');
        }

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
     * @testdox  The singleton instance of the inflector can be retrieved
     */
    public function testGetInstance()
    {
        $this->assertInstanceOf(
            Inflector::class,
            Inflector::getInstance(),
            'Check getInstance returns the right class.'
        );

        $this->assertNotSame(
            Inflector::getInstance(),
            Inflector::getInstance(true),
            'getInstance with the new flag should not return the singleton instance'
        );
    }

    /**
     * @testdox  A string is checked to determine if it a countable word
     *
     * @param   string   $input     A string.
     * @param   boolean  $expected  The expected result of the function call.
     *
     * @dataProvider  seedIsCountable
     */
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
     *
     * @dataProvider  seedSinglePlural
     */
    public function testIsPlural(string $singular, string $plural)
    {
        if ($singular === 'bus' && !$this->checkInflectorImplementation($this->inflector)) {
            $this->markTestSkipped('"bus/buses" is not known to the new implementation');
        }

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
     *
     * @dataProvider  seedSinglePlural
     */
    public function testIsSingular(string $singular, string $plural)
    {
        if ($singular === 'bus' && !$this->checkInflectorImplementation($this->inflector)) {
            $this->markTestSkipped('"bus/buses" is not known to the new implementation');
        }

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
     *
     * @dataProvider  seedSinglePlural
     */
    public function testToPlural(string $singular, string $plural)
    {
        $this->assertSame(
            $plural,
            $this->inflector->toPlural($singular),
            "'$plural' should be the plural form of '$singular'"
        );
    }

    /**
     * @testdox  A string that is already plural is returned in the same form
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
     * @testdox  A string is converted to its singular form
     *
     * @param   string  $singular  The singular form of a word.
     * @param   string  $plural    The plural form of a word.
     *
     * @dataProvider  seedSinglePlural
     */
    public function testToSingular(string $singular, string $plural)
    {
        $this->assertSame(
            $singular,
            $this->inflector->toSingular($plural),
            "'$singular' should be the singular form of '$plural'"
        );
    }

    /**
     * @testdox  A string that is already singular is returned in the same form
     */
    public function testToSingularAlreadySingular()
    {
        if (!$this->checkInflectorImplementation($this->inflector)) {
            $this->markTestSkipped('"bus/buses" is not known to the new implementation');
        }

        $this->assertSame(
            'bus',
            $this->inflector->toSingular('bus'),
            "'bus' should not be singularised'"
        );
    }

    private function checkInflectorImplementation(DoctrineInflector $inflector): bool
    {
        $reflectionClass = new \ReflectionClass($inflector);

        return $reflectionClass->hasProperty('plural');
    }
}
