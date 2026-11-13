<?php

/**
 * Part of the Joomla Framework String Package
 *
 * @copyright  Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\String;

use Doctrine\Inflector\Inflector as DoctrineInflector;
use Doctrine\Inflector\InflectorFactory;

/**
 * Joomla Framework String Inflector Class
 *
 * The Inflector transforms words
 *
 * @since  1.0
 * @deprecated  5.0  Use doctrine/inflector package as complete replacement instead.
 */
class Inflector
{
    /**
     * The inflector rules for countability.
     *
     * @var    array
     * @since  2.0.0
     */
    private static $countable = [
        'rules' => [
            'id',
            'hits',
            'clicks',
        ],
    ];

    /**
     * The Doctrine inflector the proxy methods use.
     *
     * @var    DoctrineInflector|null
     * @since  __DEPLOY_VERSION__
     */
    private static $inflector;

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
        if (\is_string($data)) {
            $data = [$data];
        } elseif (!\is_array($data)) {
            throw new \InvalidArgumentException('Invalid inflector rule data.');
        } elseif (!\in_array($ruleType, ['singular', 'plural', 'countable'])) {
            throw new \InvalidArgumentException('Unsupported rule type.');
        }

        if ($ruleType === 'countable') {
            foreach ($data as $rule) {
                // Ensure a string is pushed.
                array_push(self::$countable['rules'], (string) $rule);
            }
        } else {
            static::rules($ruleType, $data);
        }
    }

    /**
     * Returns the Doctrine inflector, building it on first use.
     *
     * @return  DoctrineInflector
     *
     * @since   __DEPLOY_VERSION__
     */
    private static function getInflector(): DoctrineInflector
    {
        return self::$inflector ??= InflectorFactory::create()->build();
    }

    /**
     * Triggers the deprecation notice for the Inflector class.
     *
     * @return  void
     *
     * @since   __DEPLOY_VERSION__
     */
    private static function triggerClassDeprecation(): void
    {
        trigger_deprecation(
            'joomla/string',
            '__DEPLOY_VERSION__',
            'The %s class is deprecated and will be removed in 5.0. Use the doctrine/inflector package instead.',
            self::class
        );
    }

    /**
     * Triggers the deprecation notice for the inflection rule methods, which have no effect.
     *
     * @param   string  $method  The method that was called
     *
     * @return  void
     *
     * @since   __DEPLOY_VERSION__
     */
    private static function triggerRulesDeprecation(string $method): void
    {
        trigger_deprecation(
            'joomla/string',
            '__DEPLOY_VERSION__',
            '%s::%s() has no effect and will be removed in 5.0. Configure the rules of doctrine/inflector with'
            . ' InflectorFactory::create()->withSingularRules() and withPluralRules() instead.',
            self::class,
            $method
        );
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
        self::triggerClassDeprecation();

        $this->addRule($data, 'countable');

        return $this;
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
        self::triggerClassDeprecation();

        return \in_array($word, self::$countable['rules']);
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
        self::triggerClassDeprecation();

        $inflector = self::getInflector();

        return $inflector->pluralize($inflector->singularize($word)) === $word;
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
        self::triggerClassDeprecation();

        return self::getInflector()->singularize($word) === $word;
    }

    /**
     * Proxy for Inflector::tableize()
     */
    public static function tableize(string $word): string
    {
        self::triggerClassDeprecation();

        return self::getInflector()->tableize($word);
    }

    /**
     * Proxy for Inflector::classify()
     */
    public static function classify(string $word): string
    {
        self::triggerClassDeprecation();

        return self::getInflector()->classify($word);
    }

    /**
     * Proxy for Inflector::camelize()
     */
    public static function camelize(string $word): string
    {
        self::triggerClassDeprecation();

        return self::getInflector()->camelize($word);
    }

    /**
     * Proxy for Inflector::ucwords()
     */
    public static function ucwords(string $string, string $delimiters = " \n\t\r\0\x0B-"): string
    {
        self::triggerClassDeprecation();

        return ucwords($string, $delimiters);
    }

    /**
     * Empty method to suffice the former interface
     *
     * @deprecated  5.0  Has no effect. Configure the rules of doctrine/inflector with InflectorFactory instead.
     */
    public static function reset(): void
    {
        self::triggerRulesDeprecation('reset');
    }

    /**
     * Empty method to suffice the former interface
     *
     * @deprecated  5.0  Has no effect. Configure the rules of doctrine/inflector with InflectorFactory instead.
     */
    public static function rules(string $type, iterable $rules, bool $reset = false): void
    {
        self::triggerRulesDeprecation('rules');
    }

    /**
     * Proxy for Inflector::pluralize()
     */
    public static function pluralize(string $word): string
    {
        self::triggerClassDeprecation();

        return self::getInflector()->pluralize($word);
    }

    /**
     * Proxy for Inflector::singularize()
     */
    public static function singularize(string $word): string
    {
        self::triggerClassDeprecation();

        return self::getInflector()->singularize($word);
    }
}
