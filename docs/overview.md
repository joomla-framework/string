# Overview

The String package provides UTF-8 aware string handling, name-convention conversion, and a
deprecated inflector.

```bash
composer require joomla/string
```

## What is in the package

| Class | Purpose |
|---|---|
| `Joomla\String\StringHelper` | UTF-8 aware equivalents of the PHP string functions |
| `Joomla\String\Normalise` | Converting between camelCase, dash-, underscore- and space-separated names |
| `Joomla\String\Inflector` | Singular/plural conversion — **deprecated**, use `doctrine/inflector` |

## `StringHelper`

Most methods are thin wrappers over `mb_*` with the same argument order as their non-multibyte
counterparts, so they can be used as drop-in replacements:

```php
use Joomla\String\StringHelper;

StringHelper::strlen('Größe');            // 5, not 6
StringHelper::substr('Größe', 0, 3);      // 'Grö'
StringHelper::strtolower('STRASSE');
StringHelper::strtoupper('straße');
StringHelper::strpos($haystack, $needle);
StringHelper::strrpos($haystack, $needle);
StringHelper::str_split('äöü');           // ['ä', 'ö', 'ü']
StringHelper::str_pad($s, 10, '.', STR_PAD_LEFT);
StringHelper::ucfirst('über');
StringHelper::ucwords('der große wagen');
StringHelper::trim($s);                   // also strips non-breaking spaces
StringHelper::ltrim($s);
StringHelper::rtrim($s);
```

Beyond the wrappers:

```php
StringHelper::is_ascii($string);                    // bool
StringHelper::valid($string);                       // valid UTF-8?
StringHelper::ord('€');                             // 8364
StringHelper::transcode($string, 'ISO-8859-1', 'UTF-8');
StringHelper::increment('Copy');                    // 'Copy (2)'
StringHelper::increment('Copy', 'dash');            // 'Copy-2'
StringHelper::increment('Copy', 'default', 5);      // 'Copy (5)'
```

`increment()` is the helper behind "copy of a copy" naming: it finds a trailing counter and raises
it, or appends one.

### Notes on individual methods

**`valid()`** is a hand-written UTF-8 state machine inherited from the `phputf8` library. For new
code `mb_check_encoding($string, 'UTF-8')` does the same thing and is maintained by PHP.

**`transcode()`** needs `ext-iconv`, which is not declared in `composer.json`. On a build without
it the method raises `Error: Undefined constant "ICONV_IMPL"`. Guard with
`extension_loaded('iconv')` if you cannot be sure.

**`str_ireplace()`** is a hand-written implementation that mixes byte offsets with character
positions. It works for plain ASCII, but on input where lowercasing changes the byte length —
Turkish `İ`, for example — the offsets drift and the result can contain broken UTF-8. Prefer
`preg_replace('/…/iu', …)` for case-insensitive replacement on non-ASCII data.

**`strcmp()`/`strcasecmp()`** with a locale argument use `strcoll()`, which depends on the
process-wide locale. Avoid them inside comparison callbacks that run many times.

## `Normalise`

Converting between naming conventions:

```php
use Joomla\String\Normalise;

Normalise::fromCamelCase('helloWorld');            // 'hello World'
Normalise::fromCamelCase('helloWorld', true);      // ['hello', 'World']
Normalise::toCamelCase('hello world');             // 'helloWorld'
Normalise::toDashSeparated('hello world');         // 'hello-world'
Normalise::toSpaceSeparated('hello_world');        // 'hello world'
Normalise::toUnderscoreSeparated('hello world');   // 'hello_world'
Normalise::toVariable('hello world');              // 'helloWorld'
Normalise::toKey('Hello World');                   // 'hello_world'
```

`toVariable()` produces a valid PHP variable name; `toKey()` a lower-case underscore key.

## `Inflector` — deprecated

```php
use Joomla\String\Inflector;

Inflector::pluralize('house');    // 'houses'
Inflector::singularize('houses'); // 'house'
Inflector::isPlural('houses');    // true
Inflector::isSingular('house');   // true
Inflector::isCountable('id');     // true
```

The class is a thin wrapper over `doctrine/inflector` and is marked `@deprecated 5.0`. Use that
package directly in new code:

```bash
composer require doctrine/inflector
```

```php
use Doctrine\Inflector\InflectorFactory;

$inflector = InflectorFactory::create()->build();
$inflector->pluralize('house');
```

Note that `doctrine/inflector` is listed under `suggest`, not `require`, even though `Inflector`
imports it unconditionally. Install it explicitly if you use the class.

## A note on loading this package

`StringHelper.php` executes `@ini_set('default_charset', 'UTF-8')` at file scope, so simply
autoloading the class changes a process-wide PHP setting — one that determines the default
character set of `htmlspecialchars()`, `htmlentities()` and the `Content-Type` header PHP sends. If
your application sets `default_charset` deliberately, set it after the point where this class may
first be loaded.
