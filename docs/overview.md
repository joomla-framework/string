# String package

TODO

## `StringHelper::unicode_to_utf8()` and `unicode_to_utf16()`

Both methods replace `\uXXXX` escape sequences, as they appear in JSON, with the characters they
stand for. A surrogate pair such as `\uD83D\uDE00` is decoded into one character (U+1F600). Despite
its name, `unicode_to_utf16()` also returns UTF-8. The two methods differ only in how they handle a
lone surrogate (one without its partner): `unicode_to_utf8()` encodes it as three bytes that are not
valid UTF-8, and `unicode_to_utf16()` turns it into `?`.

```php
use Joomla\String\StringHelper;

StringHelper::unicode_to_utf8('caf\u00e9 \uD83D\uDE00'); // 'café 😀'
```
