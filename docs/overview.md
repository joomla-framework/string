# String package

TODO

## `StringHelper::transcode()`

`StringHelper::transcode($source, $fromEncoding, $toEncoding)` converts a string between character
sets. With `ext-iconv` it uses `iconv()` with transliteration, so characters the target encoding
cannot represent are approximated or dropped (`€` becomes `EUR` in ISO-8859-1). `ext-iconv` is
suggested, not required. Without it, `transcode()` uses `mb_convert_encoding()`, which replaces
such characters with `?`.

```php
use Joomla\String\StringHelper;

StringHelper::transcode('Åbc €100', 'UTF-8', 'ISO-8859-1'); // "\xC5bc EUR100" with ext-iconv, "\xC5bc ?100" without
```
