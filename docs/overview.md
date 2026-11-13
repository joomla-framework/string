# String package

TODO

## Loading `StringHelper` changes `default_charset`

When `StringHelper.php` is loaded, it runs `@ini_set('default_charset', 'UTF-8')` at file scope. This
changes a process-wide setting, which is the default character set of `htmlspecialchars()`,
`htmlentities()`, `html_entity_decode()` and the `Content-Type` header that PHP sends. Whether it
happens depends on whether some code has loaded the class yet.

This side effect is deprecated and will be removed in 5.0. `UTF-8` has been PHP's default for
`default_charset` since PHP 5.6. An application that needs `UTF-8` and runs with a different
`php.ini` value should set `default_charset` itself, in `php.ini` or with `ini_set()` during bootstrap.
