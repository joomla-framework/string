# String package

TODO

## `StringHelper::valid()`

`StringHelper::valid($str)` returns whether `$str` is valid UTF-8, using `mb_check_encoding($str, 'UTF-8')`.
Overlong forms, UTF-16 surrogates, code points above U+10FFFF, five- and six-byte sequences and
strings that end in an incomplete multi-byte sequence are rejected. Earlier releases reported a string
that ends in an incomplete sequence, such as `"abc\xC3"`, as valid.
