# Updating from v2 to v3

Release 3.0.0 raises the PHP requirement and reformats the codebase. **No public or protected
method signature changed**, so code written against 2.x keeps working on PHP 8.1.

## At a glance

| | v2 (2.0.x) | v3 (3.0.0) |
|---|---|---|
| PHP | `^7.2.5` | `^8.1.0` |
| Public API | — | unchanged |
| Coding style | Joomla Coding Standard | PSR-12 |

## Minimum supported PHP version raised

All Framework packages now require **PHP 8.1** or newer.

## No API changes

`StringHelper`, `Normalise` and `Inflector` have the same signatures in 3.0.0 as in 2.0.0. The
`Inflector` methods deprecated earlier are still present; they were removed in 4.0.0 — see
[Updating from v3 to v4](v3-to-v4-update.md).

## Codebase converted to PSR-12

The package was reformatted from the Joomla Coding Standard to PSR-12. This touches nearly every
line and changes no behaviour.

## Dependency changes

| Package | v2 (2.0.x) | v3 (3.0.0) |
|---|---|---|
| `php` | `^7.2.5` | `^8.1.0` |
| `symfony/deprecation-contracts` | `^2.1` | `^2 \| ^3` |
