# String package

TODO

## `StringHelper::str_ireplace()`

`StringHelper::str_ireplace($search, $replace, $str, $count = null)` replaces every case-insensitive
occurrence of `$search` in `$str`. It matches on the original string with Unicode case folding, so
characters whose lowercase form has a different byte length (`Ⱥ`, `ẞ`) are handled correctly, and the
replacement is inserted literally.

Unlike PHP's `str_ireplace()`, the fourth argument is **not** a by-reference variable that receives
the number of replacements. It is a limit: at most `$count` occurrences of each search string are
replaced. `null`, `true` and `false` mean no limit.

```php
use Joomla\String\StringHelper;

StringHelper::str_ireplace('b', 'c', 'abAB');    // 'acAc'
StringHelper::str_ireplace('b', 'c', 'abAB', 1); // 'acAB'
```
