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

### `symfony/polyfill-mbstring` is now required

`symfony/polyfill-mbstring` moved into `require`, so the `mb_*` functions are available even
without `ext-mbstring`. `ext-mbstring` remains in `suggest` — the native extension is faster.

The package now also uses functions that PHP added after its own minimum version:
`mb_str_pad()` (PHP 8.3) and `mb_trim()`, `mb_ltrim()`, `mb_rtrim()`, `mb_ucfirst()` (PHP 8.4).
The polyfill supplies them, but only from version 1.32 onwards, while `composer.json` allows
`^1.31.0`. If you pin dependencies with `--prefer-lowest`, raise that constraint or you will hit
`Call to undefined function mb_trim()` on PHP 8.3.

### Dependency changes

| Package | v3 (3.0.x) | v4 (4.0.0) |
|---|---|---|
| `php` | `^8.1.0` | `^8.3.0` |
| `symfony/polyfill-mbstring` | — | `^1.31.0` (new, required) |
| `symfony/deprecation-contracts` | `^2 \| ^3` | unchanged |

`ext-mbstring` and `doctrine/inflector` remain in `suggest`. Install `doctrine/inflector`
explicitly if you use the `Inflector` class — it is imported unconditionally.
