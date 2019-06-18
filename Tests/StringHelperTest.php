<?php
/**
 * @copyright  Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\String\Tests;

use Joomla\String\StringHelper;
use PHPUnit\Framework\TestCase;

/**
 * Test class for StringHelper.
 */
class StringHelperTest extends TestCase
{
	/**
	 * Data provider for testIncrement
	 *
	 * @return  \Generator
	 */
	public function seedTestIncrement(): \Generator
	{
		// Note: string, style, number, expected
		yield 'First default increment' => ['title', null, 0, 'title (2)'];
		yield 'Second default increment' => ['title(2)', null, 0, 'title(3)'];
		yield 'First dash increment' => ['title', 'dash', 0, 'title-2'];
		yield 'Second dash increment' => ['title-2', 'dash', 0, 'title-3'];
		yield 'Set default increment' => ['title', null, 4, 'title (4)'];
		yield 'Unknown style fallback to default' => ['title', 'foo', 0, 'title (2)'];
	}

	/**
	 * Data provider for testIs_ascii
	 *
	 * @return  \Generator
	 */
	public function seedTestIs_ascii(): \Generator
	{
		yield ['ascii', true];
		yield ['1024', true];
		yield ['#$#@$%', true];
		yield ['áÑ', false];
		yield ['ÿ©', false];
		yield ['¡¾', false];
		yield ['÷™', false];
	}

	/**
	 * Data provider for testStrpos
	 *
	 * @return  \Generator
	 */
	public function seedTestStrpos(): \Generator
	{
		yield [3, 'missing', 'sing', 0];
		yield [false, 'missing', 'sting', 0];
		yield [4, 'missing', 'ing', 0];
		yield [10, ' объектов на карте с', 'на карте', 0];
		yield [0, 'на карте с', 'на карте', 0, 0];
		yield [false, 'на карте с', 'на каррте', 0];
		yield [false, 'на карте с', 'на карте', 2];
		yield [3, 'missing', 'sing', false];
	}

	/**
	 * Data provider for testStrrpos
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestStrrpos(): \Generator
	{
		yield [3, 'missing', 'sing', 0];
		yield [false, 'missing', 'sting', 0];
		yield [4, 'missing', 'ing', 0];
		yield [10, ' объектов на карте с', 'на карте', 0];
		yield [0, 'на карте с', 'на карте', 0];
		yield [false, 'на карте с', 'на каррте', 0];
		yield [3, 'на карте с', 'карт', 2];
	}

	/**
	 * Data provider for testSubstr
	 *
	 * @return  \Generator
	 */
	public function seedTestSubstr(): \Generator
	{
		yield ['issauga', 'Mississauga', 4, false];
		yield ['на карте с', ' объектов на карте с', 10, false];
		yield ['на ка', ' объектов на карте с', 10, 5];
		yield ['те с', ' объектов на карте с', -4, false];
		yield [false, ' объектов на карте с', 99, false];
	}

	/**
	 * Data provider for testStrtolower
	 *
	 * @return  \Generator
	 */
	public function seedTestStrtolower(): \Generator
	{
		yield ['Joomla! Rocks', 'joomla! rocks'];
	}

	/**
	 * Data provider for testStrtoupper
	 *
	 * @return  \Generator
	 */
	public function seedTestStrtoupper(): \Generator
	{
		yield ['Joomla! Rocks', 'JOOMLA! ROCKS'];
	}

	/**
	 * Data provider for testStrlen
	 *
	 * @return  \Generator
	 */
	public function seedTestStrlen(): \Generator
	{
		yield ['Joomla! Rocks', 13];
	}

	/**
	 * Data provider for testStr_ireplace
	 *
	 * @return  \Generator
	 */
	public function seedTestStr_ireplace(): \Generator
	{
		yield ['Pig', 'cow', 'the pig jumped', false, 'the cow jumped'];
		yield ['Pig', 'cow', 'the pig jumped', true, 'the cow jumped'];
		yield ['Pig', 'cow', 'the pig jumped over the cow', true, 'the cow jumped over the cow'];
		yield [['PIG', 'JUMPED'], ['cow', 'hopped'], 'the pig jumped over the pig', true, 'the cow hopped over the cow'];
		yield ['шил', 'биш', 'Би шил идэй чадна', true, 'Би биш идэй чадна'];
		yield ['/', ':', '/test/slashes/', true, ':test:slashes:'];
	}

	/**
	 * Data provider for testStr_split
	 *
	 * @return  \Generator
	 */
	public function seedTestStr_split(): \Generator
	{
		yield ['string', 1, ['s', 't', 'r', 'i', 'n', 'g']];
		yield ['string', 2, ['st', 'ri', 'ng']];
		yield ['волн', 3, ['вол', 'н']];
		yield ['волн', 1, ['в', 'о', 'л', 'н']];
	}

	/**
	 * Data provider for testStrcasecmp
	 *
	 * @return  \Generator
	 */
	public function seedTestStrcasecmp(): \Generator
	{
		yield ['THIS IS STRING1', 'this is string1', false, 0];
		yield ['this is string1', 'this is string2', false, -1];
		yield ['this is string2', 'this is string1', false, 1];
		yield ['бгдпт', 'бгдпт', false, 0];
		yield ['àbc', 'abc', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], 1];
		yield ['àbc', 'bcd', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], -1];
		yield ['é', 'è', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], -1];
		yield ['É', 'é', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], 0];
		yield ['œ', 'p', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], -1];
		yield ['œ', 'n', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], 1];
	}

	/**
	 * Data provider for testStrcmp
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestStrcmp(): \Generator
	{
		yield ['THIS IS STRING1', 'this is string1', false, -1];
		yield ['this is string1', 'this is string2', false, -1];
		yield ['this is string2', 'this is string1', false, 1];
		yield ['a', 'B', false, 1];
		yield ['A', 'b', false, -1];
		yield ['Àbc', 'abc', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], 1];
		yield ['Àbc', 'bcd', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], -1];
		yield ['É', 'è', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], -1];
		yield ['é', 'È', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], -1];
		yield ['Œ', 'p', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], -1];
		yield ['Œ', 'n', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], 1];
		yield ['œ', 'N', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], 1];
		yield ['œ', 'P', ['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'], -1];
	}

	/**
	 * Data provider for testStrcspn
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestStrcspn(): \Generator
	{
		yield ['subject <a> string <a>', '<>', false, false, 8];
		yield ['Би шил {123} идэй {456} чадна', '}{', null, false, 7];
		yield ['Би шил {123} идэй {456} чадна', '}{', 13, 10, 5];
	}

	/**
	 * Data provider for testStristr
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestStristr(): \Generator
	{
		yield ['haystack', 'needle', false];
		yield ['before match, after match', 'match', 'match, after match'];
		yield ['Би шил идэй чадна', 'шил', 'шил идэй чадна'];
	}

	/**
	 * Data provider for testStrrev
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestStrrev(): \Generator
	{
		yield ['abc def', 'fed cba'];
		yield ['Би шил', 'лиш иБ'];
	}

	/**
	 * Data provider for testStrspn
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestStrspn(): \Generator
	{
		yield ['A321 Main Street', '0123456789', 1, 2, 2];
		yield ['321 Main Street', '0123456789', null, 2, 2];
		yield ['A321 Main Street', '0123456789', null, 10, 0];
		yield ['321 Main Street', '0123456789', null, null, 3];
		yield ['Main Street 321', '0123456789', null, -3, 0];
		yield ['321 Main Street', '0123456789', null, -13, 2];
		yield ['321 Main Street', '0123456789', null, -12, 3];
		yield ['A321 Main Street', '0123456789', 0, null, 0];
		yield ['A321 Main Street', '0123456789', 1, 10, 3];
		yield ['A321 Main Street', '0123456789', 1, null, 3];
		yield ['Би шил идэй чадна', 'Би', null, null, 2];
		yield ['чадна Би шил идэй чадна', 'Би', null, null, 0];
	}

	/**
	 * Data provider for testSubstr_replace
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestSubstr_replace(): \Generator
	{
		yield ['321 Broadway Avenue', '321 Main Street', 'Broadway Avenue', 4, false];
		yield ['321 Broadway Street', '321 Main Street', 'Broadway', 4, 4];
		yield ['чадна 我能吞', 'чадна Би шил идэй чадна', '我能吞', 6, false];
		yield ['чадна 我能吞 шил идэй чадна', 'чадна Би шил идэй чадна', '我能吞', 6, 2];
	}

	/**
	 * Data provider for testLtrim
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestLtrim(): \Generator
	{
		yield ['   abc def', null, 'abc def'];
		yield ['   abc def', '', '   abc def'];
		yield [' Би шил', null, 'Би шил'];
		yield ["\t\n\r\x0BБи шил", null, 'Би шил'];
		yield ["\x0B\t\n\rБи шил", "\t\n\x0B", "\rБи шил"];
		yield ["\x09Би шил\x0A", "\x09\x0A", "Би шил\x0A"];
		yield ['1234abc', '0123456789', 'abc'];
	}

	/**
	 * Data provider for testRtrim
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestRtrim(): \Generator
	{
		yield ['abc def   ', null, 'abc def'];
		yield ['abc def   ', '', 'abc def   '];
		yield ['Би шил ', null, 'Би шил'];
		yield ["Би шил\t\n\r\x0B", null, 'Би шил'];
		yield ["Би шил\r\x0B\t\n", "\t\n\x0B", "Би шил\r"];
		yield ["\x09Би шил\x0A", "\x09\x0A", "\x09Би шил"];
		yield ['1234abc', 'abc', '1234'];
	}

	/**
	 * Data provider for testTrim
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestTrim(): \Generator
	{
		yield ['  abc def   ', null, 'abc def'];
		yield ['  abc def   ', '', '  abc def   '];
		yield ['   Би шил ', null, 'Би шил'];
		yield ["\t\n\r\x0BБи шил\t\n\r\x0B", null, 'Би шил'];
		yield ["\x0B\t\n\rБи шил\r\x0B\t\n", "\t\n\x0B", "\rБи шил\r"];
		yield ["\x09Би шил\x0A", "\x09\x0A", "Би шил"];
		yield ['1234abc56789', '0123456789', 'abc'];
	}

	/**
	 * Data provider for testUcfirst
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestUcfirst(): \Generator
	{
		yield ['george', null, null, 'George'];
		yield ['мога', null, null, 'Мога'];
		yield ['ψυχοφθόρα', null, null, 'Ψυχοφθόρα'];
		yield ['dr jekill and mister hyde', ' ', null, 'Dr Jekill And Mister Hyde'];
		yield ['dr jekill and mister hyde', ' ', '_', 'Dr_Jekill_And_Mister_Hyde'];
		yield ['dr jekill and mister hyde', ' ', '', 'DrJekillAndMisterHyde'];
	}

	/**
	 * Data provider for testUcwords
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestUcwords(): \Generator
	{
		yield ['george washington', 'George Washington'];
		yield ["george\r\nwashington", "George\r\nWashington"];
		yield ['мога', 'Мога'];
		yield ['αβγ δεζ', 'Αβγ Δεζ'];
		yield ['åbc öde', 'Åbc Öde'];
	}

	/**
	 * Data provider for testTranscode
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestTranscode(): \Generator
	{
		yield ['Åbc Öde €100', 'UTF-8', 'ISO-8859-1', "\xc5bc \xd6de EUR100"];
		yield [['Åbc Öde €100'], 'UTF-8', 'ISO-8859-1', null];
	}

	/**
	 * Data provider for testing compliant strings
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedCompliantStrings(): \Generator
	{
		yield ["\xCF\xB0", true];
		yield ["\xFBa", false];
		yield ["\xFDa", false];
		yield ["foo\xF7bar", false];
		yield ['george Мога Ž Ψυχοφθόρα ฉันกินกระจกได้ 我能吞下玻璃而不伤身体 ', true];
		yield ["\xFF ABC", false];
		yield ["0xfffd ABC", true];
		yield ['', true];
	}

	/**
	 * Data provider for testUnicodeToUtf8
	 *
	 * @return  \Generator
	 *
	 * @since   1.2.0
	 */
	public function seedTestUnicodeToUtf8(): \Generator
	{
		yield ["\u0422\u0435\u0441\u0442 \u0441\u0438\u0441\u0442\u0435\u043c\u044b", "Тест системы"];
		yield ["\u00dcberpr\u00fcfung der Systemumstellung", "Überprüfung der Systemumstellung"];
	}

	/**
	 * Data provider for testUnicodeToUtf16
	 *
	 * @return  \Generator
	 *
	 * @since   1.2.0
	 */
	public function seedTestUnicodeToUtf16(): \Generator
	{
		yield ["\u0422\u0435\u0441\u0442 \u0441\u0438\u0441\u0442\u0435\u043c\u044b", "Тест системы"];
		yield ["\u00dcberpr\u00fcfung der Systemumstellung", "Überprüfung der Systemumstellung"];
	}

	/**
	 * Test...
	 *
	 * @param   string  $string    @todo
	 * @param   string  $style     @todo
	 * @param   string  $number    @todo
	 * @param   string  $expected  @todo
	 *
	 * @return  void
	 *
	 * @covers        Joomla\String\StringHelper::increment
	 * @dataProvider  seedTestIncrement
	 * @since         1.0
	 */
	public function testIncrement($string, $style, $number, $expected)
	{
		$this->assertEquals(
			$expected,
			StringHelper::increment($string, $style, $number)
		);
	}

	/**
	 * Test...
	 *
	 * @param   string   $string    @todo
	 * @param   boolean  $expected  @todo
	 *
	 * @return  void
	 *
	 * @covers        Joomla\String\StringHelper::is_ascii
	 * @dataProvider  seedTestIs_ascii
	 * @since         1.2.0
	 */
	public function testIs_ascii($string, $expected)
	{
		$this->assertEquals(
			$expected,
			StringHelper::is_ascii($string)
		);
	}

	/**
	 * Test...
	 *
	 * @param   string   $expect    @todo
	 * @param   string   $haystack  @todo
	 * @param   string   $needle    @todo
	 * @param   integer  $offset    @todo
	 *
	 * @return  void
	 *
	 * @covers        Joomla\String\StringHelper::strpos
	 * @dataProvider  seedTestStrpos
	 * @since         1.0
	 */
	public function testStrpos($expect, $haystack, $needle, $offset = 0)
	{
		$actual = StringHelper::strpos($haystack, $needle, $offset);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * Test...
	 *
	 * @param   string   $expect    @todo
	 * @param   string   $haystack  @todo
	 * @param   string   $needle    @todo
	 * @param   integer  $offset    @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::strrpos
	 * @dataProvider  seedTestStrrpos
	 * @since         1.0
	 */
	public function testStrrpos($expect, $haystack, $needle, $offset = 0)
	{
		$actual = StringHelper::strrpos($haystack, $needle, $offset);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * Test...
	 *
	 * @param   string    $expect  @todo
	 * @param   string    $string  @todo
	 * @param   string    $start   @todo
	 * @param   bool|int  $length  @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::substr
	 * @dataProvider  seedTestSubstr
	 * @since         1.0
	 */
	public function testSubstr($expect, $string, $start, $length = false)
	{
		$actual = StringHelper::substr($string, $start, $length);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * Test...
	 *
	 * @param   string  $string  @todo
	 * @param   string  $expect  @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::strtolower
	 * @dataProvider  seedTestStrtolower
	 * @since         1.0
	 */
	public function testStrtolower($string, $expect)
	{
		$actual = StringHelper::strtolower($string);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * Test...
	 *
	 * @param   string  $string  @todo
	 * @param   string  $expect  @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::strtoupper
	 * @dataProvider  seedTestStrtoupper
	 * @since         1.0
	 */
	public function testStrtoupper($string, $expect)
	{
		$actual = StringHelper::strtoupper($string);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * Test...
	 *
	 * @param   string  $string  @todo
	 * @param   string  $expect  @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::strlen
	 * @dataProvider  seedTestStrlen
	 * @since         1.0
	 */
	public function testStrlen($string, $expect)
	{
		$actual = StringHelper::strlen($string);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * Test...
	 *
	 * @param   string   $search   @todo
	 * @param   string   $replace  @todo
	 * @param   string   $subject  @todo
	 * @param   integer  $count    @todo
	 * @param   string   $expect   @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::str_ireplace
	 * @dataProvider  seedTestStr_ireplace
	 * @since         1.0
	 */
	public function testStr_ireplace($search, $replace, $subject, $count, $expect)
	{
		$actual = StringHelper::str_ireplace($search, $replace, $subject, $count);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * Test...
	 *
	 * @param   string  $string        @todo
	 * @param   string  $split_length  @todo
	 * @param   string  $expect        @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::str_split
	 * @dataProvider  seedTestStr_split
	 * @since         1.0
	 */
	public function testStr_split($string, $split_length, $expect)
	{
		$actual = StringHelper::str_split($string, $split_length);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * Test...
	 *
	 * @param   string  $string1  @todo
	 * @param   string  $string2  @todo
	 * @param   string  $locale   @todo
	 * @param   string  $expect   @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::strcasecmp
	 * @dataProvider  seedTestStrcasecmp
	 * @since         1.0
	 */
	public function testStrcasecmp($string1, $string2, $locale, $expect)
	{
		// Convert the $locale param to a string if it is an array
		if (\is_array($locale))
		{
			$locale = "'" . implode("', '", $locale) . "'";
		}

		if (substr(php_uname(), 0, 6) == 'Darwin' && $locale != false)
		{
			$this->markTestSkipped('Darwin bug prevents foreign conversion from working properly');
		}
		elseif ($locale != false && !setlocale(LC_COLLATE, $locale))
		{
			$this->markTestSkipped("Locale {$locale} is not available.");
		}
		else
		{
			$actual = StringHelper::strcasecmp($string1, $string2, $locale);

			if ($actual != 0)
			{
				$actual = $actual / abs($actual);
			}

			$this->assertEquals($expect, $actual);
		}
	}

	/**
	 * Test...
	 *
	 * @param   string  $string1  @todo
	 * @param   string  $string2  @todo
	 * @param   string  $locale   @todo
	 * @param   string  $expect   @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::strcmp
	 * @dataProvider  seedTestStrcmp
	 * @since         1.0
	 */
	public function testStrcmp($string1, $string2, $locale, $expect)
	{
		// Convert the $locale param to a string if it is an array
		if (\is_array($locale))
		{
			$locale = "'" . implode("', '", $locale) . "'";
		}

		if (substr(php_uname(), 0, 6) == 'Darwin' && $locale != false)
		{
			$this->markTestSkipped('Darwin bug prevents foreign conversion from working properly');
		}
		elseif ($locale != false && !setlocale(LC_COLLATE, $locale))
		{
			// If the locale is not available, we can't have to transcode the string and can't reliably compare it.
			$this->markTestSkipped("Locale {$locale} is not available.");
		}
		else
		{
			$actual = StringHelper::strcmp($string1, $string2, $locale);

			if ($actual != 0)
			{
				$actual = $actual / abs($actual);
			}

			$this->assertEquals($expect, $actual);
		}
	}

	/**
	 * Test...
	 *
	 * @param   string   $haystack  @todo
	 * @param   string   $needles   @todo
	 * @param   integer  $start     @todo
	 * @param   integer  $len       @todo
	 * @param   string   $expect    @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::strcspn
	 * @dataProvider  seedTestStrcspn
	 * @since         1.0
	 */
	public function testStrcspn($haystack, $needles, $start, $len, $expect)
	{
		$actual = StringHelper::strcspn($haystack, $needles, $start, $len);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * Test...
	 *
	 * @param   string  $haystack  @todo
	 * @param   string  $needle    @todo
	 * @param   string  $expect    @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::stristr
	 * @dataProvider  seedTestStristr
	 * @since         1.0
	 */
	public function testStristr($haystack, $needle, $expect)
	{
		$actual = StringHelper::stristr($haystack, $needle);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * Test...
	 *
	 * @param   string  $string  @todo
	 * @param   string  $expect  @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::strrev
	 * @dataProvider  seedTestStrrev
	 * @since         1.0
	 */
	public function testStrrev($string, $expect)
	{
		$actual = StringHelper::strrev($string);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * Test...
	 *
	 * @param   string   $subject  @todo
	 * @param   string   $mask     @todo
	 * @param   integer  $start    @todo
	 * @param   integer  $length   @todo
	 * @param   string   $expect   @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::strspn
	 * @dataProvider  seedTestStrspn
	 * @since         1.0
	 */
	public function testStrspn($subject, $mask, $start, $length, $expect)
	{
		$actual = StringHelper::strspn($subject, $mask, $start, $length);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * Test...
	 *
	 * @param   string   $expect       @todo
	 * @param   string   $string       @todo
	 * @param   string   $replacement  @todo
	 * @param   integer  $start        @todo
	 * @param   integer  $length       @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::substr_replace
	 * @dataProvider  seedTestSubstr_replace
	 * @since         1.0
	 */
	public function testSubstr_replace($expect, $string, $replacement, $start, $length)
	{
		$actual = StringHelper::substr_replace($string, $replacement, $start, $length);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * Test...
	 *
	 * @param   string  $string    @todo
	 * @param   string  $charlist  @todo
	 * @param   string  $expect    @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::ltrim
	 * @dataProvider  seedTestLtrim
	 * @since         1.0
	 */
	public function testLtrim($string, $charlist, $expect)
	{
		if ($charlist === null)
		{
			$actual = StringHelper::ltrim($string);
		}
		else
		{
			$actual = StringHelper::ltrim($string, $charlist);
		}

		$this->assertEquals($expect, $actual);
	}

	/**
	 * Test...
	 *
	 * @param   string  $string    @todo
	 * @param   string  $charlist  @todo
	 * @param   string  $expect    @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::rtrim
	 * @dataProvider  seedTestRtrim
	 * @since         1.0
	 */
	public function testRtrim($string, $charlist, $expect)
	{
		if ($charlist === null)
		{
			$actual = StringHelper::rtrim($string);
		}
		else
		{
			$actual = StringHelper::rtrim($string, $charlist);
		}

		$this->assertEquals($expect, $actual);
	}

	/**
	 * Test...
	 *
	 * @param   string  $string    @todo
	 * @param   string  $charlist  @todo
	 * @param   string  $expect    @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::trim
	 * @dataProvider  seedTestTrim
	 * @since         1.0
	 */
	public function testTrim($string, $charlist, $expect)
	{
		if ($charlist === null)
		{
			$actual = StringHelper::trim($string);
		}
		else
		{
			$actual = StringHelper::trim($string, $charlist);
		}

		$this->assertEquals($expect, $actual);
	}

	/**
	 * Test...
	 *
	 * @param   string  $string        @todo
	 * @param   string  $delimiter     @todo
	 * @param   string  $newDelimiter  @todo
	 * @param   string  $expect        @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::ucfirst
	 * @dataProvider  seedTestUcfirst
	 * @since         1.0
	 */
	public function testUcfirst($string, $delimiter, $newDelimiter, $expect)
	{
		$actual = StringHelper::ucfirst($string, $delimiter, $newDelimiter);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * Test...
	 *
	 * @param   string  $string  @todo
	 * @param   string  $expect  @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::ucwords
	 * @dataProvider  seedTestUcwords
	 * @since         1.0
	 */
	public function testUcwords($string, $expect)
	{
		$actual = StringHelper::ucwords($string);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * Test...
	 *
	 * @param   string  $source         @todo
	 * @param   string  $from_encoding  @todo
	 * @param   string  $to_encoding    @todo
	 * @param   string  $expect         @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::transcode
	 * @dataProvider  seedTestTranscode
	 * @since         1.0
	 */
	public function testTranscode($source, $from_encoding, $to_encoding, $expect)
	{
		$actual = StringHelper::transcode($source, $from_encoding, $to_encoding);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * Test...
	 *
	 * @param   string  $string  @todo
	 * @param   string  $expect  @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::valid
	 * @dataProvider  seedCompliantStrings
	 * @since         1.0
	 */
	public function testValid($string, $expect)
	{
		$actual = StringHelper::valid($string);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * Test...
	 *
	 * @param   string  $string  @todo
	 * @param   string  $expect  @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::unicode_to_utf8
	 * @dataProvider  seedTestUnicodeToUtf8
	 * @since         1.2.0
	 */
	public function testUnicodeToUtf8($string, $expect)
	{
		$actual = StringHelper::unicode_to_utf8($string);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * Test...
	 *
	 * @param   string  $string  @todo
	 * @param   string  $expect  @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::unicode_to_utf16
	 * @dataProvider  seedTestUnicodeToUtf16
	 * @since         1.2.0
	 */
	public function testUnicodeToUtf16($string, $expect)
	{
		$actual = StringHelper::unicode_to_utf16($string);
		$this->assertEquals($expect, $actual);
	}

	/**
	 * Test...
	 *
	 * @param   string  $string  @todo
	 * @param   string  $expect  @todo
	 *
	 * @return  array
	 *
	 * @covers        Joomla\String\StringHelper::compliant
	 * @dataProvider  seedCompliantStrings
	 * @since         1.0
	 */
	public function testCompliant($string, $expect)
	{
		$actual = StringHelper::compliant($string);
		$this->assertEquals($expect, $actual);
	}
}
