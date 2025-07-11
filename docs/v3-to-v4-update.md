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
