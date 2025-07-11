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

The `Inflector` class in this package depends on the `doctrine/inflector` v1 package, which has been EOL for some time. The newer v2 version of that package does not allow our class to inherit from their `Inflector` class anymore. At the same time, the new `doctrine/inflector` package provides basically everything our `Inflector` class supplied, so our class is not necessary anymore. Thus the `Inflector` class in this package is deprecated and should not be used anymore. Use the `doctrine/inflector` package directly instead. This class will be removed in 5.0 of this package.
