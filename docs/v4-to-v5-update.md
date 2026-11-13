## Updating from v4 to v5

The following changes were made to the String package between version 4 and version 5.

### The `Inflector` class has been removed

`Joomla\String\Inflector`, deprecated in 4.x, has been removed. Use the `doctrine/inflector` package
directly:

```php
use Doctrine\Inflector\InflectorFactory;

$inflector = InflectorFactory::create()->build();

$inflector->pluralize('house');    // 'houses'
$inflector->singularize('houses'); // 'house'
$inflector->tableize('BlogPost');  // 'blog_post'
```

| 4.x | Replacement |
|---|---|
| `Inflector::pluralize()`, `singularize()`, `tableize()`, `classify()`, `camelize()` | The method of the same name on a `Doctrine\Inflector\Inflector` |
| `Inflector::isPlural($word)` | `$inflector->pluralize($inflector->singularize($word)) === $word` |
| `Inflector::isSingular($word)` | `$inflector->singularize($word) === $word` |
| `Inflector::ucwords()` | PHP's `ucwords($string, " \n\t\r\0\x0B-")` |
| `Inflector::addCountableRule()`, `isCountable()` | Keep your own list of countable words |
| `Inflector::rules()`, `reset()` | Had no effect. Use `InflectorFactory::create()->withSingularRules()` / `withPluralRules()` |

### Dependency changes

`doctrine/inflector` is no longer suggested and no longer a development dependency.
