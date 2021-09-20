<?php /** @noinspection SpellCheckingInspection */

/**
 * @copyright  Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
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
	public function seedTestIsAscii(): \Generator
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
	public function seedTestStrPos(): \Generator
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
	public function seedTestStrRPos(): \Generator
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
	public function seedTestStrToLower(): \Generator
	{
		yield ['Joomla! Rocks', 'joomla! rocks'];
	}

	/**
	 * Data provider for testStrtoupper
	 *
	 * @return  \Generator
	 */
	public function seedTestStrToUpper(): \Generator
	{
		yield ['Joomla! Rocks', 'JOOMLA! ROCKS'];
	}

	/**
	 * Data provider for testStrlen
	 *
	 * @return  \Generator
	 */
	public function seedTestStrLen(): \Generator
	{
		yield ['Joomla! Rocks', 13];
	}

	/**
	 * Data provider for testStr_ireplace
	 *
	 * @return  \Generator
	 */
	public function seedTestStrIReplace(): \Generator
	{
		yield ['Pig', 'cow', 'the pig jumped', false, 'the cow jumped'];
		yield ['Pig', 'cow', 'the pig jumped', true, 'the cow jumped'];
		yield ['Pig', 'cow', 'the pig jumped over the cow', true, 'the cow jumped over the cow'];
		yield [
			['PIG', 'JUMPED'],
			['cow', 'hopped'],
			'the pig jumped over the pig',
			true,
			'the cow hopped over the cow'
		];
		yield ['шил', 'биш', 'Би шил идэй чадна', true, 'Би биш идэй чадна'];
		yield ['/', ':', '/test/slashes/', true, ':test:slashes:'];
	}

	/**
	 * Data provider for testStr_split
	 *
	 * @return  \Generator
	 */
	public function seedTestStrSplit(): \Generator
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
	public function seedTestStrCaseCmp(): \Generator
	{
		yield ['THIS IS STRING1', 'this is string1', false, 0];
		yield ['this is string1', 'this is string2', false, -1];
		yield ['this is string2', 'this is string1', false, 1];
		yield ['бгдпт', 'бгдпт', false, 0];
		yield [
			'àbc',
			'abc',
			['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'],
			1
		];
		yield [
			'àbc',
			'bcd',
			['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'],
			-1
		];
		yield [
			'é',
			'è',
			['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'],
			-1
		];
		yield [
			'É',
			'é',
			['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'],
			0
		];
		yield [
			'œ',
			'p',
			['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'],
			-1
		];
		yield [
			'œ',
			'n',
			['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'],
			1
		];
	}

	/**
	 * Data provider for testStrcmp
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestStrCmp(): \Generator
	{
		yield ['THIS IS STRING1', 'this is string1', false, -1];
		yield ['this is string1', 'this is string2', false, -1];
		yield ['this is string2', 'this is string1', false, 1];
		yield ['a', 'B', false, 1];
		yield ['A', 'b', false, -1];
		yield [
			'Àbc',
			'abc',
			['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'],
			1
		];
		yield [
			'Àbc',
			'bcd',
			['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'],
			-1
		];
		yield [
			'É',
			'è',
			['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'],
			-1
		];
		yield [
			'é',
			'È',
			['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'],
			-1
		];
		yield [
			'Œ',
			'p',
			['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'],
			-1
		];
		yield [
			'Œ',
			'n',
			['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'],
			1
		];
		yield [
			'œ',
			'N',
			['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'],
			1
		];
		yield [
			'œ',
			'P',
			['fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'],
			-1
		];
	}

	/**
	 * Data provider for testStrcspn
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestStrCSpn(): \Generator
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
	public function seedTestStrIStr(): \Generator
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
	public function seedTestStrRev(): \Generator
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
	public function seedTestStrSpn(): \Generator
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
	public function seedTestSubstrReplace(): \Generator
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
	public function seedTestLTrim(): \Generator
	{
		yield ['   abc def', false, 'abc def'];
		yield ['   abc def', '', '   abc def'];
		yield [' Би шил', false, 'Би шил'];
		yield ["\t\n\r\x0BБи шил", false, 'Би шил'];
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
	public function seedTestRTrim(): \Generator
	{
		yield ['abc def   ', false, 'abc def'];
		yield ['abc def   ', '', 'abc def   '];
		yield ['Би шил ', false, 'Би шил'];
		yield ["Би шил\t\n\r\x0B", false, 'Би шил'];
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
		yield ['  abc def   ', false, 'abc def'];
		yield ['  abc def   ', '', '  abc def   '];
		yield ['   Би шил ', false, 'Би шил'];
		yield ["\t\n\r\x0BБи шил\t\n\r\x0B", false, 'Би шил'];
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
	 * @testdox       A string is correctly incremented
	 *
	 * @param   string       $string    The source string.
	 * @param   string|null  $style     The style (default|dash).
	 * @param   integer      $number    If supplied, this number is used for the copy, otherwise it is the 'next' number.
	 * @param   string       $expected  Expected result.
	 *
	 * @dataProvider  seedTestIncrement
	 */
	public function testIncrement(string $string, ?string $style, int $number, string $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::increment($string, $style, $number)
		);
	}

	/**
	 * @testdox       A string is checked to determine if it is ASCII
	 *
	 * @param   string   $string    The string to test.
	 * @param   boolean  $expected  Expected result.
	 *
	 * @dataProvider  seedTestIsAscii
	 */
	public function testIsAscii(string $string, bool $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::is_ascii($string)
		);
	}

	/**
	 * @testdox       UTF-8 aware strpos() is performed on a string
	 *
	 * @param   string|boolean        $expected  Expected result
	 * @param   string                $haystack  String being examined
	 * @param   string                $needle    String being searched for
	 * @param   integer|null|boolean  $offset    Optional, specifies the position from which the search should be performed
	 *
	 * @dataProvider  seedTestStrPos
	 */
	public function testStrPos($expected, string $haystack, string $needle, $offset = 0): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::strpos($haystack, $needle, $offset)
		);
	}

	/**
	 * @testdox       UTF-8 aware strrpos() is performed on a string
	 *
	 * @param   string|boolean        $expected  Expected result
	 * @param   string                $haystack  String being examined
	 * @param   string                $needle    String being searched for
	 * @param   integer|null|boolean  $offset    Optional, specifies the position from which the search should be performed
	 *
	 * @dataProvider  seedTestStrRPos
	 */
	public function testStrRPos($expected, string $haystack, string $needle, int $offset = 0): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::strrpos($haystack, $needle, $offset)
		);
	}

	/**
	 * @testdox       UTF-8 aware substr() is performed on a string
	 *
	 * @param   string|boolean        $expected  Expected result
	 * @param   string                $string    String being processed
	 * @param   integer               $start     Number of UTF-8 characters offset (from left)
	 * @param   integer|null|boolean  $length    Optional, specifies the length
	 *
	 * @dataProvider  seedTestSubstr
	 */
	public function testSubstr($expected, string $string, int $start, $length = false): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::substr($string, $start, $length)
		);
	}

	/**
	 * @testdox       UTF-8 aware strtolower() is performed on a string
	 *
	 * @param   string          $string    String being processed
	 * @param   string|boolean  $expected  Expected result
	 *
	 * @dataProvider  seedTestStrToLower
	 */
	public function testStrToLower(string $string, $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::strtolower($string)
		);
	}

	/**
	 * @testdox       UTF-8 aware strtoupper() is performed on a string
	 *
	 * @param   string          $string    String being processed
	 * @param   string|boolean  $expected  Expected result
	 *
	 * @dataProvider  seedTestStrToUpper
	 */
	public function testStrToUpper(string $string, $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::strtoupper($string)
		);
	}

	/**
	 * @testdox       UTF-8 aware strlen() is performed on a string
	 *
	 * @param   string          $string    String being processed
	 * @param   string|boolean  $expected  Expected result
	 *
	 * @dataProvider  seedTestStrLen
	 */
	public function testStrLen(string $string, $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::strlen($string)
		);
	}

	/**
	 * @testdox       UTF-8 aware str_ireplace() is performed on a string
	 *
	 * @param   string[]|string       $search    String to search
	 * @param   string[]|string       $replace   Existing string to replace
	 * @param   string                $subject   New string to replace with
	 * @param   integer|null|boolean  $count     Optional count value to be passed by reference
	 * @param   string                $expected  Expected result
	 *
	 * @return  void
	 *
	 * @dataProvider  seedTestStrIReplace
	 */
	public function testStrIReplace($search, $replace, string $subject, $count, string $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::str_ireplace($search, $replace, $subject, $count)
		);
	}

	/**
	 * @testdox       UTF-8 aware str_split() is performed on a string
	 *
	 * @param   string                $string    UTF-8 encoded string to process
	 * @param   integer               $splitLen  Number to characters to split string by
	 * @param   array|string|boolean  $expected  Expected result
	 *
	 * @dataProvider  seedTestStrSplit
	 */
	public function testStrSplit(string $string, int $splitLen, $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::str_split($string, $splitLen)
		);
	}

	/**
	 * @testdox       UTF-8 aware strcasecmp() is performed on a string
	 *
	 * @param   string                $string1   String 1 to compare
	 * @param   string                $string2   String 2 to compare
	 * @param   array|string|boolean  $locale    The locale used by strcoll or false to use classical comparison
	 * @param   integer               $expected  Expected result
	 *
	 * @dataProvider  seedTestStrCaseCmp
	 */
	public function testStrCaseCmp(string $string1, string $string2, $locale, int $expected): void
	{
		// Convert the $locale param to a string if it is an array
		if (\is_array($locale))
		{
			$locale = "'" . implode("', '", $locale) . "'";
		}

		if ($locale !== false && strpos(php_uname(), 'Darwin') === 0)
		{
			$this->markTestSkipped('Darwin bug prevents foreign conversion from working properly');
		}

		if ($locale !== false && !setlocale(LC_COLLATE, $locale))
		{
			$this->markTestSkipped("Locale $locale is not available.");
		}

		$actual = StringHelper::strcasecmp($string1, $string2, $locale);

		if ($actual !== 0)
		{
			$actual /= abs($actual);
		}

		$this->assertEquals($expected, $actual);
	}

	/**
	 * @testdox       UTF-8 aware strcmp() is performed on a string
	 *
	 * @param   string   $string1   String 1 to compare
	 * @param   string   $string2   String 2 to compare
	 * @param   mixed    $locale    The locale used by strcoll or false to use classical comparison
	 * @param   integer  $expected  Expected result
	 *
	 * @dataProvider  seedTestStrCmp
	 */
	public function testStrCmp(string $string1, string $string2, $locale, int $expected): void
	{
		// Convert the $locale param to a string if it is an array
		if (\is_array($locale))
		{
			$locale = "'" . implode("', '", $locale) . "'";
		}

		if ($locale !== false && strpos(php_uname(), 'Darwin') === 0)
		{
			$this->markTestSkipped('Darwin bug prevents foreign conversion from working properly');
		}

		if ($locale !== false && !setlocale(LC_COLLATE, $locale))
		{
			// If the locale is not available, we can't have to transcode the string and can't reliably compare it.
			$this->markTestSkipped("Locale $locale is not available.");
		}

		$actual = StringHelper::strcmp($string1, $string2, $locale);

		if ($actual !== 0)
		{
			$actual /= abs($actual);
		}

		$this->assertEquals($expected, $actual);
	}

	/**
	 * @testdox       UTF-8 aware strcspn() is performed on a string
	 *
	 * @param   string           $haystack  The string to process
	 * @param   string           $needles   The mask
	 * @param   integer|boolean  $start     Optional starting character position (in characters)
	 * @param   integer|boolean  $len       Optional length
	 * @param   integer          $expected  Expected result
	 *
	 * @dataProvider  seedTestStrCSpn
	 */
	public function testStrCSpn(string $haystack, string $needles, $start, $len, int $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::strcspn($haystack, $needles, $start, $len)
		);
	}

	/**
	 * @testdox       UTF-8 aware stristr() is performed on a string
	 *
	 * @param   string          $haystack  The haystack
	 * @param   string          $needle    The needle
	 * @param   string|boolean  $expected  Expected result
	 *
	 * @dataProvider  seedTestStrIStr
	 */
	public function testStrIStr(string $haystack, string $needle, $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::stristr($haystack, $needle)
		);
	}

	/**
	 * @testdox       UTF-8 aware strrev() is performed on a string
	 *
	 * @param   string  $string    String to be reversed
	 * @param   string  $expected  Expected result
	 *
	 * @dataProvider  seedTestStrRev
	 */
	public function testStrRev(string $string, string $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::strrev($string)
		);
	}

	/**
	 * @testdox       UTF-8 aware strspn() is performed on a string
	 *
	 * @param   string        $subject   The haystack
	 * @param   string        $mask      The mask
	 * @param   integer|null  $start     Start optional
	 * @param   integer|null  $length    Length optional
	 * @param   integer       $expected  Expected result
	 *
	 * @dataProvider  seedTestStrSpn
	 */
	public function testStrSpn(string $subject, string $mask, ?int $start, ?int $length, int $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::strspn($subject, $mask, $start, $length)
		);
	}

	/**
	 * @testdox       UTF-8 aware substr_replace() is performed on a string
	 *
	 * @param   string                $expected     Expected result
	 * @param   string                $string       The haystack
	 * @param   string                $replacement  The replacement string
	 * @param   integer               $start        Start
	 * @param   integer|boolean|null  $length       Length (optional)
	 *
	 * @dataProvider  seedTestSubstrReplace
	 */
	public function testSubstrReplace(string $expected, string $string, string $replacement, int $start, $length): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::substr_replace($string, $replacement, $start, $length)
		);
	}

	/**
	 * @testdox       UTF-8 aware ltrim() is performed on a string
	 *
	 * @param   string          $string    The string to be trimmed
	 * @param   string|boolean  $charlist  The optional charlist of additional characters to trim
	 * @param   string          $expected  Expected result
	 *
	 * @dataProvider  seedTestLTrim
	 */
	public function testLTrim(string $string, $charlist, string $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::ltrim($string, $charlist)
		);
	}

	/**
	 * @testdox       UTF-8 aware rtrim() is performed on a string
	 *
	 * @param   string          $string    The string to be trimmed
	 * @param   string|boolean  $charlist  The optional charlist of additional characters to trim
	 * @param   string          $expected  Expected result
	 *
	 * @dataProvider  seedTestRTrim
	 */
	public function testRTrim(string $string, $charlist, string $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::rtrim($string, $charlist)
		);
	}

	/**
	 * @testdox       UTF-8 aware trim() is performed on a string
	 *
	 * @param   string          $string    The string to be trimmed
	 * @param   string|boolean  $charlist  The optional charlist of additional characters to trim
	 * @param   string          $expected  Expected result
	 *
	 * @dataProvider  seedTestTrim
	 */
	public function testTrim(string $string, $charlist, string $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::trim($string, $charlist)
		);
	}

	/**
	 * @testdox       UTF-8 aware ucfirst() is performed on a string
	 *
	 * @param   string       $string        String to be processed
	 * @param   string|null  $delimiter     The delimiter (null means do not split the string)
	 * @param   string|null  $newDelimiter  The new delimiter (null means equal to $delimiter)
	 * @param   string       $expected      Expected result
	 *
	 * @dataProvider  seedTestUcfirst
	 */
	public function testUcfirst(string $string, ?string $delimiter, ?string $newDelimiter, string $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::ucfirst($string, $delimiter, $newDelimiter)
		);
	}

	/**
	 * @testdox       UTF-8 aware ucwords() is performed on a string
	 *
	 * @param   string  $string    String to be processed
	 * @param   string  $expected  Expected result
	 *
	 * @dataProvider  seedTestUcwords
	 */
	public function testUcwords(string $string, string $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::ucwords($string)
		);
	}

	/**
	 * @testdox       A string is transcoded
	 *
	 * @param   string       $source        The string to transcode.
	 * @param   string       $fromEncoding  The source encoding.
	 * @param   string       $toEncoding    The target encoding.
	 * @param   string|null  $expected      Expected result.
	 *
	 * @dataProvider  seedTestTranscode
	 */
	public function testTranscode(string $source, string $fromEncoding, string $toEncoding, ?string $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::transcode($source, $fromEncoding, $toEncoding)
		);
	}

	/**
	 * @testdox       A string is tested as valid UTF-8
	 *
	 * @param   string   $string    UTF-8 encoded string.
	 * @param   boolean  $expected  Expected result.
	 *
	 * @dataProvider  seedCompliantStrings
	 */
	public function testValid(string $string, bool $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::valid($string)
		);
	}

	/**
	 * @testdox       A string is converted from unicode to UTF-8
	 *
	 * @param   string  $string    The Unicode string to be converted
	 * @param   string  $expected  Expected result
	 *
	 * @dataProvider  seedTestUnicodeToUtf8
	 */
	public function testUnicodeToUtf8(string $string, string $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::unicode_to_utf8($string)
		);
	}

	/**
	 * @testdox       A string is converted from unicode to UTF-16
	 *
	 * @param   string  $string    The Unicode string to be converted
	 * @param   string  $expected  Expected result
	 *
	 * @dataProvider  seedTestUnicodeToUtf16
	 */
	public function testUnicodeToUtf16(string $string, string $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::unicode_to_utf16($string)
		);
	}

	/**
	 * @testdox       A string is checked for UTF-8 compliance
	 *
	 * @param   string   $string    UTF-8 string to check
	 * @param   boolean  $expected  Expected result
	 *
	 * @dataProvider  seedCompliantStrings
	 */
	public function testCompliant(string $string, bool $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::compliant($string)
		);
	}
}
