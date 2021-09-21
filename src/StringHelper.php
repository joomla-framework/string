<?php
/**
 * Part of the Joomla Framework String Package
 *
 * @copyright  Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\String;

@ini_set('default_charset', 'UTF-8');

/**
 * String handling class for UTF-8 data wrapping the phputf8 library. All functions assume the validity of UTF-8 strings.
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
	 * Test whether a string contains only 7bit ASCII bytes.
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
	 * @param   string  $str  The string to test.
	 *
	 * @return  boolean True if the string is all ASCII
	 *
	 * @since   1.3.0
	 */
	public static function is_ascii($str)
	{
		return utf8_is_ascii($str);
	}

	/**
	 * Convert the first byte of a string to its ordinal number
	 *
	 * UTF-8 aware alternative to ord()
	 *
	 * @param   string  $chr  UTF-8 encoded character
	 *
	 * @return  integer Unicode ordinal for the character
	 *
	 * @link    https://www.php.net/ord
	 * @since   1.4.0
	 */
	public static function ord($chr)
	{
		return utf8_ord($chr);
	}

	/**
	 * Find the position of the first occurrence of a substring in a string
	 *
	 * UTF-8 aware alternative to strpos()
	 *
	 * @param   string        $haystack  The string to search in
	 * @param   string        $needle    String being searched for
	 * @param   integer|null  $offset    If specified, search will start this number of characters counted from the
	 *                                   beginning of the string. Unlike {@see strrpos()}, the offset cannot be negative.
	 *
	 * @return  integer|boolean  Returns the position where the needle exists relative to the beginnning of the haystack
	 *                           string (independent of search direction or offset). Also note that string positions
	 *                           start at 0, and not 1.
	 *                           Returns false if the needle was not found.
	 *
	 * @link    https://www.php.net/strpos
	 * @since   1.3.0
	 */
	public static function strpos($haystack, $needle, $offset = null)
	{
		if ($offset === null)
		{
			return utf8_strpos($haystack, $needle);
		}

		return utf8_strpos($haystack, $needle, $offset);
	}

	/**
	 * Find the position of the last occurrence of a substring in a string
	 *
	 * UTF-8 aware alternative to strrpos()
	 *
	 * @param   string   $haystack  The string to search in.
	 * @param   string   $needle    String being searched for.
	 * @param   integer  $offset    If specified, search will start this number of characters counted from the beginning
	 *                              of the string. If the value is negative, search will instead start from that many
	 *                              characters from the end of the string, searching backwards.
	 *
	 * @return  integer|boolean  Returns the position where the needle exists relative to the beginnning of the haystack
	 *                           string (independent of search direction or offset). Also note that string positions
	 *                           start at 0, and not 1.
	 *                           Returns false if the needle was not found.
	 *
	 * @link    https://www.php.net/strrpos
	 * @since   1.3.0
	 */
	public static function strrpos($haystack, $needle, $offset = 0)
	{
		return utf8_strrpos($haystack, $needle, $offset);
	}

	/**
	 * Get part of a string given character offset (and optionally length).
	 *
	 * UTF-8 aware alternative to substr()
	 *
	 * @param   string        $str     String being processed
	 * @param   integer       $offset  Number of UTF-8 characters offset (from left)
	 * @param   integer|null  $length  Optional length in UTF-8 characters from offset
	 *
	 * @return  string|boolean
	 *
	 * @link    https://www.php.net/substr
	 * @since   1.3.0
	 */
	public static function substr($str, $offset, $length = null)
	{
		if ($length === null)
		{
			return utf8_substr($str, $offset);
		}

		return utf8_substr($str, $offset, $length);
	}

	/**
	 * Make a string lowercase
	 *
	 * UTF-8 aware alternative to strtolower()
	 *
	 * Note: The concept of a characters "case" only exists is some alphabets such as Latin, Greek, Cyrillic, Armenian and archaic Georgian - it does
	 * not exist in the Chinese alphabet, for example. See Unicode Standard Annex #21: Case Mappings
	 *
	 * @param   string  $str  String being processed
	 *
	 * @return  string|boolean  Either string in lowercase or FALSE is UTF-8 invalid
	 *
	 * @link    https://www.php.net/strtolower
	 * @since   1.3.0
	 */
	public static function strtolower($str)
	{
		return utf8_strtolower($str);
	}

	/**
	 * Make a string uppercase
	 *
	 * UTF-8 aware alternative to strtoupper()
	 *
	 * Note: The concept of a characters "case" only exists is some alphabets such as Latin, Greek, Cyrillic, Armenian and archaic Georgian - it does
	 * not exist in the Chinese alphabet, for example. See Unicode Standard Annex #21: Case Mappings
	 *
	 * @param   string  $str  String being processed
	 *
	 * @return  string|boolean  Either string in uppercase or FALSE is UTF-8 invalid
	 *
	 * @link    https://www.php.net/strtoupper
	 * @since   1.3.0
	 */
	public static function strtoupper($str)
	{
		return utf8_strtoupper($str);
	}

	/**
	 * UTF-8 aware alternative to strlen()
	 *
	 * Returns the number of characters in the string (NOT THE NUMBER OF BYTES).
	 *
	 * @param   string  $str  UTF-8 string.
	 *
	 * @return  integer  Number of UTF-8 characters in string.
	 *
	 * @link    https://www.php.net/strlen
	 * @since   1.3.0
	 */
	public static function strlen($str)
	{
		return utf8_strlen($str);
	}

	/**
	 * Replace (parts of) a string in a case-insensitive manner
	 *
	 * UTF-8 aware alternative to str_ireplace()
	 *
	 *
	 * @param   string[]|string  $search   String(s) to search
	 *                                     Every replacement with search array is
	 *                                     performed on the result of previous replacement.
	 * @param   string[]|string  $replace  New string(s) to replace with
	 * @param   string           $subject  Existing string to replace
	 * @param   integer|null     $count    Optional count value to be passed by reference
	 *
	 * @return  string  UTF-8 String
	 *
	 * @link    https://www.php.net/str_ireplace
	 * @since   1.3.0
	 */
	public static function str_ireplace($search, $replace, $subject, &$count = null)
	{
		return utf8_ireplace($search, $replace, $subject, $count);
	}

	/**
	 * Pad a string to a certain length with another string.
	 *
	 * UTF-8 aware alternative to str_pad()
	 *
	 * $padStr may contain multi-byte characters.
	 *
	 * @param   string   $input   The input string.
	 * @param   integer  $length  If the value is negative, less than, or equal to the length of the input string, no padding takes place.
	 * @param   string   $padStr  The string may be truncated if the number of padding characters can't be evenly divided by the string's length.
	 * @param   integer  $type    The type of padding to apply
	 *
	 * @return  string
	 *
	 * @link    https://www.php.net/str_pad
	 * @since   1.4.0
	 */
	public static function str_pad($input, $length, $padStr = ' ', $type = STR_PAD_RIGHT)
	{
		return utf8_str_pad($input, $length, $padStr, $type);
	}

	/**
	 * Split a string into an array.
	 *
	 * UTF-8 aware alternative to str_split()
	 *
	 * @param   string   $str       UTF-8 encoded string to process
	 * @param   integer  $splitLen  Number to characters to split string by
	 *
	 * @return  array|string|boolean
	 *
	 * @link    https://www.php.net/str_split
	 * @since   1.3.0
	 */
	public static function str_split($str, $splitLen = 1)
	{
		return utf8_str_split($str, $splitLen);
	}

	/**
	 * Compare strings in a case-insensitive manner.
	 *
	 * UTF-8/LOCALE aware alternative to strcasecmp()
	 *
	 * @param   string                $str1    string 1 to compare
	 * @param   string                $str2    string 2 to compare
	 * @param   array|string|boolean  $locale  The locale used by strcoll or false to use classical comparison
	 *
	 * @return  integer   < 0 if str1 is less than str2; > 0 if str1 is greater than str2, and 0 if they are equal.
	 *
	 * @link    https://www.php.net/strcasecmp
	 * @link    https://www.php.net/strcoll
	 * @link    https://www.php.net/setlocale
	 * @since   1.3.0
	 */
	public static function strcasecmp($str1, $str2, $locale = false)
	{
		if ($locale === false)
		{
			return utf8_strcasecmp($str1, $str2);
		}

		$encoding = self::setLocale($locale);

		// If we successfully set encoding it to utf-8 or encoding is sth weird don't recode
		if ($encoding === 'UTF-8' || $encoding === 'nonrecodable')
		{
			return strcoll(utf8_strtolower($str1), utf8_strtolower($str2));
		}

		return strcoll(
			static::transcode(utf8_strtolower($str1), 'UTF-8', $encoding),
			static::transcode(utf8_strtolower($str2), 'UTF-8', $encoding)
		);
	}

	/**
	 * Compare strings in a case-sensitive manner.
	 *
	 * UTF-8/LOCALE aware alternative to strcmp()
	 *
	 * @param   string                $str1    string 1 to compare
	 * @param   string                $str2    string 2 to compare
	 * @param   array|string|boolean  $locale  The locale used by strcoll or false to use classical comparison
	 *
	 * @return  integer  < 0 if str1 is less than str2; > 0 if str1 is greater than str2, and 0 if they are equal.
	 *
	 * @link    https://www.php.net/strcmp
	 * @link    https://www.php.net/strcoll
	 * @link    https://www.php.net/setlocale
	 * @since   1.3.0
	 */
	public static function strcmp($str1, $str2, $locale = false)
	{
		if ($locale === false)
		{
			return strcmp($str1, $str2);
		}

		$encoding = self::setLocale($locale);

		// If we successfully set encoding it to utf-8 or encoding is sth weird don't recode
		if ($encoding === 'UTF-8' || $encoding === 'nonrecodable')
		{
			return strcoll($str1, $str2);
		}

		return strcoll(static::transcode($str1, 'UTF-8', $encoding), static::transcode($str2, 'UTF-8', $encoding));
	}

	/**
	 * Find length of initial segment not matching mask.
	 *
	 * UTF-8 aware alternative to strcspn()
	 *
	 * @param   string           $str     The string to process
	 * @param   string           $mask    The mask
	 * @param   integer|boolean  $start   Optional starting character position (in characters)
	 * @param   integer|boolean  $length  Optional length
	 *
	 * @return  integer  The length of the initial segment of str1 which does not contain any of the characters in str2
	 *
	 * @link    https://www.php.net/strcspn
	 * @since   1.3.0
	 */
	public static function strcspn($str, $mask, $start = null, $length = null)
	{
		if ($length === null)
		{
			if ($start === null)
			{
				return utf8_strcspn($str, $mask);
			}

			return utf8_strcspn($str, $mask, $start);
		}

		return utf8_strcspn($str, $mask, $start, $length);
	}

	/**
	 * Get everything from haystack from the first occurrence of needle to the end.
	 *
	 * UTF-8 aware alternative to stristr()
	 *
	 * Needle and haystack are examined in a case-insensitive manner to find the first occurrence of a string using
	 * case-insensitive comparison.
	 *
	 * @param   string  $str     The haystack
	 * @param   string  $search  The needle
	 *
	 * @return  string|boolean
	 *
	 * @link    https://www.php.net/stristr
	 * @since   1.3.0
	 */
	public static function stristr($str, $search)
	{
		return utf8_stristr($str, $search);
	}

	/**
	 * Reverse a string.
	 *
	 * UTF-8 aware alternative to strrev()
	 *
	 * @param   string  $str  String to be reversed
	 *
	 * @return  string   The string in reverse character order
	 *
	 * @link    https://www.php.net/strrev
	 * @since   1.3.0
	 */
	public static function strrev($str)
	{
		return utf8_strrev($str);
	}

	/**
	 * Find length of initial segment matching mask.
	 *
	 * UTF-8 aware alternative to strspn()
	 *
	 * @param   string        $str     The haystack
	 * @param   string        $mask    The mask
	 * @param   integer|null  $start   Start optional
	 * @param   integer|null  $length  Length optional
	 *
	 * @return  integer
	 *
	 * @link    https://www.php.net/strspn
	 * @since   1.3.0
	 */
	public static function strspn($str, $mask, $start = null, $length = null)
	{
		if ($length === null)
		{
			if ($start === null)
			{
				return utf8_strspn($str, $mask);
			}

			return utf8_strspn($str, $mask, $start);
		}

		return utf8_strspn($str, $mask, $start, $length);
	}

	/**
	 * Replace text within a portion of a string.
	 *
	 * UTF-8 aware alternative to substr_replace()
	 *
	 * @param   string                $str     The haystack
	 * @param   string                $repl    The replacement string
	 * @param   integer               $start   Start
	 * @param   integer|boolean|null  $length  Length (optional)
	 *
	 * @return  string
	 *
	 * @link    https://www.php.net/substr_replace
	 * @since   1.3.0
	 */
	public static function substr_replace($str, $repl, $start, $length = null)
	{
		if ($length === false)
		{
			return utf8_substr_replace($str, $repl, $start);
		}

		return utf8_substr_replace($str, $repl, $start, $length);
	}

	/**
	 * Strip whitespace (or other characters) from the beginning of a string.
	 *
	 * UTF-8 aware replacement for ltrim()
	 *
	 * You only need to use this if you are supplying the char list optional arg, and it contains UTF-8 characters.
	 * Otherwise, ltrim will work normally on a UTF-8 string.
	 *
	 * @param   string          $str       The string to be trimmed
	 * @param   string|boolean  $charlist  The optional charlist of additional characters to trim
	 *
	 * @return  string  The trimmed string
	 *
	 * @link    https://www.php.net/ltrim
	 * @since   1.3.0
	 */
	public static function ltrim($str, $charlist = false)
	{
		if ($charlist === false)
		{
			return utf8_ltrim($str);
		}

		if (empty($charlist))
		{
			return $str;
		}

		return utf8_ltrim($str, $charlist);
	}

	/**
	 * Strip whitespace (or other characters) from the end of a string.
	 *
	 * UTF-8 aware replacement for rtrim()
	 *
	 * You only need to use this if you are supplying the char list optional arg, and it contains UTF-8 characters.
	 * Otherwise, rtrim will work normally on a UTF-8 string.
	 *
	 * @param   string          $str       The string to be trimmed
	 * @param   string|boolean  $charlist  The optional charlist of additional characters to trim
	 *
	 * @return  string  The trimmed string
	 *
	 * @link    https://www.php.net/rtrim
	 * @since   1.3.0
	 */
	public static function rtrim($str, $charlist = false)
	{
		if ($charlist === false)
		{
			return utf8_rtrim($str);
		}

		if (empty($charlist))
		{
			return $str;
		}

		return utf8_rtrim($str, $charlist);
	}

	/**
	 * Strip whitespace (or other characters) from the beginning and end of a string.
	 *
	 * UTF-8 aware replacement for trim()
	 *
	 * You only need to use this if you are supplying the charlist optional arg and it contains UTF-8 characters.
	 * Otherwise, trim will work normally on a UTF-8 string
	 *
	 * @param   string          $str       The string to be trimmed
	 * @param   string|boolean  $charlist  The optional charlist of additional characters to trim
	 *
	 * @return  string  The trimmed string
	 *
	 * @link    https://www.php.net/trim
	 * @since   1.3.0
	 */
	public static function trim($str, $charlist = false)
	{
		if ($charlist === false)
		{
			return utf8_trim($str);
		}

		if (empty($charlist))
		{
			return $str;
		}

		return utf8_trim($str, $charlist);
	}

	/**
	 * Make a string's first character uppercase or all words' first character uppercase.
	 *
	 * UTF-8 aware alternative to ucfirst()
	 *
	 * @param   string       $str           String to be processed
	 * @param   string|null  $delimiter     The words delimiter (null means do not split the string)
	 * @param   string|null  $newDelimiter  The new words delimiter (null means equal to $delimiter)
	 *
	 * @return  string  If $delimiter is null, return the string with first character as upper case (if applicable)
	 *                  else consider the string of words separated by the delimiter, apply the ucfirst to each words
	 *                  and return the string with the new delimiter
	 *
	 * @link    https://www.php.net/ucfirst
	 * @since   1.3.0
	 */
	public static function ucfirst($str, $delimiter = null, $newDelimiter = null)
	{
		if ($delimiter === null)
		{
			return utf8_ucfirst($str);
		}

		if ($newDelimiter === null)
		{
			$newDelimiter = $delimiter;
		}

		return implode($newDelimiter, array_map('utf8_ucfirst', explode($delimiter, $str)));
	}

	/**
	 * Uppercase the first character of each word in a string.
	 *
	 * UTF-8 aware alternative to ucwords()
	 *
	 * @param   string  $str  String to be processed
	 *
	 * @return  string  String with first char of each word uppercase
	 *
	 * @link    https://www.php.net/ucwords
	 * @since   1.3.0
	 */
	public static function ucwords($str)
	{
		return utf8_ucwords($str);
	}

	/**
	 * Transcode a string.
	 *
	 * @param   string  $source        The string to transcode.
	 * @param   string  $fromEncoding  The source encoding.
	 * @param   string  $toEncoding    The target encoding.
	 *
	 * @return  string|null  The transcoded string, or null if the source was not a string.
	 *
	 * @link    https://bugs.php.net/bug.php?id=48147
	 *
	 * @since   1.3.0
	 */
	public static function transcode($source, $fromEncoding, $toEncoding)
	{
		$modifier = ICONV_IMPL === 'glibc' ? '//TRANSLIT,IGNORE' : '//IGNORE//TRANSLIT';

		return @iconv($fromEncoding, $toEncoding . $modifier, $source);
	}

	/**
	 * Tests a string whether it's valid UTF-8 and supported by the Unicode standard.
	 *
	 * Note: this function has been modified to simple return true or false.
	 *
	 * @param   string  $str  UTF-8 encoded string.
	 *
	 * @return  boolean  true if valid
	 *
	 * @author  <hsivonen@iki.fi>
	 * @link    https://hsivonen.fi/php-utf8/
	 * @see     compliant
	 * @since   1.3.0
	 */
	public static function valid($str)
	{
		return utf8_is_valid($str);
	}

	/**
	 * Tests whether a string complies as UTF-8.
	 *
	 * This will be much faster than StringHelper::valid() but will pass five and six octet UTF-8 sequences, which are
	 * not supported by Unicode and so cannot be displayed correctly in a browser. In other words it is not as strict
	 * as StringHelper::valid() but it's faster. If you use it to validate user input, you place yourself at the risk
	 * that attackers will be able to inject 5 and 6 byte sequences (which may or may not be a significant risk,
	 * depending on what you are doing).
	 *
	 * @param   string  $str  UTF-8 string to check
	 *
	 * @return  boolean  TRUE if string is valid UTF-8
	 *
	 * @see     StringHelper::valid
	 * @link    https://www.php.net/manual/en/reference.pcre.pattern.modifiers.php#54805
	 * @since   1.3.0
	 */
	public static function compliant($str)
	{
		return utf8_compliant($str);
	}

	/**
	 * Converts Unicode sequences to UTF-8 string.
	 *
	 * @param   string  $str  Unicode string to convert
	 *
	 * @return  string  UTF-8 string
	 *
	 * @since   1.3.0
	 */
	public static function unicode_to_utf8($str)
	{
		if (\extension_loaded('mbstring'))
		{
			return preg_replace_callback(
				'/\\\\u([0-9a-fA-F]{4})/',
				static function ($match) {
					return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
				},
				$str
			);
		}

		return $str; // @codeCoverageIgnore
	}

	/**
	 * Converts Unicode sequences to UTF-16 string.
	 *
	 * @param   string  $str  Unicode string to convert
	 *
	 * @return  string  UTF-16 string
	 *
	 * @since   1.3.0
	 */
	public static function unicode_to_utf16($str)
	{
		if (\extension_loaded('mbstring'))
		{
			return preg_replace_callback(
				'/\\\\u([0-9a-fA-F]{4})/',
				static function ($match) {
					return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UTF-16BE');
				},
				$str
			);
		}

		return $str; // @codeCoverageIgnore
	}

	/**
	 * @param $value
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
	 * @param   string[]|string  $locale
	 *
	 * @return string
	 */
	private static function setLocale($locale): string
	{
		$locale = setlocale(LC_COLLATE, $locale);

		if ($locale === false)
		{
			$locale = setlocale(LC_COLLATE, 0);
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
}
