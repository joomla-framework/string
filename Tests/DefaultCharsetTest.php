<?php

/**
 * @copyright  Copyright (C) 2026 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\String\Tests;

use Joomla\String\StringHelper;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\TestCase;

/**
 * Test that loading StringHelper has no side effects on the PHP configuration.
 */
class DefaultCharsetTest extends TestCase
{
    /**
     * @testdox  Loading StringHelper does not change the default_charset setting
     */
    #[RunInSeparateProcess]
    public function testLoadingStringHelperKeepsDefaultCharset()
    {
        $this->assertFalse(class_exists(StringHelper::class, false), 'StringHelper must not be loaded yet.');

        ini_set('default_charset', 'ISO-8859-1');

        $this->assertTrue(class_exists(StringHelper::class));
        $this->assertSame('ISO-8859-1', ini_get('default_charset'));
    }
}
