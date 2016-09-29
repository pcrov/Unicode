# Unicode (Work in progress)

[![Build Status](https://travis-ci.org/pcrov/Unicode.svg?branch=master)](https://travis-ci.org/pcrov/Unicode)
[![Coverage Status](https://coveralls.io/repos/github/pcrov/Unicode/badge.svg?branch=master)](https://coveralls.io/github/pcrov/Unicode?branch=master)
[![License](https://poser.pugx.org/pcrov/unicode/license)](https://github.com/pcrov/Unicode/blob/master/LICENSE)
[![Latest Stable Version](https://poser.pugx.org/pcrov/unicode/v/stable)](https://packagist.org/packages/pcrov/unicode)

Miscellaneous Unicode utility functions. No PHP extensions are required though they are encouraged.

## Drivers
Various drivers are used depending on the function and which extensions are available. The one chosen should be sane,
even for very large strings (when applicable), though might not be optimal in all cases.

If you have stricter performance requirements you should be testing and building something tailored to what you're
handling.

- Native - plain old PHP. Always available for all functions.
- PCRE - built with Unicode support or without. Preferably with. Preferably with jit enabled.
- iconv - an oldie but goodie.
- mbstring - it's mbstring.

## Data
The data directory holds two files containing all possible UTF-8 encoded characters.
One as plain text, the other as json. These are not included in packaged stable
releases but can be generated with `pcrov\Unicode\utf8_generate_all()` (returns the
plain text string.)

## Functions
TODO: document everything

Namespace `pcrov\Unicode`.

### `Utf8::getByteMap() : array`
Provides a recursive array letting you walk a (potentially endless) UTF-8
sequence byte by byte.

The map is in the form of `[byte => [next possible byte => ...,], ...]`

Example (validation):
```php
$map = Utf8::getByteMap();
$length = \strlen($input);
for ($i = 0; $i < $length; $i++) {
    $byte = $input[$i];
    if (isset($map[$byte])) {
        $map = $map[$byte];
    } else {
        throw new \Exception("Unexpected byte " . bin2hex($byte) . " at position $i");
    }
}

// Make sure we've made it back to the beginning.
if (!isset($map["\x0"])) {
    throw new \Exception("Unexpected end of sequence.");
}
```

### `Utf8::getAllCharacters() : string`
Returns a string containing every possible valid UTF-8 encoded codepoint.
All 1,112,064 of them.

Runs surprisingly quick as long as Xdebug isn't loaded.

## Excerpts from the [Unicode 9.0.0 standard][1]:
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

[1]: http://www.unicode.org/versions/Unicode9.0.0/ch03.pdf#page=54
