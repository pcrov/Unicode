# Unicode

[![Build Status](https://travis-ci.org/pcrov/Unicode.svg?branch=master)](https://travis-ci.org/pcrov/Unicode)
[![Coverage Status](https://coveralls.io/repos/github/pcrov/Unicode/badge.svg?branch=master)](https://coveralls.io/github/pcrov/Unicode?branch=master)
[![License](https://poser.pugx.org/pcrov/unicode/license)](https://github.com/pcrov/Unicode/blob/master/LICENSE)
[![Latest Stable Version](https://poser.pugx.org/pcrov/unicode/v/stable)](https://packagist.org/packages/pcrov/unicode)

Miscellaneous Unicode utility functions.

## Functions

Namespace `pcrov\Unicode`.

#### `surrogate_pair_to_code_point(int $high, int $low): int`
Translates a UTF-16 surrogate pair into a single code point. [Wikipedia's UTF-16 article][0]
explains what this is fairly well.

#### `utf8_find_invalid_byte_sequence(string $string): ?int`
Returns the position of the first invalid byte sequence or null if the input is valid.

#### `utf8_get_invalid_byte_sequence(string $string): ?string`
Returns the first invalid byte sequence or null if the input is valid.

#### `utf8_get_state_machine(): array`
Provides a state machine letting you walk a (potentially endless) UTF-8
sequence byte by byte.

It is in the form of `[byte => [valid next byte => ...,], ...]`

Example use:
```php
function utf8_generate_all_code_points(): string
{
    $generator = function (array $machine, string $buffer = "") use (&$generator) {
        // Completed a UTF-8 encoded code point.
        if ($buffer !== "" && isset($machine["\x0"])) {
            return $buffer;
        }

        $out = "";
        foreach ($machine as $byte => $next) {
            $out .= $generator($next, $buffer . $byte);
        }

        return $out;
    };

    return $generator(utf8_get_state_machine());
}
```

#### `utf8_validate(string $string): bool`
Does what it says on the box.

## Data
The test/data directory holds two files containing all possible UTF-8 encoded characters.
All 1,112,064 of them. One as plain text, the other as json. These are not included in
packaged stable releases but can be generated with the example `utf8_generate_all_code_points()`
function above (returns the plain text string.)

## Excerpts from the [Unicode 10.0.0 standard][1]:
Recreated here for ease of reference. Nobody likes PDFs.

### Table 3-6. UTF-8 Bit Distribution

|  Scalar Value             | First Byte | Second Byte | Third Byte | Fourth Byte |
|---------------------------|------------|-------------|------------|-------------|
|00000000 0xxxxxxx          | 0xxxxxxx   |             |            |             |
|00000yyy yyxxxxxx          | 110yyyyy   | 10xxxxxx    |            |             |
|zzzzyyyy yyxxxxxx          | 1110zzzz   | 10yyyyyy    | 10xxxxxx   |             |
|000uuuuu zzzzyyyy yyxxxxxx | 11110uuu   | 10uuzzzz    | 10yyyyyy   | 10xxxxxx    |

### Table 3-7. Well-Formed UTF-8 Byte Sequences

|    Code Points     | First Byte | Second Byte | Third Byte | Fourth Byte |
|--------------------|------------|-------------|------------|-------------|
| U+0000..U+007F     | 00..7F     |             |            |             |
| U+0080..U+07FF     | C2..DF     | 80..BF      |            |             |
| U+0800..U+0FFF     | E0         | _**A0**_..BF| 80..BF     |             |
| U+1000..U+CFFF     | E1..EC     | 80..BF      | 80..BF     |             |
| U+D000..U+D7FF     | ED         | 80.._**9F**_| 80..BF     |             |
| U+E000..U+FFFF     | EE..EF     | 80..BF      | 80..BF     |             |
| U+10000..U+3FFFF   | F0         | _**90**_..BF| 80..BF     | 80..BF      |
| U+40000..U+FFFFF   | F1..F3     | 80..BF      | 80..BF     | 80..BF      |
| U+100000..U+10FFFF | F4         | 80.._**8F**_| 80..BF     | 80..BF      |

[0]: https://en.wikipedia.org/wiki/UTF-16#U.2B10000_to_U.2B10FFFF
[1]: http://www.unicode.org/versions/Unicode10.0.0/ch03.pdf#page=55
