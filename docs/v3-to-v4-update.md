## Updating from v3 to v4

### Minimum supported PHP version raised

All Framework packages now require PHP 8.3 or newer.

### Deprecated methods from `Inflector` class have been removed

The following deprecated methods have been removed in this release:

* `Inflector::addWord()`: Configure an irregular word for `doctrine/inflector` with `Doctrine\Inflector\InflectorFactory::create()->withSingularRules()` and `->withPluralRules()`
* `Inflector::addPluraliseRule()`: Use `Doctrine\Inflector\InflectorFactory::create()->withPluralRules()`
* `Inflector::addSingulariseRule()`: Use `Doctrine\Inflector\InflectorFactory::create()->withSingularRules()`
* `Inflector::getInstance()`: Use the static methods without an instance instead.
* `Inflector::toPlural()`: Use `pluralize()` of a `Doctrine\Inflector\Inflector` built with `Doctrine\Inflector\InflectorFactory::create()->build()`
* `Inflector::toSingular()`: Use `singularize()` of a `Doctrine\Inflector\Inflector` built with `Doctrine\Inflector\InflectorFactory::create()->build()`

The `rules()` and `reset()` methods of `Joomla\String\Inflector` are kept only for the former interface and have
no effect: rules passed to them are ignored. They trigger a deprecation notice. Configure the rules of
`doctrine/inflector` instead:

```php
use Doctrine\Inflector\InflectorFactory;
use Doctrine\Inflector\Rules\Pattern;
use Doctrine\Inflector\Rules\Patterns;
use Doctrine\Inflector\Rules\Ruleset;
use Doctrine\Inflector\Rules\Substitution;
use Doctrine\Inflector\Rules\Substitutions;
use Doctrine\Inflector\Rules\Transformations;
use Doctrine\Inflector\Rules\Word;

$inflector = InflectorFactory::create()
    ->withPluralRules(
        new Ruleset(
            new Transformations(),
            new Patterns(new Pattern('sheep')),
            new Substitutions(new Substitution(new Word('person'), new Word('folk')))
        )
    )
    ->build();

$inflector->pluralize('person'); // 'folk'
```

### The `Inflector` class has been deprecated

The `Inflector` class in this package does not provide benefits over the original `doctrine/inflector` package anymore and is currently only there to keep backwards compatibility with version 3. Use `doctrine/inflector` directly instead. This class will be removed in 5.0 of this package.

Every method of the class triggers a deprecation notice (`E_USER_DEPRECATED` through `symfony/deprecation-contracts`).
