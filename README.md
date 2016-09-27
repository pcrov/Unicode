# Unicode
Miscellaneous Unicode utility functions.

## Data
The data directory holds two files containing all possible UTF-8 encoded characters.
One as plain text, the other as json. These are not included in packaged stable
releases but can be generated with `pcrov\Unicode\utf8_generate_all()` (returns the
plain text string.)

## Functions
All functions are in the namespace `pcrov\Unicode`.

### `utf8_byte_map() : array`
Provides a recursive array letting you walk a (potentially endless) UTF-8
sequence byte by byte.

The map is in the form of `[byte => [next possible byte => ...,], ...]`

Example (validation):
```php
$map = utf8_byte_map();
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

### `utf8_char_byte_map() : array`
Similar to the above, though not recursive - only traverses a single character, at the end of which is `false`.

See the source for the provided `utf8_generate_all()` for example use.

### `utf8_generate_all_characters() : string`
Returns a string containing every possible valid UTF-8 encoded codepoint.
All 1,112,064 of them.

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
| U+0800..U+0FFF     | E0         | A0..BF      | 80..BF     |             |
| U+1000..U+CFFF     | E1..EC     | 80..BF      | 80..BF     |             |
| U+D000..U+D7FF     | ED         | 80..9F      | 80..BF     |             |
| U+E000..U+FFFF     | EE..EF     | 80..BF      | 80..BF     |             |
| U+10000..U+3FFFF   | F0         | 90..BF      | 80..BF     | 80..BF      |
| U+40000..U+FFFFF   | F1..F3     | 80..BF      | 80..BF     | 80..BF      |
| U+100000..U+10FFFF | F4         | 80..8F      | 80..BF     | 80..BF      |

[1]: http://www.unicode.org/versions/Unicode9.0.0/ch03.pdf#page=54
