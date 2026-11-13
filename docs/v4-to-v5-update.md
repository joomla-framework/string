## Updating from v4 to v5

The following changes were made to the String package between version 4 and version 5.

### Loading `StringHelper` no longer changes `default_charset`

In 4.x, loading `StringHelper.php` ran `@ini_set('default_charset', 'UTF-8')`, which changed a
process-wide PHP setting as a side effect. This has been removed. `UTF-8` has been PHP's default for
`default_charset` since PHP 5.6. If your `php.ini` sets a different value and your application relies
on `UTF-8`, for example for `htmlspecialchars()` or the `Content-Type` header, set it yourself in
`php.ini` or with `ini_set('default_charset', 'UTF-8')` during bootstrap.
