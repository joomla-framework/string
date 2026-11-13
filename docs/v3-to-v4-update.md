## Updating from v3 to v4

### Minimum supported PHP version raised

All Framework packages now require PHP 8.3 or newer.

### Deprecated methods from `Inflector` class have been removed

The following deprecated methods have been removed in this release:

* `Inflector::addWord()`: Use `Doctrine\Common\Inflector\Inflector::rules()`
* `Inflector::addPluraliseRule()`: Use `Doctrine\Common\Inflector\Inflector::rules()`
* `Inflector::addSingulariseRule()`: Use `Doctrine\Common\Inflector\Inflector::rules()`
* `Inflector::getInstance()`: Use the static methods without an instance instead.
* `Inflector::toPlural()`: Use static `Doctrine\Common\Inflector\Inflector::pluralize()`
* `Inflector::toSingular()`: Use static `Doctrine\Common\Inflector\Inflector::singularize()`

### The `Inflector` class has been deprecated

The `Inflector` class in this package does not provide benefits over the original `doctrine/inflector` package anymore and is currently only there to keep backwards compatibility with version 3. Use `doctrine/inflector` directly instead. This class will be removed in 5.0 of this package.

### `StringHelper::trim()`, `ltrim()` and `rtrim()` strip more whitespace by default

Without a `$charlist`, the three methods now call `mb_trim()`, `mb_ltrim()` and `mb_rtrim()` instead of
PHP's `trim()`, `ltrim()` and `rtrim()`. Besides the ASCII whitespace that the native functions strip,
they now also remove the form feed (`\f`), U+0085 and the Unicode space separators, including the
no-break space U+00A0, U+1680, U+2000 to U+200A, U+2028, U+2029, U+202F, U+205F, U+3000 and U+180E.

```php
use Joomla\String\StringHelper;

StringHelper::trim("\u{00A0}Größe\u{3000}"); // 4.x: 'Größe', 3.x: unchanged
```

To strip only ASCII whitespace as before, use PHP's `trim()` or pass the characters explicitly, for
example `StringHelper::trim($str, " \t\n\r\0\x0B")`. A `$charlist` is used as given. As in 3.x,
`..` does not define a range of characters.
