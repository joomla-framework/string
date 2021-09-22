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
	const FRENCH_LOCALE      = [
		'fr_FR.utf8',
		'fr_FR.UTF-8',
		'fr_FR.UTF-8@euro',
		'French_Standard',
		'french',
		'fr_FR',
		'fre_FR'
	];
	const RUSSIAN_WIN_LOCALE = ['ru_RU.CP1251'];

	/**
	 * Data provider for testIncrement
	 *
	 * @return  \Generator
	 */
	public function seedTestIncrement(): \Generator
	{
		// Note: input string, incrementation style, next number, expected result
		yield 'appends " (2)" to an unnumbered string (default style)' => ['title', null, 0, 'title (2)'];
		yield 'increments a trailing number by 1 (default style)' => ['title(2)', null, 0, 'title(3)'];
		yield 'appends "-2" to an unnumbered string (dash style)' => ['title', 'dash', 0, 'title-2'];
		yield 'increments a trailing number by 1 (dash style)' => ['title-2', 'dash', 0, 'title-3'];
		yield 'sets the number to the value provided' => ['title', null, 4, 'title (4)'];
		yield 'uses default style, if an unknown style is provided' => ['title', 'foo', 0, 'title (2)'];
	}

	/**
	 * @testdox       StringHelper::increment() $_dataName
	 *
	 * @param   string        $string    The source string.
	 * @param   string|null   $style     The style (default|dash).
	 * @param   integer|null  $number    If supplied and > 0, this number is used for the copy, otherwise it is the 'next' number.
	 * @param   string        $expected  Expected result.
	 *
	 * @dataProvider  seedTestIncrement
	 */
	public function testIncrement(string $string, ?string $style, ?int $number, string $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::increment($string, $style, $number)
		);
	}

	/**
	 * Data provider for testIsAscii
	 *
	 * @return  \Generator
	 */
	public function seedTestIsAscii(): \Generator
	{
		// Note: input string, expected result
		yield '7bit ASCII letters' => ['ascii', true];
		yield 'ASCII numbers' => ['1024', true];
		yield 'ASCII special characters' => ['#$#@$%', true];
		yield 'characters above code 128' => ['áÑÿ©¡¾÷™', false];
		yield 'cyrillic letters' => ['на карте с', false];
		yield 'greek letters' => ['ψυχοφθόρα', false];
		yield 'chinese letters' => ['我能吞', false];
	}

	/**
	 * @testdox       StringHelper::is_ascii() correctly recognises $_dataName
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
	 * Data provider for testOrd
	 *
	 * @return  \Generator
	 */
	public function seedTestOrd(): \Generator
	{
		yield 'lowercase ASCII characters' => ['abc', 97];
		yield 'uppercase ASCII characters' => ['A', 65];
		yield 'cyrillic characters' => ['на', 1085];
		yield 'greek characters' => ['ψ', 968];
		yield 'chinese characters' => ['我能吞', 25105];
	}

	/**
	 * @testdox       SringHelper::ord() returns the ordinal number for $_dataName
	 *
	 * @param   string   $character
	 * @param   integer  $ordinalNumber
	 *
	 * @dataProvider  seedTestOrd
	 */
	public function testOrd(string $character, int $ordinalNumber): void
	{
		$this->assertEquals(
			$ordinalNumber,
			StringHelper::ord($character)
		);
	}

	/**
	 * Data provider for testStrPos
	 *
	 * @return  \Generator
	 */
	public function seedTestStrPos(): \Generator
	{
		// Note: haystack, needle, offset, expected result
		yield 'returns the position of the first occurance of the substring' => ['pinging', 'ing', 0, 1];
		yield 'locates substring in ASCII string' => ['missing', 'sing', 0, 3];
		yield 'locates substring in string with accents' => ['Fábio', 'b', 0, 2];
		yield 'locates substring in cyrillic string' => [' объектов на карте с', 'на карте', 0, 10];
		yield 'locates substring beginning in first position' => ['на карте с', 'на карте', 0, 0, 0];
		yield 'returns false for non-existing substrings' => ['missing', 'sting', 0, false];
		yield 'starts search at the given offset' => ['на карте с', 'на карте', 2, false];
		yield 'starts search at position 0 if no offset is provided' => ['missing', 'mis', null, 0];
	}

	/**
	 * @testdox       StringHelper::strpos() $_dataName
	 *
	 * @param   string                $haystack  String being examined
	 * @param   string                $needle    String being searched for
	 * @param   integer|null|boolean  $offset    The position from which the search should be performed
	 * @param   string|boolean        $expected  Expected result
	 *
	 * @dataProvider  seedTestStrPos
	 */
	public function testStrPos(string $haystack, string $needle, $offset, $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::strpos($haystack, $needle, $offset)
		);
	}

	/**
	 * Data provider for testStrRPos
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestStrRPos(): \Generator
	{
		// Note: haystack, needle, offset, expected result
		yield 'returns the position of the last occurance of the substring' => ['pinging', 'ing', null, 4];
		yield 'locates substring in ASCII string' => ['missing', 'sing', 0, 3];
		yield 'locates substring in cyrillic string' => [' объектов на карте с', 'на карте', 0, 10];
		yield 'returns false for non-existing substrings' => ['missing', 'sting', 0, false];
		yield 'locates substring beginning in first position' => ['на карте с', 'на карте', 0, 0];
		yield 'starts search at the given offset' => ['на карте с', 'карт', 2, 3];
	}

	/**
	 * @testdox       StringHelper::strrpos() $_dataName
	 *
	 * @param   string          $haystack  String being examined
	 * @param   string          $needle    String being searched for
	 * @param   integer|null    $offset    Optional, specifies the position from which the search should be performed
	 * @param   string|boolean  $expected  Expected result
	 *
	 * @dataProvider  seedTestStrRPos
	 */
	public function testStrRPos(string $haystack, string $needle, ?int $offset, $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::strrpos($haystack, $needle, $offset ?? 0)
		);
	}

	/**
	 * Data provider for testSubstr
	 *
	 * @return  \Generator
	 */
	public function seedTestSubstr(): \Generator
	{
		// Note: string, offset, length, expected result
		yield 'extracts substring from offset to end, if no length is provided' => ['Mississauga', 4, null, 'issauga'];
		yield 'extracts substring from cyrillic string' => [' объектов на карте с', 10, null, 'на карте с'];
		yield 'extracts substring of given length' => [' объектов на карте с', 10, 5, 'на ка'];
		yield 'extracts substring from the end, if offset is negative' => [' объектов на карте с', -4, null, 'те с'];
		yield 'returns false, if offset is out of bounds' => [' объектов на карте с', 99, null, false];
	}

	/**
	 * @testdox       StringHelper::substr() $_dataName
	 *
	 * @param   string          $string    String being processed
	 * @param   integer         $start     Number of UTF-8 characters offset (from left)
	 * @param   integer|null    $length    Optional, specifies the length
	 * @param   string|boolean  $expected  Expected result
	 *
	 * @dataProvider  seedTestSubstr
	 */
	public function testSubstr(string $string, int $start, $length, $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::substr($string, $start, $length)
		);
	}

	/**
	 * Data provider for testStrToLower
	 *
	 * @return  \Generator
	 */
	public function seedTestStrToLower(): \Generator
	{
		yield 'converts ASCII string' => ['Joomla! Rocks', 'joomla! rocks'];
		yield 'converts cyrillic string' => ['На Карте С', 'на карте с'];
		yield 'converts greek string' => ['Ψυχοφθόρα', 'ψυχοφθόρα'];
		yield 'leaves chinese string alone' => ['我能吞', '我能吞'];
	}

	/**
	 * @testdox       StringHelper::strtolower() $_dataName
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
	 * Data provider for testStrToUpper
	 *
	 * @return  \Generator
	 */
	public function seedTestStrToUpper(): \Generator
	{
		yield 'converts ASCII string' => ['Joomla! Rocks', 'JOOMLA! ROCKS'];
		yield 'converts cyrillic string' => ['На Карте С', 'НА КАРТЕ С'];
		yield 'converts greek string' => ['Ψυχοφθόρα', 'ΨΥΧΟΦΘΌΡΑ'];
		yield 'leaves chinese string alone' => ['我能吞', '我能吞'];
	}

	/**
	 * @testdox       StringHelper::strtoupper() $_dataName
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
	 * Data provider for testStrLen
	 *
	 * @return  \Generator
	 */
	public function seedTestStrLen(): \Generator
	{
		yield 'an ASCII string' => ['Joomla! Rocks', 13];
		yield 'a cyrillic string' => ['На Карте С', 10];
		yield 'a greek string' => ['Ψυχοφθόρα', 9];
		yield 'a chinese string' => ['我能吞', 3];
	}

	/**
	 * @testdox       StringHelper::strlen() determines the length of $_dataName
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
	 * Data provider for testStrIReplace
	 *
	 * @return  \Generator
	 */
	public function seedTestStrIReplace(): \Generator
	{
		// Note: search, replace, subject, count, expected result
		yield 'does not require "count" variable' => ['Pig', 'cow', 'the pig jumped', null, 'the cow jumped'];
		yield 'counts the number of replacements' => ['Pig', 'cow', 'the pig jumped', 1, 'the cow jumped'];
		yield 'supports arrays for search and replace values' => [
			['PIG', 'JUMPED'],
			['cow', 'hopped'],
			'the pig jumped over the pig',
			3,
			'the cow hopped over the cow'
		];
		yield 'operates on cyrillic string' => ['шил', 'биш', 'Би шил идэй чадна', 1, 'Би биш идэй чадна'];
		yield 'replaces special characters' => ['/', ':', '/test/slashes/', 3, ':test:slashes:'];
		yield 'performes replacement on the result of previous replacement' => [
			['Pig', 'cow'],
			['cow', 'dog'],
			'the pig jumped over the cow',
			3,
			'the dog jumped over the dog'
		];
	}

	/**
	 * @testdox       StringHelper::str_ireplace() $_dataName
	 *
	 * @param   string[]|string  $search          String to search
	 * @param   string[]|string  $replace         Existing string to replace
	 * @param   string           $subject         New string to replace with
	 * @param   integer|null     $expectedCount   Optional count value to be passed by reference
	 * @param   string           $expectedResult  Expected result
	 *
	 * @return  void
	 *
	 * @dataProvider  seedTestStrIReplace
	 */
	public function testStrIReplace(
		$search,
		$replace,
		string $subject,
		?int $expectedCount,
		string $expectedResult
	): void {
		$actualCount = null;

		if ($expectedCount !== null)
		{
			$actualResult = StringHelper::str_ireplace($search, $replace, $subject, $actualCount);
		}
		else
		{
			$actualResult = StringHelper::str_ireplace($search, $replace, $subject);
		}

		$this->assertEquals($expectedResult, $actualResult);
		$this->assertEquals($expectedCount, $actualCount);
	}

	/**
	 * Data provider for testStrPad
	 *
	 * @return  \Generator
	 */
	public function seedTestStrPad(): \Generator
	{
		// Note: input, length, padStr, type, expected result
		yield 'can pad to the right' => ['foo', 5, ' ', STR_PAD_RIGHT, 'foo  '];
		yield 'can pad to the left' => ['foo', 5, ' ', STR_PAD_LEFT, '  foo'];
		yield 'can pad to both sides' => ['foo', 5, ' ', STR_PAD_BOTH, ' foo '];
		yield 'truncates the pad string to fit' => ['foo', 7, 'bar', STR_PAD_BOTH, 'bafooba'];
		yield 'can pad a cyrillic string' => ['На Карте С', 12, 'т', STR_PAD_RIGHT, 'На Карте Стт'];
		yield 'can pad a greek string' => ['Ψυχοφθόρα', 11, 'φ', STR_PAD_RIGHT, 'Ψυχοφθόραφφ'];
		yield 'can pad a chinese string' => ['我能吞', 5, '我', STR_PAD_RIGHT, '我能吞我我'];
	}

	/**
	 * @testdox      StringHelper::strpad() $_dataName
	 *
	 * @dataProvider seedTestStrPad
	 *
	 * @param   string  $string     The input string.
	 * @param   int     $length     Desired string length
	 * @param   string  $padString  The string to add; may be truncated
	 * @param   int     $padType    One of STR_PAD_RIGHT, STR_PAD_LEFT or STR_PAD_BOTH
	 * @param   string  $expected   The expected result
	 */
	public function testStrPad(string $string, int $length, string $padString, int $padType, string $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::str_pad($string, $length, $padString, $padType)
		);
	}

	/**
	 * Data provider for testStrSplit
	 *
	 * @return  \Generator
	 */
	public function seedTestStrSplit(): \Generator
	{
		yield 'splits into single characters by default' => ['string', null, ['s', 't', 'r', 'i', 'n', 'g']];
		yield 'splits into chunks of given size' => ['strings', 2, ['st', 'ri', 'ng', 's']];
		yield 'splits cyrillic strings' => ['волн', 1, ['в', 'о', 'л', 'н']];
	}

	/**
	 * @testdox       StringHelper::str_split() $_dataName
	 *
	 * @param   string        $string    UTF-8 encoded string to process
	 * @param   integer|null  $splitLen  Number to characters to split string by
	 * @param   array         $expected  Expected result
	 *
	 * @dataProvider  seedTestStrSplit
	 */
	public function testStrSplit(string $string, ?int $splitLen, array $expected): void
	{
		if ($splitLen !== null)
		{
			$actual = StringHelper::str_split($string, $splitLen);
		}
		else
		{
			$actual = StringHelper::str_split($string);
		}

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Data provider for testStrCaseCmp
	 *
	 * @return  \Generator
	 */
	public function seedTestStrCaseCmp(): \Generator
	{
		yield 'A = a with default locale' => ['THIS IS STRING1', 'this is string1', false, 0];
		yield 'a < b with default locale' => ['this is string1', 'this is string2', false, -1];
		yield 'b > a with default locale' => ['this is string2', 'this is string1', false, 1];
		yield 'cyrillic д = д with default locale' => ['бгдпт', 'бгдпт', false, 0];
		yield 'à > a with french locale' => ['àbc', 'abc', self::FRENCH_LOCALE, 1];
		yield 'à < b with french locale' => ['àbc', 'bcd', self::FRENCH_LOCALE, -1];
		yield 'é < è with french locale' => ['é', 'è', self::FRENCH_LOCALE, -1];
		yield 'É = é with french locale' => ['É', 'é', self::FRENCH_LOCALE, 0];
		yield 'œ < p with french locale' => ['œ', 'p', self::FRENCH_LOCALE, -1];
		yield 'œ > n with french locale' => ['œ', 'n', self::FRENCH_LOCALE, 1];
		yield 'cyrillic р < т with russian locale' => ['р', 'т', self::RUSSIAN_WIN_LOCALE, -1];
	}

	/**
	 * @testdox       StringHelper::strcasecmp() compares $_dataName
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
		if ($locale !== false && strpos(php_uname(), 'Darwin') === 0)
		{
			$this->markTestSkipped('Darwin bug prevents foreign conversion from working properly');
		}

		if ($locale !== false && setlocale(LC_COLLATE, $locale) === false)
		{
			$this->markTestSkipped(
				sprintf(
					"Locale %s is not available.",
					implode(', ', (array)$locale)
				)
			);
		}

		$actual = StringHelper::strcasecmp($string1, $string2, $locale);

		if ($actual !== 0)
		{
			$actual /= abs($actual);
		}

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Data provider for testStrCmp
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestStrCmp(): \Generator
	{
		yield 'A < a with default locale' => ['THIS IS STRING1', 'this is string1', false, -1];
		yield 'a < b with default locale' => ['this is string1', 'this is string2', false, -1];
		yield 'b > a with default locale' => ['this is string2', 'this is string1', false, 1];
		yield 'a > B with default locale' => ['a', 'B', false, 1];
		yield 'A < b with default locale' => ['A', 'b', false, -1];
		yield 'À > a with french locale' => ['Àbc', 'abc', self::FRENCH_LOCALE, 1];
		yield 'À < b with french locale' => ['Àbc', 'bcd', self::FRENCH_LOCALE, -1];
		yield 'É < è with french locale' => ['É', 'è', self::FRENCH_LOCALE, -1];
		yield 'é < È with french locale' => ['é', 'È', self::FRENCH_LOCALE, -1];
		yield 'Œ < p with french locale' => ['Œ', 'p', self::FRENCH_LOCALE, -1];
		yield 'Œ > n with french locale' => ['Œ', 'n', self::FRENCH_LOCALE, 1];
		yield 'œ > N with french locale' => ['œ', 'N', self::FRENCH_LOCALE, 1];
		yield 'œ < P with french locale' => ['œ', 'P', self::FRENCH_LOCALE, -1];
		yield 'cyrillic р < т with russian locale' => ['р', 'т', self::RUSSIAN_WIN_LOCALE, -1];
	}

	/**
	 * @testdox       StringHelper::strcmp() compares $_dataName
	 *
	 * @param   string           $string1   String 1 to compare
	 * @param   string           $string2   String 2 to compare
	 * @param   string[]|string  $locale    The locale used by strcoll or false to use classical comparison
	 * @param   integer          $expected  Expected result
	 *
	 * @dataProvider  seedTestStrCmp
	 */
	public function testStrCmp(string $string1, string $string2, $locale, int $expected): void
	{
		if ($locale !== false && strpos(php_uname(), 'Darwin') === 0)
		{
			$this->markTestSkipped('Darwin bug prevents foreign conversion from working properly');
		}

		if ($locale !== false && setlocale(LC_COLLATE, $locale) === false)
		{
			$this->markTestSkipped(
				sprintf(
					"Locale %s is not available.",
					implode(', ', (array)$locale)
				)
			);
		}

		$actual = StringHelper::strcmp($string1, $string2, $locale);

		if ($actual !== 0)
		{
			$actual /= abs($actual);
		}

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Data provider for testStrCSpn
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestStrCSpn(): \Generator
	{
		// Note: haystack, needles, start, len, expected result
		yield 'an ASCII string' => ['subject <a> string <a>', '<>', null, null, 8];
		yield 'an ASCII string given a start position' => ['subject <a> string <a>', '<>', 1, null, 7];
		yield 'a cyrillic string' => ['Би шил {123} идэй {456} чадна', '}{', null, null, 7];
		yield 'a limited substring' => ['Би шил {123} идэй {456} чадна', '}{', 13, 10, 5];
	}

	/**
	 * @testdox       StringHelper::strcspn() finds length of non-matching segment in $_dataName
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
	 * Data provider for testStrIStr
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestStrIStr(): \Generator
	{
		yield 'non-matching needle' => ['haystack', 'needle', false];
		yield 'match case needle' => ['before match, after match', 'match', 'match, after match'];
		yield 'non-match case needle' => ['before match, after match', 'MATCH', 'match, after match'];
		yield 'cyrillic strings' => ['Би шил идэй чадна', 'шил', 'шил идэй чадна'];
	}

	/**
	 * @testdox       StringHelper::stristr() finds the first occurrence of a string
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
	 * Data provider for testStrRev
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestStrRev(): \Generator
	{
		yield 'an ASCII string' => ['abc def', 'fed cba'];
		yield 'a cyrillic string' => ['Би шил', 'лиш иБ'];
	}

	/**
	 * @testdox       StringHelper::strrev() reverses $_dataName
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
	 * Data provider for testStrSpn
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestStrSpn(): \Generator
	{
		// Note: subject, mask, start, length, expected result
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
	 * @testdox       StringHelper::strspn() finds length of matching segment
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
	 * Data provider for testSubstrReplace
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestSubstrReplace(): \Generator
	{
		yield 'the remainder of the string, if no length is given' => [
			'321 Main Street',
			'Broadway Avenue',
			4,
			false,
			'321 Broadway Avenue'
		];
		yield 'only the given number of characters' => ['321 Main Street', 'Broadway', 4, 4, '321 Broadway Street'];
		yield 'the given number of characters in a cyrillic string' => [
			'чадна Би шил идэй чадна',
			'我能吞',
			6,
			2,
			'чадна 我能吞 шил идэй чадна'
		];
		yield 'the remainder of a cyrillic string, if no length is given' => [
			'чадна Би шил идэй чадна',
			'我能吞',
			6,
			false,
			'чадна 我能吞'
		];
	}

	/**
	 * @testdox       StringHelper::substr_replace() replaces $_dataName
	 *
	 * @param   string                $string       The haystack
	 * @param   string                $replacement  The replacement string
	 * @param   integer               $start        Start
	 * @param   integer|boolean|null  $length       Length (optional)
	 * @param   string                $expected     Expected result
	 *
	 * @dataProvider  seedTestSubstrReplace
	 */
	public function testSubstrReplace(string $string, string $replacement, int $start, $length, string $expected): void
	{
		$this->assertEquals(
			$expected,
			StringHelper::substr_replace($string, $replacement, $start, $length)
		);
	}

	/**
	 * Data provider for testLTrim
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestLTrim(): \Generator
	{
		yield 'spaces with default char list' => ['   abc def', false, 'abc def'];
		yield 'nothing with empty char list' => ['   abc def', '', '   abc def'];
		yield 'spaces with default char list on cyrillic strings' => [' Би шил', false, 'Би шил'];
		yield 'HTAB, VTAB, NL, CR with default char list' => ["\t\n\r\x0BБи шил", false, 'Би шил'];
		yield 'special characters from provided char list' => ["\x0B\t\n\rБи шил", "\t\n\x0B", "\rБи шил"];
		yield 'characters only on the left side' => ["\x09Би шил\x0A", "\x09\x0A", "Би шил\x0A"];
		yield 'normal characters from provided char list' => ['1234abc', '0123456789', 'abc'];
	}

	/**
	 * @testdox       StringHelper::ltrim() removes $_dataName
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
	 * Data provider for testRTrim
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestRTrim(): \Generator
	{
		yield 'spaces with default char list' => ['abc def   ', false, 'abc def'];
		yield 'nothing with empty char list' => ['abc def   ', '', 'abc def   '];
		yield 'spaces with default char list on cyrillic strings' => ['Би шил ', false, 'Би шил'];
		yield 'HTAB, VTAB, NL, CR with default char list' => ["Би шил\t\n\r\x0B", false, 'Би шил'];
		yield 'special characters from provided char list' => ["Би шил\r\x0B\t\n", "\t\n\x0B", "Би шил\r"];
		yield 'characters only on the right side' => ["\x09Би шил\x0A", "\x09\x0A", "\x09Би шил"];
		yield 'normal characters from provided char list' => ['1234abc', 'abcdefgh', '1234'];
	}

	/**
	 * @testdox       StringHelper::rtrim() removes $_dataName
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
	 * Data provider for testTrim
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestTrim(): \Generator
	{
		yield 'spaces with default char list' => ['  abc def   ', false, 'abc def'];
		yield 'nothing with empty char list' => ['  abc def   ', '', '  abc def   '];
		yield 'spaces with default char list on cyrillic strings' => ['   Би шил ', false, 'Би шил'];
		yield 'HTAB, VTAB, NL, CR with default char list' => ["\t\n\r\x0BБи шил\t\n\r\x0B", false, 'Би шил'];
		yield 'special characters from provided char list' => ["\x0B\t\n\rБи шил\r\x0B\t\n", "\t\n\x0B", "\rБи шил\r"];
		yield 'characters on both sides' => ["\x09Би шил\x0A", "\x09\x0A", "Би шил"];
		yield 'normal characters from provided char list' => ['1234abc56789', '0123456789', 'abc'];
	}

	/**
	 * @testdox       StringHelper::trim() removes $_dataName
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
	 * Data provider for testUcfirst
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestUcfirst(): \Generator
	{
		// Note: string, delimiter, newDelimiter expected result
		yield 'only the first character by default' => ['george michael', null, null, 'George michael'];
		yield 'the first character of a cyrillic string' => ['мога', null, null, 'Мога'];
		yield 'the first character of a greek string' => ['ψυχοφθόρα', null, null, 'Ψυχοφθόρα'];
		yield 'the first character of every chunk, if a delimiter is provided' => [
			'dr jekill and mister hyde',
			' ',
			null,
			'Dr Jekill And Mister Hyde'
		];
		yield 'the chunks and optionally replaces the delimiter' => [
			'dr jekill and mister hyde',
			' ',
			'_',
			'Dr_Jekill_And_Mister_Hyde'
		];
	}

	/**
	 * @testdox       StringHelper::ucfirst() uppercases $_dataName
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
	 * Data provider for testUcwords
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestUcwords(): \Generator
	{
		yield 'each word' => ['george washington', 'George Washington'];
		yield 'words at the beginning of a new line' => ["george\r\nwashington", "George\r\nWashington"];
		yield 'cyrillic words' => ['мога', 'Мога'];
		yield 'greek words' => ['αβγ δεζ', 'Αβγ Δεζ'];
		yield 'words with Umlauts' => ['åbc öde', 'Åbc Öde'];
	}

	/**
	 * @testdox       StringHelper::ucwords() uppercases $_dataName
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
	 * Data provider for testTranscode
	 *
	 * @return  \Generator
	 *
	 * @since   1.0
	 */
	public function seedTestTranscode(): \Generator
	{
		yield 'UTF-8 to ISO-8859-1' => ['Åbc Öde €100', 'UTF-8', 'ISO-8859-1', "\xc5bc \xd6de EUR100"];
	}

	/**
	 * @testdox       StringHelper::transcode transcodes $_dataName
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
	 * Data provider for testUnicodeToUtf8
	 *
	 * @return  \Generator
	 *
	 * @since   1.2.0
	 */
	public function seedTestUnicodeToUtf8(): \Generator
	{
		yield 'cyrillic' => ["\u0422\u0435\u0441\u0442 \u0441\u0438\u0441\u0442\u0435\u043c\u044b", "Тест системы"];
		yield 'umlaut' => ["\u00dcberpr\u00fcfung der Systemumstellung", "Überprüfung der Systemumstellung"];
	}

	/**
	 * @testdox       StringHelper::unicode_to_utf8() converts a $_dataName string from unicode to UTF-8
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
	 * Data provider for testUnicodeToUtf16
	 *
	 * @return  \Generator
	 *
	 * @since   1.2.0
	 */
	public function seedTestUnicodeToUtf16(): \Generator
	{
		yield 'cyrillic' => ["\u0422\u0435\u0441\u0442 \u0441\u0438\u0441\u0442\u0435\u043c\u044b", "Тест системы"];
		yield 'umlaut' => ["\u00dcberpr\u00fcfung der Systemumstellung", "Überprüfung der Systemumstellung"];
	}

	/**
	 * @testdox       StringHelper::unicode_to_utf16() converts a $_dataName string from unicode to UTF-16
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
	 * @testdox       StringHelper::compliant() detects UTF-8 compliant strings
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

	/**
	 * @testdox       StringHelper::valid() validates UTF-8 strings
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
}
