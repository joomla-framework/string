<?php

/**
 * @copyright  Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\String\Tests;

use Joomla\String\StringHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Test class for StringHelper.
 */
class StringHelperTest extends TestCase
{
    /**
     * Data provider for testIncrement
     *
     * @return  array
     */
    public static function seedTestIncrement(): array
    {
        return [
            // Note: string, style, number, expected
            'First default increment' => ['title', null, 0, 'title (2)'],
            'Second default increment' => ['title(2)', null, 0, 'title(3)'],
            'First dash increment' => ['title', 'dash', 0, 'title-2'],
            'Second dash increment' => ['title-2', 'dash', 0, 'title-3'],
            'Set default increment' => ['title', null, 4, 'title (4)'],
            'Unknown style fallback to default' => ['title', 'foo', 0, 'title (2)'],
        ];
    }

    /**
     * Data provider for testIs_ascii
     *
     * @return  array
     */
    public static function seedTestIs_ascii(): array
    {
        return [
            ['ascii', true],
            ['1024', true],
            ['#$#@$%', true],
            ['áÑ', false],
            ['ÿ©', false],
            ['¡¾', false],
            ['÷™', false],
        ];
    }

    /**
     * Data provider for testStrpos
     *
     * @return  array
     */
    public static function seedTestStrpos(): array
    {
        return [
            [3, 'missing', 'sing', 0],
            [false, 'missing', 'sting', 0],
            [4, 'missing', 'ing', 0],
            [10, ' объектов на карте с', 'на карте', 0],
            [0, 'на карте с', 'на карте', 0],
            [false, 'на карте с', 'на каррте', 0],
            [false, 'на карте с', 'на карте', 2],
            [3, 'missing', 'sing', false],
        ];
    }

    /**
     * Data provider for testStrrpos
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function seedTestStrrpos(): array
    {
        return [
            [3, 'missing', 'sing', 0],
            [false, 'missing', 'sting', 0],
            [4, 'missing', 'ing', 0],
            [10, ' объектов на карте с', 'на карте', 0],
            [0, 'на карте с', 'на карте', 0],
            [false, 'на карте с', 'на каррте', 0],
            [3, 'на карте с', 'карт', 2],
        ];
    }

    /**
     * Data provider for testSubstr
     *
     * @return  array
     */
    public static function seedTestSubstr(): array
    {
        return [
            ['issauga', 'Mississauga', 4, false],
            ['на карте с', ' объектов на карте с', 10, false],
            ['на ка', ' объектов на карте с', 10, 5],
            ['те с', ' объектов на карте с', -4, false],
            [false, ' объектов на карте с', 99, false],
        ];
    }

    /**
     * Data provider for testStrtolower
     *
     * @return  array
     */
    public static function seedTestStrtolower(): array
    {
        return [
            ['Joomla! Rocks', 'joomla! rocks'],
        ];
    }

    /**
     * Data provider for testStrtoupper
     *
     * @return  array
     */
    public static function seedTestStrtoupper(): array
    {
        return [
            ['Joomla! Rocks', 'JOOMLA! ROCKS'],
        ];
    }

    /**
     * Data provider for testStrlen
     *
     * @return  array
     */
    public static function seedTestStrlen(): array
    {
        return [
            ['Joomla! Rocks', 13],
        ];
    }

    /**
     * Data provider for testStr_ireplace
     *
     * @return  array
     */
    public static function seedTestStr_ireplace(): array
    {
        return [
            ['Pig', 'cow', 'the pig jumped', false, 'the cow jumped'],
            ['Pig', 'cow', 'the pig jumped', true, 'the cow jumped'],
            ['Pig', 'cow', 'the pig jumped over the cow', true, 'the cow jumped over the cow'],
            [['PIG', 'JUMPED'], ['cow', 'hopped'], 'the pig jumped over the pig', true, 'the cow hopped over the cow'],
            ['шил', 'биш', 'Би шил идэй чадна', true, 'Би биш идэй чадна'],
            ['/', ':', '/test/slashes/', true, ':test:slashes:'],
        ];
    }

    /**
     * Data provider for testStr_split
     *
     * @return  array
     */
    public static function seedTestStr_split(): array
    {
        return [
            ['string', 1, ['s', 't', 'r', 'i', 'n', 'g']],
            ['string', 2, ['st', 'ri', 'ng']],
            ['волн', 3, ['вол', 'н']],
            ['волн', 1, ['в', 'о', 'л', 'н']],
        ];
    }

    /**
     * Data provider for testStrcasecmp
     *
     * @return  array
     */
    public static function seedTestStrcasecmp(): array
    {
        return [
            ['THIS IS STRING1', 'this is string1', false, 0],
            ['this is string1', 'this is string2', false, -1],
            ['this is string2', 'this is string1', false, 1],
            ['бгдпт', 'бгдпт', false, 0],
            ['àbc', 'abc', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], 1],
            ['àbc', 'bcd', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], -1],
            ['é', 'è', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], -1],
            ['É', 'é', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], 0],
            ['œ', 'p', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], -1],
            ['œ', 'n', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], 1],
        ];
    }

    /**
     * Data provider for testStrcmp
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function seedTestStrcmp(): array
    {
        return [
            ['THIS IS STRING1', 'this is string1', false, -1],
            ['this is string1', 'this is string2', false, -1],
            ['this is string2', 'this is string1', false, 1],
            ['a', 'B', false, 1],
            ['A', 'b', false, -1],
            ['Àbc', 'abc', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], 1],
            ['Àbc', 'bcd', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], -1],
            ['É', 'è', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], -1],
            ['é', 'È', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], -1],
            ['Œ', 'p', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], -1],
            ['Œ', 'n', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], 1],
            ['œ', 'N', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], 1],
            ['œ', 'P', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], -1],
        ];
    }

    /**
     * Data provider for testStrcspn
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function seedTestStrcspn(): array
    {
        return [
            ['subject <a> string <a>', '<>', false, false, 8],
            ['Би шил {123} идэй {456} чадна', '}{', null, false, 7],
            ['Би шил {123} идэй {456} чадна', '}{', 13, 10, 5],
        ];
    }

    /**
     * Data provider for testStristr
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function seedTestStristr(): array
    {
        return [
            ['haystack', 'needle', false],
            ['before match, after match', 'match', 'match, after match'],
            ['Би шил идэй чадна', 'шил', 'шил идэй чадна'],
        ];
    }

    /**
     * Data provider for testStrrev
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function seedTestStrrev(): array
    {
        return [
            ['abc def', 'fed cba'],
            ['Би шил', 'лиш иБ'],
        ];
    }

    /**
     * Data provider for testStrspn
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function seedTestStrspn(): array
    {
        return [
            ['A321 Main Street', '0123456789', 1, 2, 2],
            ['321 Main Street', '0123456789', 0, 2, 2],
            ['A321 Main Street', '0123456789', 0, 10, 0],
            ['321 Main Street', '0123456789', 0, null, 3],
            ['Main Street 321', '0123456789', 0, -3, 0],
            ['321 Main Street', '0123456789', 0, -13, 2],
            ['321 Main Street', '0123456789', 0, -12, 3],
            ['A321 Main Street', '0123456789', 0, null, 0],
            ['A321 Main Street', '0123456789', 1, 10, 3],
            ['A321 Main Street', '0123456789', 1, null, 3],
            ['Би шил идэй чадна', 'Би', 0, null, 2],
            ['чадна Би шил идэй чадна', 'Би', 0, null, 0],
        ];
    }

    /**
     * Data provider for testSubstr_replace
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function seedTestSubstr_replace(): array
    {
        return [
            ['321 Broadway Avenue', '321 Main Street', 'Broadway Avenue', 4, false],
            ['321 Broadway Street', '321 Main Street', 'Broadway', 4, 4],
            ['чадна 我能吞', 'чадна Би шил идэй чадна', '我能吞', 6, false],
            ['чадна 我能吞 шил идэй чадна', 'чадна Би шил идэй чадна', '我能吞', 6, 2],
        ];
    }

    /**
     * Data provider for testLtrim
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function seedTestLtrim(): array
    {
        return [
            ['   abc def', false, 'abc def'],
            ['   abc def', '', '   abc def'],
            [' Би шил', false, 'Би шил'],
            ["\t\n\r\x0BБи шил", false, 'Би шил'],
            ["\x0B\t\n\rБи шил", "\t\n\x0B", "\rБи шил"],
            ["\x09Би шил\x0A", "\x09\x0A", "Би шил\x0A"],
            ['1234abc', '0123456789', 'abc'],
        ];
    }

    /**
     * Data provider for testRtrim
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function seedTestRtrim(): array
    {
        return [
            ['abc def   ', false, 'abc def'],
            ['abc def   ', '', 'abc def   '],
            ['Би шил ', false, 'Би шил'],
            ["Би шил\t\n\r\x0B", false, 'Би шил'],
            ["Би шил\r\x0B\t\n", "\t\n\x0B", "Би шил\r"],
            ["\x09Би шил\x0A", "\x09\x0A", "\x09Би шил"],
            ['1234abc', 'abc', '1234'],
        ];
    }

    /**
     * Data provider for testTrim
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function seedTestTrim(): array
    {
        return [
            ['  abc def   ', false, 'abc def'],
            ['  abc def   ', '', '  abc def   '],
            ['   Би шил ', false, 'Би шил'],
            ["\t\n\r\x0BБи шил\t\n\r\x0B", false, 'Би шил'],
            ["\x0B\t\n\rБи шил\r\x0B\t\n", "\t\n\x0B", "\rБи шил\r"],
            ["\x09Би шил\x0A", "\x09\x0A", "Би шил"],
            ['1234abc56789', '0123456789', 'abc'],
        ];
    }

    /**
     * Data provider for testUcfirst
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function seedTestUcfirst(): array
    {
        return [
            ['george', null, null, 'George'],
            ['мога', null, null, 'Мога'],
            ['ψυχοφθόρα', null, null, 'Ψυχοφθόρα'],
            ['dr jekill and mister hyde', ' ', null, 'Dr Jekill And Mister Hyde'],
            ['dr jekill and mister hyde', ' ', '_', 'Dr_Jekill_And_Mister_Hyde'],
            ['dr jekill and mister hyde', ' ', '', 'DrJekillAndMisterHyde'],
        ];
    }

    /**
     * Data provider for testUcwords
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function seedTestUcwords(): array
    {
        return [
            ['george washington', 'George Washington'],
            ["george\r\nwashington", "George\r\nWashington"],
            ['мога', 'Мога'],
            ['αβγ δεζ', 'Αβγ Δεζ'],
            ['åbc öde', 'Åbc Öde'],
        ];
    }

    /**
     * Data provider for testTranscode
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function seedTestTranscode(): array
    {
        return [
            ['Åbc Öde €100', 'UTF-8', 'ISO-8859-1', "\xc5bc \xd6de EUR100"],
        ];
    }

    /**
     * Data provider for testing compliant strings
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function seedCompliantStrings(): array
    {
        return [
            ["\xCF\xB0", true],
            ["\xFBa", false],
            ["\xFDa", false],
            ["foo\xF7bar", false],
            ['george Мога Ž Ψυχοφθόρα ฉันกินกระจกได้ 我能吞下玻璃而不伤身体 ', true],
            ["\xFF ABC", false],
            ["0xfffd ABC", true],
            ['', true],
        ];
    }

    /**
     * Data provider for testUnicodeToUtf8
     *
     * @return  array
     *
     * @since   1.2.0
     */
    public static function seedTestUnicodeToUtf8(): array
    {
        return [
            ["\u0422\u0435\u0441\u0442 \u0441\u0438\u0441\u0442\u0435\u043c\u044b", "Тест системы"],
            ["\u00dcberpr\u00fcfung der Systemumstellung", "Überprüfung der Systemumstellung"],
        ];
    }

    /**
     * Data provider for testUnicodeToUtf16
     *
     * @return  array
     *
     * @since   1.2.0
     */
    public static function seedTestUnicodeToUtf16(): array
    {
        return [
            ["\u0422\u0435\u0441\u0442 \u0441\u0438\u0441\u0442\u0435\u043c\u044b", "Тест системы"],
            ["\u00dcberpr\u00fcfung der Systemumstellung", "Überprüfung der Systemumstellung"],
        ];
    }

    /**
     * @testdox  A string is correctly incremented
     *
     * @param   string       $string    The source string.
     * @param   string|null  $style     The the style (default|dash).
     * @param   integer      $number    If supplied, this number is used for the copy, otherwise it is the 'next' number.
     * @param   string       $expected  Expected result.
     */
    #[DataProvider('seedTestIncrement')]
    public function testIncrement(string $string, ?string $style, int $number, string $expected)
    {
        $this->assertEquals(
            $expected,
            StringHelper::increment($string, $style, $number)
        );
    }

    /**
     * @testdox  A string is checked to determine if it is ASCII
     *
     * @param   string   $string    The string to test.
     * @param   boolean  $expected  Expected result.
     */
    #[DataProvider('seedTestIs_ascii')]
    public function testIs_ascii(string $string, bool $expected)
    {
        $this->assertEquals(
            $expected,
            StringHelper::is_ascii($string)
        );
    }

    /**
     * @testdox  UTF-8 aware strpos() is performed on a string
     *
     * @param   string|boolean        $expected  Expected result
     * @param   string                $haystack  String being examined
     * @param   string                $needle    String being searched for
     * @param   integer|null|boolean  $offset    Optional, specifies the position from which the search should be performed
     */
    #[DataProvider('seedTestStrpos')]
    public function testStrpos($expected, string $haystack, string $needle, $offset = 0)
    {
        $this->assertEquals(
            $expected,
            StringHelper::strpos($haystack, $needle, $offset)
        );
    }

    /**
     * @testdox  UTF-8 aware strrpos() is performed on a string
     *
     * @param   string|boolean        $expected  Expected result
     * @param   string                $haystack  String being examined
     * @param   string                $needle    String being searched for
     * @param   integer|null|boolean  $offset    Optional, specifies the position from which the search should be performed
     */
    #[DataProvider('seedTestStrrpos')]
    public function testStrrpos($expected, string $haystack, string $needle, int $offset = 0)
    {
        $this->assertEquals(
            $expected,
            StringHelper::strrpos($haystack, $needle, $offset)
        );
    }

    /**
     * @testdox  UTF-8 aware substr() is performed on a string
     *
     * @param   string|boolean        $expected  Expected result
     * @param   string                $string    String being processed
     * @param   integer               $offset    Number of UTF-8 characters offset (from left)
     * @param   integer|null|boolean  $offset    Optional, specifies the position from which the search should be performed
     */
    #[DataProvider('seedTestSubstr')]
    public function testSubstr($expected, string $string, int $start, $length = false)
    {
        $this->assertEquals(
            $expected,
            StringHelper::substr($string, $start, $length)
        );
    }

    /**
     * @testdox  UTF-8 aware strtolower() is performed on a string
     *
     * @param   string          $string    String being processed
     * @param   string|boolean  $expected  Expected result
     */
    #[DataProvider('seedTestStrtolower')]
    public function testStrtolower(string $string, $expected)
    {
        $this->assertEquals(
            $expected,
            StringHelper::strtolower($string)
        );
    }

    /**
     * @testdox  UTF-8 aware strtoupper() is performed on a string
     *
     * @param   string          $string    String being processed
     * @param   string|boolean  $expected  Expected result
     */
    #[DataProvider('seedTestStrtoupper')]
    public function testStrtoupper($string, $expected)
    {
        $this->assertEquals(
            $expected,
            StringHelper::strtoupper($string)
        );
    }

    /**
     * @testdox  UTF-8 aware strlen() is performed on a string
     *
     * @param   string          $string    String being processed
     * @param   string|boolean  $expected  Expected result
     */
    #[DataProvider('seedTestStrlen')]
    public function testStrlen(string $string, $expected)
    {
        $this->assertEquals(
            $expected,
            StringHelper::strlen($string)
        );
    }

    /**
     * @testdox  UTF-8 aware str_ireplace() is performed on a string
     *
     * @param   string                $search    String to search
     * @param   string                $replace   Existing string to replace
     * @param   string                $subject   New string to replace with
     * @param   integer|null|boolean  $count     Optional count value to be passed by reference
     * @param   string                $expected  Expected result
     */
    #[DataProvider('seedTestStr_ireplace')]
    public function testStr_ireplace($search, $replace, $subject, $count, $expected)
    {
        $this->assertEquals(
            $expected,
            StringHelper::str_ireplace($search, $replace, $subject, $count)
        );
    }

    /**
     * @testdox  UTF-8 aware str_split() is performed on a string
     *
     * @param   string                $string    UTF-8 encoded string to process
     * @param   integer               $splitLen  Number to characters to split string by
     * @param   array|string|boolean  $expected  Expected result
     */
    #[DataProvider('seedTestStr_split')]
    public function testStr_split($string, $splitLen, $expected)
    {
        $this->assertEquals(
            $expected,
            StringHelper::str_split($string, $splitLen)
        );
    }

    /**
     * @testdox  UTF-8 aware strcasecmp() is performed on a string
     *
     * @param   string                $string1   String 1 to compare
     * @param   string                $string2   String 2 to compare
     * @param   array|string|boolean  $locale    The locale used by strcoll or false to use classical comparison
     * @param   integer               $expected  Expected result
     */
    #[DataProvider('seedTestStrcasecmp')]
    public function testStrcasecmp(string $string1, string $string2, $locale, int $expected)
    {
        // Convert the $locale param to a string if it is an array
        if (\is_array($locale)) {
            $locale = "'" . implode("', '", $locale) . "'";
        }

        if (substr(php_uname(), 0, 6) == 'Darwin' && $locale != false) {
            $this->markTestSkipped('Darwin bug prevents foreign conversion from working properly');
        }

        if ($locale != false && !setlocale(LC_COLLATE, $locale)) {
            $this->markTestSkipped("Locale {$locale} is not available.");
        }

        $actual = StringHelper::strcasecmp($string1, $string2, $locale);

        if ($actual != 0) {
            $actual /= abs($actual);
        }

        $this->assertEquals($expected, $actual);
    }

    /**
     * @testdox  UTF-8 aware strcmp() is performed on a string
     *
     * @param   string   $string1   String 1 to compare
     * @param   string   $string2   String 2 to compare
     * @param   mixed    $locale    The locale used by strcoll or false to use classical comparison
     * @param   integer  $expected  Expected result
     */
    #[DataProvider('seedTestStrcmp')]
    public function testStrcmp(string $string1, string $string2, $locale, int $expected)
    {
        // Convert the $locale param to a string if it is an array
        if (\is_array($locale)) {
            $locale = "'" . implode("', '", $locale) . "'";
        }

        if (substr(php_uname(), 0, 6) == 'Darwin' && $locale != false) {
            $this->markTestSkipped('Darwin bug prevents foreign conversion from working properly');
        }

        if ($locale != false && !setlocale(LC_COLLATE, $locale)) {
            // If the locale is not available, we can't have to transcode the string and can't reliably compare it.
            $this->markTestSkipped("Locale {$locale} is not available.");
        }

        $actual = StringHelper::strcmp($string1, $string2, $locale);

        if ($actual != 0) {
            $actual = $actual / abs($actual);
        }

        $this->assertEquals($expected, $actual);
    }

    /**
     * @testdox  UTF-8 aware strcspn() is performed on a string
     *
     * @param   string           $haystack  The string to process
     * @param   string           $needles   The mask
     * @param   integer|boolean  $start     Optional starting character position (in characters)
     * @param   integer|boolean  $len       Optional length
     * @param   integer          $expected  Expected result
     */
    #[DataProvider('seedTestStrcspn')]
    public function testStrcspn(string $haystack, string $needles, $start, $len, int $expected)
    {
        $this->assertEquals(
            $expected,
            StringHelper::strcspn($haystack, $needles, $start, $len)
        );
    }

    /**
     * @testdox  UTF-8 aware stristr() is performed on a string
     *
     * @param   string          $haystack  The haystack
     * @param   string          $needle    The needle
     * @param   string|boolean  $expect    Expected result
     */
    #[DataProvider('seedTestStristr')]
    public function testStristr(string $haystack, string $needle, $expected)
    {
        $this->assertEquals(
            $expected,
            StringHelper::stristr($haystack, $needle)
        );
    }

    /**
     * @testdox  UTF-8 aware strrev() is performed on a string
     *
     * @param   string  $string    String to be reversed
     * @param   string  $expected  Expected result

     */
    #[DataProvider('seedTestStrrev')]
    public function testStrrev(string $string, string $expected)
    {
        $this->assertEquals(
            $expected,
            StringHelper::strrev($string)
        );
    }

    /**
     * @testdox  UTF-8 aware strspn() is performed on a string
     *
     * @param   string        $subject  The haystack
     * @param   string        $mask     The mask
     * @param   integer|null  $start    Start optional
     * @param   integer|null  $length   Length optional
     * @param   integer       $expect   Expected result
     */
    #[DataProvider('seedTestStrspn')]
    public function testStrspn(string $subject, string $mask, $start, $length, int $expected)
    {
        $this->assertEquals(
            $expected,
            StringHelper::strspn($subject, $mask, $start, $length)
        );
    }

    /**
     * @testdox  UTF-8 aware substr_replace() is performed on a string
     *
     * @param   string                $expected     Expected result
     * @param   string                $string       The haystack
     * @param   string                $replacement  The replacement string
     * @param   integer               $start        Start
     * @param   integer|boolean|null  $length       Length (optional)
     */
    #[DataProvider('seedTestSubstr_replace')]
    public function testSubstr_replace(string $expected, string $string, string $replacement, int $start, $length)
    {
        $this->assertEquals(
            $expected,
            StringHelper::substr_replace($string, $replacement, $start, $length)
        );
    }

    /**
     * @testdox  UTF-8 aware ltrim() is performed on a string
     *
     * @param   string          $string    The string to be trimmed
     * @param   string|boolean  $charlist  The optional charlist of additional characters to trim
     * @param   string          $expected  Expected result
     */
    #[DataProvider('seedTestLtrim')]
    public function testLtrim(string $string, $charlist, string $expected)
    {
        $this->assertEquals(
            $expected,
            StringHelper::ltrim($string, $charlist)
        );
    }

    /**
     * @testdox  UTF-8 aware rtrim() is performed on a string
     *
     * @param   string          $string    The string to be trimmed
     * @param   string|boolean  $charlist  The optional charlist of additional characters to trim
     * @param   string          $expected  Expected result
     */
    #[DataProvider('seedTestRtrim')]
    public function testRtrim(string $string, $charlist, string $expected)
    {
        $this->assertEquals(
            $expected,
            StringHelper::rtrim($string, $charlist)
        );
    }

    /**
     * @testdox  UTF-8 aware trim() is performed on a string
     *
     * @param   string          $string    The string to be trimmed
     * @param   string|boolean  $charlist  The optional charlist of additional characters to trim
     * @param   string          $expected  Expected result
     */
    #[DataProvider('seedTestTrim')]
    public function testTrim(string $string, $charlist, string $expected)
    {
        $this->assertEquals(
            $expected,
            StringHelper::trim($string, $charlist)
        );
    }

    /**
     * @testdox  UTF-8 aware ucfirst() is performed on a string
     *
     * @param   string       $string        String to be processed
     * @param   string|null  $delimiter     The words delimiter (null means do not split the string)
     * @param   string|null  $newDelimiter  The new words delimiter (null means equal to $delimiter)
     * @param   string       $expected      Expected result
     */
    #[DataProvider('seedTestUcfirst')]
    public function testUcfirst(string $string, ?string $delimiter, ?string $newDelimiter, string $expected)
    {
        $this->assertEquals(
            $expected,
            StringHelper::ucfirst($string, $delimiter, $newDelimiter)
        );
    }

    /**
     * @testdox  UTF-8 aware ucwords() is performed on a string
     *
     * @param   string  $string    String to be processed
     * @param   string  $expected  Expected result
     */
    #[DataProvider('seedTestUcwords')]
    public function testUcwords(string $string, string $expected)
    {
        $this->assertEquals(
            $expected,
            StringHelper::ucwords($string)
        );
    }

    /**
     * @testdox  A string is transcoded
     *
     * @param   string       $source        The string to transcode.
     * @param   string       $fromEncoding  The source encoding.
     * @param   string       $toEncoding    The target encoding.
     * @param   string|null  $expect        Expected result.
     */
    #[DataProvider('seedTestTranscode')]
    public function testTranscode(string $source, string $fromEncoding, string $toEncoding, ?string $expected)
    {
        $this->assertEquals(
            $expected,
            StringHelper::transcode($source, $fromEncoding, $toEncoding)
        );
    }

    /**
     * @testdox  A string is tested as valid UTF-8
     *
     * @param   string   $string    UTF-8 encoded string.
     * @param   boolean  $expected  Expected result.
     */
    #[DataProvider('seedCompliantStrings')]
    public function testValid(string $string, bool $expected)
    {
        $this->assertEquals(
            $expected,
            StringHelper::valid($string)
        );
    }

    /**
     * @testdox  A string is converted from unicode to UTF-8
     *
     * @param   string  $string    Unicode string to convert
     * @param   string  $expected  Expected result
     */
    #[DataProvider('seedTestUnicodeToUtf8')]
    public function testUnicodeToUtf8(string $string, string $expected)
    {
        $this->assertEquals(
            $expected,
            StringHelper::unicode_to_utf8($string)
        );
    }

    /**
     * @testdox  A string is converted from unicode to UTF-16
     *
     * @param   string  $string    Unicode string to convert
     * @param   string  $expected  Expected result
     */
    #[DataProvider('seedTestUnicodeToUtf16')]
    public function testUnicodeToUtf16(string $string, string $expected)
    {
        $this->assertEquals(
            $expected,
            StringHelper::unicode_to_utf16($string)
        );
    }

    /**
     * @testdox  A string is checked for UTF-8 compliance
     *
     * @param   string   $string    UTF-8 string to check
     * @param   boolean  $expected  Expected result
     */
    #[DataProvider('seedCompliantStrings')]
    public function testCompliant(string $string, bool $expected)
    {
        $this->assertEquals(
            $expected,
            StringHelper::compliant($string)
        );
    }
}
