# String package

TODO

## Comparing strings with a locale

`StringHelper::strcmp($str1, $str2, $locale)` and `StringHelper::strcasecmp($str1, $str2, $locale)`
compare two UTF-8 strings with the collation rules of `$locale` when a locale is given. They use the
`Collator` class of the `intl` extension, so install `ext-intl` to use them:

```php
use Joomla\String\StringHelper;

StringHelper::strcmp('a', 'B', 'fr_FR');      // < 0 (byte comparison: > 0)
StringHelper::strcasecmp('É', 'é', 'fr_FR');  // 0
```

The locale is an ICU locale identifier such as `fr_FR`; a POSIX suffix like `.UTF-8` is ignored.
The process-wide locale (`setlocale()`) is not changed. ICU uses the root collation for locales it
does not know, such as Windows names like `French_Standard`.

Without `ext-intl`, both methods fall back to `setlocale(LC_COLLATE, $locale)` and `strcoll()`, and
restore the previous `LC_COLLATE` locale afterwards. This fallback is deprecated and triggers a
deprecation notice: `strcoll()` itself is deprecated as of PHP 8.6, and from PHP 8.6 on the fallback
compares the strings byte by byte instead.
