# Max

- `Max(Validatable $rule)`

Validates the maximum value of the input against a given rule.

```php
v::max(v::equals(30))->validate([10, 20, 30]); // true

v::max(v::between('e', 'g'))->validate(['b', 'd', 'f']); // true

v::max(v::greaterThan(new DateTime('today')))
        ->validate([new DateTime('yesterday'), new DateTime('tomorrow')]); // true

v::max(v::greaterThan(15))->validate([4, 8, 12]); // false
```

## Note

This rule uses [IterableType](IterableType.md) and [NotEmpty](NotEmpty.md) internally. If an input is non-iterable or
empty, the validation will fail.

## Categorization

- Comparisons
- Transformations

## Changelog

| Version | Description                 |
|--------:|-----------------------------|
|   3.0.0 | Became a transformation     |
|   2.0.0 | Became always inclusive     |
|   1.0.0 | Became inclusive by default |
|   0.3.9 | Created                     |

***
See also:

- [Between](Between.md)
- [BetweenExclusive](BetweenExclusive.md)
- [GreaterThan](GreaterThan.md)
- [GreaterThanOrEqual](GreaterThanOrEqual.md)
- [IterableType](IterableType.md)
- [LessThan](LessThan.md)
- [LessThanOrEqual](LessThanOrEqual.md)
- [Min](Min.md)
- [NotEmpty](NotEmpty.md)
