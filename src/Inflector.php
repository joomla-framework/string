<?php
/**
 * Part of the Joomla Framework String Package
 *
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\String;

use Doctrine\Common\Inflector\Inflector as DoctrineInflector;

/**
 * Joomla Framework String Inflector Class
 *
 * The Inflector transforms words
 *
 * @since  1.0
 */
class Inflector
{
	/**
	 * The singleton instance.
	 *
	 * @var    Inflector
	 * @since  1.0
	 */
	private static $instance;

	/**
	 * The inflector rules for countability.
	 *
	 * @var    array
	 * @since  1.0
	 */
	private $rules = [
		'countable' => [
			'id',
			'hits',
			'clicks',
		],
	];

	/**
	 * Adds inflection regex rules to the inflector.
	 *
	 * @param   mixed   $data      A string or an array of strings or regex rules to add.
	 * @param   string  $ruleType  The rule type: singular | plural | countable
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException
	 */
	private function addRule($data, string $ruleType)
	{
		if (is_string($data))
		{
			$data = [$data];
		}
		elseif (!is_array($data))
		{
			throw new \InvalidArgumentException('Invalid inflector rule data.');
		}
		elseif (!in_array($ruleType, ['singular', 'plural', 'countable']))
		{
			throw new \InvalidArgumentException('Unsupported rule type.');
		}

		if ($ruleType === 'countable')
		{
			foreach ($data as $rule)
			{
				// Ensure a string is pushed.
				array_push($this->rules[$ruleType], (string) $rule);
			}
		}
		else
		{
			DoctrineInflector::rules($ruleType, $data);
		}
	}

	/**
	 * Adds a countable word.
	 *
	 * @param   mixed  $data  A string or an array of strings to add.
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function addCountableRule($data)
	{
		$this->addRule($data, 'countable');

		return $this;
	}

	/**
	 * Adds a specific singular-plural pair for a word.
	 *
	 * @param   string  $singular  The singular form of the word.
	 * @param   string  $plural    The plural form of the word. If omitted, it is assumed the singular and plural are identical.
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function addWord($singular, $plural = '')
	{
		if ($plural !== '')
		{
			DoctrineInflector::rules(
				'plural',
				[
					'irregular' => [$plural => $singular]
				]
			);

			DoctrineInflector::rules(
				'singular',
				[
					'irregular' => [$singular => $plural]
				]
			);
		}
		else
		{
			DoctrineInflector::rules(
				'plural',
				[
					'uninflected' => [$singular]
				]
			);

			DoctrineInflector::rules(
				'singular',
				[
					'uninflected' => [$singular]
				]
			);
		}

		return $this;
	}

	/**
	 * Adds a pluralisation rule.
	 *
	 * @param   mixed  $data  A string or an array of regex rules to add.
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function addPluraliseRule($data)
	{
		$this->addRule($data, 'plural');

		return $this;
	}

	/**
	 * Adds a singularisation rule.
	 *
	 * @param   mixed  $data  A string or an array of regex rules to add.
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function addSingulariseRule($data)
	{
		$this->addRule($data, 'singular');

		return $this;
	}

	/**
	 * Gets an instance of the Inflector singleton.
	 *
	 * @param   boolean  $new  If true (default is false), returns a new instance regardless if one exists. This argument is mainly used for testing.
	 *
	 * @return  static
	 *
	 * @since   1.0
	 */
	public static function getInstance($new = false)
	{
		if ($new)
		{
			return new static;
		}

		if (!is_object(self::$instance))
		{
			self::$instance = new static;
		}

		return self::$instance;
	}

	/**
	 * Checks if a word is countable.
	 *
	 * @param   string  $word  The string input.
	 *
	 * @return  boolean  True if word is countable, false otherwise.
	 *
	 * @since   1.0
	 */
	public function isCountable($word)
	{
		return in_array($word, $this->rules['countable']);
	}

	/**
	 * Checks if a word is in a plural form.
	 *
	 * @param   string  $word  The string input.
	 *
	 * @return  boolean  True if word is plural, false if not.
	 *
	 * @since   1.0
	 */
	public function isPlural($word)
	{
		return $this->toPlural($this->toSingular($word)) === $word;
	}

	/**
	 * Checks if a word is in a singular form.
	 *
	 * @param   string  $word  The string input.
	 *
	 * @return  boolean  True if word is singular, false if not.
	 *
	 * @since   1.0
	 */
	public function isSingular($word)
	{
		return $this->toSingular($word) === $word;
	}

	/**
	 * Converts a word into its plural form.
	 *
	 * @param   string  $word  The singular word to pluralise.
	 *
	 * @return  string  The word in plural form.
	 *
	 * @since   1.0
	 */
	public function toPlural($word)
	{
		return DoctrineInflector::pluralize($word);
	}

	/**
	 * Converts a word into its singular form.
	 *
	 * @param   string  $word  The plural word to singularise.
	 *
	 * @return  string  The word in singular form.
	 *
	 * @since   1.0
	 */
	public function toSingular($word)
	{
		return DoctrineInflector::singularize($word);
	}
}
