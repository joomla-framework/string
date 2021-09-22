<?php
/**
 * Part of the Joomla Framework String Package
 *
 * @copyright    Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
 * @license      GNU General Public License version 2 or later; see LICENSE
 *
 * @noinspection SpellCheckingInspection
 * @noinspection PhpMissingReturnTypeInspection
 * @noinspection ReturnTypeCanBeDeclaredInspection
 * @noinspection PhpMissingParamTypeInspection
 */

namespace Joomla\String;

use voku\helper\UTF8;

@ini_set('default_charset', 'UTF-8');

/**
 * String handling class for UTF-8 data wrapping the Portable UTF-8 library.
 * All functions assume the validity of UTF-8 strings.
 *
 * @since  1.3.0
 */
abstract class StringHelper
{
	/**
	 * Increment styles.
	 *
	 * @var    array
	 * @since  1.3.0
	 */
	protected static $incrementStyles = [
		'dash'    => [
			'regexp' => '#-(\d+)$#',
			'printf' => '-%d',
		],
		'default' => [
			'regexp' => ['#\((\d+)\)$#', '#\(\d+\)$#'],
			'printf' => [' (%d)', '(%d)'],
		],
	];

	/**
	 * @var false|string
	 */
	private static $currentLocale;

	/**
	 * Increment a trailing number in a string.
	 *
	 * Used to easily create distinct labels when copying objects. The method has the following styles:
	 *
	 * default: "Label" becomes "Label (2)"
	 * dash:    "Label" becomes "Label-2"
	 *
	 * @param   string        $string  The source string.
	 * @param   string|null   $style   The style (default|dash).
	 * @param   integer|null  $n       If a positive number is supplied, this number is used for the copy, otherwise it is the 'next' number.
	 *
	 * @return  string  The incremented string.
	 *
	 * @since   1.3.0
	 */
	public static function increment($string, $style = 'default', $n = null)
	{
		$styleSpec = static::$incrementStyles[$style] ?? static::$incrementStyles['default'];

		// Regular expression search and replace patterns.
		[$rxSearch, $rxReplace] = self::splitSearchReplace($styleSpec['regexp']);

		// New and old (existing) sprintf formats.
		[$newFormat, $oldFormat] = self::splitSearchReplace($styleSpec['printf']);

		// Check if we are incrementing an existing pattern, or appending a new one.
		if (preg_match($rxSearch, $string, $matches))
		{
			return preg_replace($rxReplace, sprintf($oldFormat, $n ?: ($matches[1] + 1)), $string);
		}

		$string .= sprintf($newFormat, $n ?: 2);

		return $string;
	}

	/**
	 * Check if a string is 7 bit ASCII.
	 *
	 * You might use this to conditionally check whether a string needs handling as UTF-8 or not, potentially offering performance
	 * benefits by using the native PHP equivalent if it's just ASCII e.g.;
	 *
	 * <code>
	 * if (StringHelper::is_ascii($someString))
	 * {
	 *     // It's just ASCII - use the native PHP version
	 *     $someString = strtolower($someString);
	 * }
	 * else
	 * {
	 *     $someString = StringHelper::strtolower($someString);
	 * }
	 * </code>
	 *
	 * @param   string  $str  TThe string to check.
	 *
	 * @return  boolean true if it is ASCII, false otherwise
	 *
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::is_ascii() instead.
	 * @deprecated 3.0 Please use UTF8::is_ascii() instead.
	 */
	public static function is_ascii($str)
	{
		return UTF8::is_ascii($str);
	}

	/**
	 * Calculate Unicode code point of the given UTF-8 encoded character.
	 *
	 * @param   string  $chr  The character of which to calculate code point.
	 *
	 * @return  integer Unicode code point of the given character, 0 on invalid UTF-8 byte sequence
	 *
	 * @link       https://www.php.net/ord
	 * @since      1.4.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::ord() instead.
	 * @deprecated 3.0 Please use UTF8::ord() instead.
	 */
	public static function ord($chr)
	{
		return UTF8::ord($chr);
	}

	/**
	 * Find the position of the first occurrence of a substring in a string.
	 *
	 * @param   string          $haystack  The string from which to get the position of the first occurrence of needle.
	 * @param   integer|string  $needle    The string to find in haystack, or a code point as int.
	 * @param   integer|null    $offset    [optional] The search offset. If it is not specified, 0 is used.
	 *
	 * @return  integer|boolean  The numeric position of the first occurrence of needle in the haystack string.
	 *                           If needle is not found it returns false.
	 *
	 * @link       https://www.php.net/strpos
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::strpos() instead.
	 * @deprecated 3.0 Please use UTF8::strpos() instead.
	 */
	public static function strpos($haystack, $needle, $offset = null)
	{
		return UTF8::strpos($haystack, $needle, $offset ?? 0);
	}

	/**
	 * Find the position of the last occurrence of a substring in a string.
	 *
	 * @param   string          $haystack  The string being checked for the last occurrence of needle.
	 * @param   integer|string  $needle    The string to find in haystack or a code point as int.
	 * @param   integer         $offset    [optional] Can be specified to start the search after the given number of characters in
	 *                                     the string. Negative values stop the search at the given point before the end
	 *                                     of the string.
	 *
	 * @return  integer|boolean  The numeric position of the last occurrence of needle in the haystack string.
	 *                           If needle is not found, it returns false.
	 *
	 * @link       https://www.php.net/strrpos
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::strrpos() instead.
	 * @deprecated 3.0 Please use UTF8::strrpos() instead.
	 */
	public static function strrpos($haystack, $needle, $offset = null)
	{
		return UTF8::strrpos($haystack, $needle, $offset ?? 0);
	}

	/**
	 * Get part of a string.
	 *
	 * @param   string        $str     The string being checked.
	 * @param   integer       $offset  The first position used in str.
	 * @param   integer|null  $length  [optional] The maximum length of the returned string.
	 *
	 * @return  string|boolean The portion of str specified by the offset and length parameters.
	 *                         If str is shorter than offset characters, false will be returned.
	 *
	 * @link       https://www.php.net/substr
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::substr() instead.
	 * @deprecated 3.0 Please use UTF8::substr() instead.
	 */
	public static function substr($str, $offset, $length = null)
	{
		return UTF8::substr($str, $offset, $length);
	}

	/**
	 * Make a string lowercase.
	 *
	 * Note: The concept of a characters "case" only exists is some alphabets such as Latin, Greek, Cyrillic, Armenian and archaic Georgian - it does
	 * not exist in the Chinese alphabet, for example. See Unicode Standard Annex #21: Case Mappings
	 *
	 * @param   string  $str  The string being lowercased.
	 *
	 * @return  string  String with all alphabetic characters converted to lowercase.
	 *
	 * @link       https://www.php.net/strtolower
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::strtolower() instead.
	 * @since      __DEPLOY_VERSION__ str is always cast to string.
	 * @deprecated 3.0 Please use UTF8::strtolower() instead.
	 */
	public static function strtolower($str)
	{
		return UTF8::strtolower($str);
	}

	/**
	 * Make a string uppercase.
	 *
	 * Note: The concept of a characters "case" only exists is some alphabets such as Latin, Greek, Cyrillic, Armenian and archaic Georgian - it does
	 * not exist in the Chinese alphabet, for example. See Unicode Standard Annex #21: Case Mappings
	 *
	 * @param   string  $str  The string being uppercased.
	 *
	 * @return  string  String with all alphabetic characters converted to uppercase.
	 *
	 * @link       https://www.php.net/strtoupper
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::strtoupper() instead.
	 * @since      __DEPLOY_VERSION__ str is always cast to string.
	 * @deprecated 3.0 Please use UTF8::strtoupper() instead.
	 */
	public static function strtoupper($str)
	{
		return UTF8::strtoupper($str);
	}

	/**
	 * Get the string length, not the byte-length!
	 *
	 * @param   string  $str  The string being checked for length.
	 *
	 * @return  integer|false  The number of characters in the string or false, if mbstring is not installed and invalid
	 *                         characters are encountered.
	 *
	 * @link       https://www.php.net/strlen
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::strlen() instead.
	 * @since      __DEPLOY_VERSION__ Returns false, if mbstring is not installed and invalid characters are encountered.
	 * @deprecated 3.0 Please use UTF8::strlen() instead.
	 */
	public static function strlen($str)
	{
		return UTF8::strlen($str);
	}

	/**
	 * Case-insensitive and UTF-8 safe version of str_replace().
	 *
	 * @param   string[]|string  $search   String(s) to search
	 *                                     Every replacement with search array is
	 *                                     performed on the result of previous replacement.
	 * @param   string[]|string  $replace  The replacement.
	 * @param   string[]|string  $subject  If subject is an array, then the search and
	 *                                     replace is performed with every entry of
	 *                                     subject, and the return value is an array as
	 *                                     well.
	 * @param   integer|null     $count    [optional] The number of matched and replaced needles will
	 *                                     be returned in count which is passed by
	 *                                     reference.
	 *
	 * @return  string[]|string  A string or an array of strings with applied replacements.
	 *
	 * @link       https://www.php.net/str_ireplace
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::str_ireplace() instead.
	 * @since      __DEPLOY_VERSION__ Accepts an array as subject.
	 * @deprecated 3.0 Please use UTF8::str_ireplace() instead.
	 */
	public static function str_ireplace($search, $replace, $subject, &$count = null)
	{
		return UTF8::str_ireplace($search, $replace, $subject, $count);
	}

	/**
	 * Pad a string to a certain length with another string.
	 *
	 * @param   string   $input   The input string.
	 * @param   integer  $length  The length of return string. If the value is negative, less than, or equal to the
	 *                            length of the input string, no padding takes place.
	 * @param   string   $padStr  [optional] String to use for padding the input string. The string may be truncated if the number
	 *                            of padding characters can't be evenly divided by the string's length.
	 * @param   integer  $type    [optional] The type of padding to apply. Can be STR_PAD_RIGHT, STR_PAD_LEFT or
	 *                            STR_PAD_BOTH.
	 *
	 * @return  string The padded string.
	 *
	 * @link       https://www.php.net/str_pad
	 * @since      1.4.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::str_pad() instead.
	 * @deprecated 3.0 Please use UTF8::str_pad() instead.
	 */
	public static function str_pad($input, $length, $padStr = ' ', $type = STR_PAD_RIGHT)
	{
		return UTF8::str_pad($input, $length, $padStr, $type);
	}

	/**
	 * Convert a string to an array of unicode characters.
	 *
	 * @param   string   $str       The string to split into an array.
	 * @param   integer  $splitLen  [optional] Max character length of each array element.
	 *
	 * @return  array An array containing chunks of chars from the input.
	 *
	 * @link       https://www.php.net/str_split
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::str_split() instead.
	 * @deprecated 3.0 Please use UTF8::str_split() instead.
	 */
	public static function str_split($str, $splitLen = 1)
	{
		return UTF8::str_split($str, $splitLen);
	}

	/**
	 * Case-insensitive string comparison.
	 *
	 * If no locale is provided, this method is an alias for UTF8::strcasecmp().
	 * If a locale is provided, that locale is set, if possible, and used for comparison with strcoll().
	 *
	 * @param   string           $str1    The first string.
	 * @param   string           $str2    The second string.
	 * @param   string[]|string  $locale  [optional] A locale for collation aware comparison.
	 *                                    See setlocale() for valid values.
	 *
	 * @return  integer   < 0 if str1 is less than str2, > 0 if str1 is greater than str2, 0 if they are equal.
	 *
	 * @link       https://www.php.net/strcasecmp
	 * @link       https://www.php.net/strcoll
	 * @link       https://www.php.net/setlocale
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Restores locale after comparision.
	 */
	public static function strcasecmp($str1, $str2, $locale = null)
	{
		if (empty($locale))
		{
			return UTF8::strcasecmp($str1, $str2);
		}

		$encoding = self::setLocale($locale);

		// If we successfully set encoding it to utf-8 or encoding is sth weird don't recode
		if ($encoding === 'UTF-8' || $encoding === 'nonrecodable')
		{
			$result = strcoll(UTF8::strtolower($str1), UTF8::strtolower($str2));
		}
		else
		{
			$result = strcoll(
				static::transcode(UTF8::strtolower($str1), 'UTF-8', $encoding),
				static::transcode(UTF8::strtolower($str2), 'UTF-8', $encoding)
			);
		}

		self::restoreLocale();

		return $result;
	}

	/**
	 * Case-sensitive string comparison.
	 *
	 * If no locale is provided, this method is an alias for UTF8::strcmp().
	 * If a locale is provided, that locale is set, if possible, and used for comparison with strcoll().
	 *
	 * @param   string           $str1    The first string.
	 * @param   string           $str2    The second string.
	 * @param   string[]|string  $locale  [optional] A locale for collation aware comparison.
	 *                                    See setlocale() for valid values.
	 *
	 * @return  integer   < 0 if str1 is less than str2, > 0 if str1 is greater than str2, 0 if they are equal.
	 *
	 * @link       https://www.php.net/strcmp
	 * @link       https://www.php.net/strcoll
	 * @link       https://www.php.net/setlocale
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Restores locale after comparision.
	 */
	public static function strcmp($str1, $str2, $locale = false)
	{
		if ($locale === false)
		{
			return UTF8::strcmp($str1, $str2);
		}

		$encoding = self::setLocale($locale);

		// If we successfully set encoding it to utf-8 or encoding is sth weird don't recode
		if ($encoding === 'UTF-8' || $encoding === 'nonrecodable')
		{
			$result = strcoll($str1, $str2);
		}
		else
		{
			$result = strcoll(
				static::transcode($str1, 'UTF-8', $encoding),
				static::transcode($str2, 'UTF-8', $encoding)
			);
		}

		self::restoreLocale();

		return $result;
	}

	/**
	 * Find length of initial segment not matching mask.
	 *
	 * @param   string   $str     The string to process
	 * @param   string   $mask    The mask
	 * @param   integer  $offset  [optional] Starting character position (in characters)
	 * @param   integer  $length  [optional] Length
	 *
	 * @return  integer  The length of the initial segment of str1 which does not contain any of the characters in str2
	 *
	 * @link       https://www.php.net/strcspn
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::strcspn() instead.
	 * @deprecated 3.0 Please use UTF8::strcspn() instead.
	 */
	public static function strcspn($str, $mask, $offset = null, $length = null)
	{
		if ($length === null)
		{
			if ($offset === null)
			{
				return UTF8::strcspn($str, $mask);
			}

			return UTF8::strcspn($str, $mask, $offset);
		}

		return UTF8::strcspn($str, $mask, $offset, $length);
	}

	/**
	 * Get everything from haystack from the first occurrence of needle to the end.
	 *
	 * Needle and haystack are examined in a case-insensitive manner to find the first occurrence of a string using
	 * case-insensitive comparison.
	 *
	 * @param   string  $haystack  The input string. Must be valid UTF-8.
	 * @param   string  $needle    The string to look for. Must be valid UTF-8.
	 *
	 * @return  string|false A sub-string, or false if needle is not found.
	 *
	 * @link       https://www.php.net/stristr
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::stristr() instead.
	 * @deprecated 3.0 Please use UTF8::stristr() instead.
	 */
	public static function stristr($haystack, $needle)
	{
		return UTF8::stristr($haystack, $needle);
	}

	/**
	 * Reverse characters order in the string.
	 *
	 * @param   string  $str  String to be reversed
	 *
	 * @return  string   The string with characters in the reverse sequence.
	 *
	 * @link       https://www.php.net/strrev
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::strrev() instead.
	 * @deprecated 3.0 Please use UTF8::strrev() instead.
	 */
	public static function strrev($str)
	{
		return UTF8::strrev($str);
	}

	/**
	 * Find the length of the initial segment of a string consisting entirely of characters contained within a given mask.
	 *
	 * @param   string   $str     The input string.
	 * @param   string   $mask    The mask of chars
	 * @param   integer  $offset  [optional] Start
	 * @param   integer  $length  [optional] Length
	 *
	 * @return  integer
	 *
	 * @link       https://www.php.net/strspn
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::strspn() instead.
	 * @deprecated 3.0 Please use UTF8::strspn() instead.
	 */
	public static function strspn($str, $mask, $offset = null, $length = null)
	{
		if ($length === null)
		{
			if ($offset === null)
			{
				return UTF8::strspn($str, $mask);
			}

			return UTF8::strspn($str, $mask, $offset);
		}

		return UTF8::strspn($str, $mask, $offset ?? 0, $length);
	}

	/**
	 * Replace text within a portion of a string.
	 *
	 * @param   string[]|string         $str          The input string or an array of stings.
	 * @param   string[]|string         $replacement  The replacement string or an array of stings.
	 * @param   integer[]|integer       $offset       If offset is positive, the replacing will begin at the start'th character
	 *                                                of the string.
	 *                                                If offset is negative, the replacing will begin at the start'th character
	 *                                                from the end of string.
	 * @param   integer[]|integer|null  $length       [optional] If given and is positive, it represents the length of the
	 *                                                portion of string which is to be replaced. If it is negative, it
	 *                                                represents the number of characters from the end of string at which to
	 *                                                stop replacing. If it is not given, then it will default to
	 *                                                strlen(string); i.e. end the replacing at the end of string.
	 *                                                Of course, if length is zero then this function will have the effect
	 *                                                of inserting replacement into string at the given start offset.
	 *
	 * @return  string The result string. If string is an array then an array is returned.
	 *
	 * @link       https://www.php.net/substr_replace
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::substr_replace() instead.
	 * @deprecated 3.0 Please use UTF8::substr_replace() instead.
	 */
	public static function substr_replace($str, $replacement, $offset, $length = null)
	{
		if ($length === false)
		{
			$length = null;
		}

		return UTF8::substr_replace($str, $replacement, $offset, $length);
	}

	/**
	 * Strip whitespace or other characters from the beginning of a string.
	 *
	 * You only need to use this if you are supplying the char list optional arg, and it contains UTF-8 characters.
	 * Otherwise, ltrim will work normally on a UTF-8 string.
	 *
	 * @param   string  $str    The string to be trimmed.
	 * @param   string  $chars  [optional] Characters to be stripped.
	 *
	 * @return  string  The string with unwanted characters stripped from the left
	 *
	 * @link       https://www.php.net/ltrim
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::ltrim() instead.
	 * @deprecated 3.0 Please use UTF8::ltrim() instead.
	 */
	public static function ltrim($str, $chars = false)
	{
		if ($chars === '')
		{
			return $str;
		}

		if ($chars === false)
		{
			return UTF8::ltrim($str);
		}

		return UTF8::ltrim($str, $chars);
	}

	/**
	 * Strip whitespace or other characters from the end of a string.
	 *
	 * You only need to use this if you are supplying the char list optional arg, and it contains UTF-8 characters.
	 * Otherwise, rtrim will work normally on a UTF-8 string.
	 *
	 * @param   string  $str    The string to be trimmed.
	 * @param   string  $chars  [optional] Characters to be stripped.
	 *
	 * @return  string  The string with unwanted characters stripped from the right.
	 *
	 * @link       https://www.php.net/rtrim
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::rtrim() instead.
	 * @deprecated 3.0 Please use UTF8::rtrim() instead.
	 */
	public static function rtrim($str, $chars = false)
	{
		if ($chars === '')
		{
			return $str;
		}

		if ($chars === false)
		{
			return UTF8::rtrim($str);
		}

		return UTF8::rtrim($str, $chars);
	}

	/**
	 * Strip whitespace or other characters from the beginning and end of a string.
	 *
	 * You only need to use this if you are supplying the charlist optional arg, and it contains UTF-8 characters.
	 * Otherwise, trim will work normally on a UTF-8 string
	 *
	 * @param   string  $str    The string to be trimmed.
	 * @param   string  $chars  [optional] Characters to be stripped.
	 *
	 * @return  string  The string with unwanted characters stripped from both ends.
	 *
	 * @link       https://www.php.net/trim
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::trim() instead.
	 * @deprecated 3.0 Please use UTF8::trim() instead.
	 */
	public static function trim($str, $chars = false)
	{
		if ($chars === '')
		{
			return $str;
		}

		if ($chars === false)
		{
			return UTF8::trim($str);
		}

		return UTF8::trim($str, $chars);
	}

	/**
	 * Make a string's first character uppercase or all words' first character uppercase.
	 *
	 * @param   string  $str           String to be processed
	 * @param   string  $delimiter     [optional] The words' delimiter (omitting means do not split the string)
	 * @param   string  $newDelimiter  [optional] The new delimiter (omitting means equal to $delimiter)
	 *
	 * @return  string  If $delimiter is omitted, return the string with first character as upper case (if applicable)
	 *                  else consider the string of words separated by the delimiter, apply the ucfirst to each word
	 *                  and return the string with the new delimiter
	 *
	 * @link       https://www.php.net/ucfirst
	 * @since      1.3.0
	 * @deprecated 3.0 Please use UTF8::ucfirst() instead. To reproduce the delimiter splitting and re-joining, use explode() and implode().
	 */
	public static function ucfirst($str, $delimiter = null, $newDelimiter = null)
	{
		if ($delimiter === null)
		{
			return UTF8::ucfirst($str);
		}

		if ($newDelimiter === null)
		{
			$newDelimiter = $delimiter;
		}

		return implode($newDelimiter, array_map([UTF8::class, 'ucfirst'], explode($delimiter, $str)));
	}

	/**
	 * Uppercase the first character of each word in a string.
	 *
	 * @param   string  $str  The input string.
	 *
	 * @return  string  String with first char of each word uppercase
	 *
	 * @link       https://www.php.net/ucwords
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::ucwords() instead.
	 * @deprecated 3.0 Please use UTF8::ucwords() instead.
	 */
	public static function ucwords($str)
	{
		return UTF8::ucwords($str);
	}

	/**
	 * Transcode a string.
	 *
	 * @param   string  $source        The string to transcode.
	 * @param   string  $fromEncoding  The source encoding.
	 * @param   string  $toEncoding    The target encoding.
	 *
	 * @return  string|false  The converted string, or false on failure.
	 *
	 * @link       https://bugs.php.net/bug.php?id=48147
	 * @see        UTF8::to_iso8859()
	 * @see        UTF8::to_utf8()
	 * @since      1.3.0
	 */
	public static function transcode($source, $fromEncoding, $toEncoding)
	{
		$modifier = ICONV_IMPL === 'glibc' ? '//TRANSLIT,IGNORE' : '//IGNORE//TRANSLIT';

		return @iconv($fromEncoding, $toEncoding . $modifier, $source);
	}

	/**
	 * Check whether the passed input contains only byte sequences that appear valid UTF-8.
	 *
	 * @param   string  $str  The input to be checked.
	 *
	 * @return  boolean  true if valid
	 *
	 * @see        self::compliant
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::is_utf8() instead.
	 * @deprecated 3.0 Please use UTF8::is_utf8() instead.
	 */
	public static function valid($str)
	{
		return UTF8::is_utf8($str);
	}

	/**
	 * Check whether the passed input contains only byte sequences that appear valid UTF-8.
	 *
	 * @param   string  $str  The input to be checked.
	 *
	 * @return  boolean  true if valid
	 *
	 * @see        self::valid
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::is_utf8() instead.
	 * @deprecated 3.0 Please use UTF8::is_utf8() instead.
	 */
	public static function compliant($str)
	{
		return UTF8::is_utf8($str);
	}

	/**
	 * Convert UTF-8 sequence to UTF-8 string.
	 *
	 * @param   string  $str  Unicode string to convert
	 *
	 * @return  string  UTF-8 string
	 *
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::to_utf8_string() instead.
	 * @deprecated 3.0 Please use UTF8::to_utf8_string() instead.
	 */
	public static function unicode_to_utf8($str)
	{
		return UTF8::to_utf8_string($str);
	}

	/**
	 * Convert UTF-16 sequence to UTF-8 string.
	 *
	 * @param   string  $str  Unicode string to convert
	 *
	 * @return  string  UTF-16 string
	 *
	 * @since      1.3.0
	 * @since      __DEPLOY_VERSION__ Deprecated. Use UTF8::to_utf8_string() instead.
	 * @deprecated 3.0 Please use UTF8::to_utf8_string() instead.
	 */
	public static function unicode_to_utf16($str)
	{
		return UTF8::to_utf8_string($str);
	}

	/**
	 * @param   string[]|string  $value  The value
	 *
	 * @return array
	 */
	private static function splitSearchReplace($value): array
	{
		if (\is_array($value))
		{
			[$one, $two] = $value;
		}
		else
		{
			$one = $two = $value;
		}

		return array($one, $two);
	}

	/**
	 * @param   string[]|string  $locale  The locale(s)
	 *
	 * @return string The encoding
	 */
	private static function setLocale($locale): string
	{
		self::$currentLocale = setlocale(LC_COLLATE, 0);
		$locale              = setlocale(LC_COLLATE, $locale);

		if ($locale === false)
		{
			$locale = (string) self::$currentLocale;
		}

		// See if we have successfully set locale to UTF-8
		if (stripos($locale, 'UTF-8') === false
			&& strpos($locale, '_') !== false
			&& preg_match('~(?:\.|cp)(\d+)$~i', $locale, $m)
		)
		{
			$encoding = 'CP' . $m[1];
		}
		elseif (stripos($locale, 'UTF-8') !== false || stripos($locale, 'utf8') !== false)
		{
			$encoding = 'UTF-8';
		}
		else
		{
			$encoding = 'nonrecodable';
		}

		return $encoding;
	}

	/**
	 * @return void
	 */
	private static function restoreLocale(): void
	{
		setlocale(LC_COLLATE, self::$currentLocale);
	}
}
