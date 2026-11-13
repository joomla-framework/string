## Updating from v4 to v5

The following changes were made to the String package between version 4 and version 5.

### Native parameter and return types

All methods of `StringHelper` and `Normalise` have native parameter and return types. Passing a value
of the wrong type, for example `null` instead of a string, now throws a `TypeError`. A subclass that
overrides one of these static methods must use compatible signatures.

The `false` sentinels for optional integer parameters have been replaced with `null`:

| Method | 4.x | 5.x |
|---|---|---|
| `StringHelper::strpos()` | `$offset = false` | `?int $offset = null` |
| `StringHelper::substr()` | `$length = false` | `?int $length = null` |
| `StringHelper::substr_replace()` | `$length = null`, `false` also accepted | `?int $length = null` |
| `StringHelper::str_ireplace()` | `$count = null`, `true`/`false` also meant "no limit" | `?int $count = null` |
| `StringHelper::strcspn()`, `strspn()` | `$start`/`$length` also accepted `false` | `?int $start = null, ?int $length = null` |

Without `declare(strict_types=1)`, PHP converts `false` to `0` and `true` to `1` for an `int`
parameter instead of throwing, which changes the result silently. Replace these calls:

* `StringHelper::substr($str, $offset, false)` returned the rest of the string and now returns `''`. Pass `null` or leave the argument out.
* `StringHelper::substr_replace($str, $repl, $start, false)` replaced up to the end of the string and now inserts without replacing. Pass `null`.
* `StringHelper::str_ireplace($search, $replace, $str, true)` replaced every occurrence and now replaces only the first one. Pass `null`.

Other signature changes:

* `strcmp()` and `strcasecmp()` take `string|false $locale`. `strcmp()` used to accept any value and treated falsy values as "no locale".
* `ltrim()`, `rtrim()` and `trim()` keep `string|false $charlist = false`.
* `ord()`, `strpos()`, `strrpos()` and `stristr()` declare the `false` they return when nothing is found. `transcode()` declares `string|false`, because `iconv()` returns `false` on failure. `strtolower()`, `strtoupper()` and `substr()` return `string` only, as the `mb_*` functions they wrap have done since PHP 8.0.
* The protected property `StringHelper::$incrementStyles` is typed `array`.
