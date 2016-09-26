<?php declare(strict_types = 1);

namespace pcrov\Unicode;

    /*
     * |    Code Points     | First Byte | Second Byte | Third Byte | Fourth Byte |
     * |--------------------|------------|-------------|------------|-------------|
     * | U+0000..U+007F     | 00..7F     |             |            |             |
     * | U+0080..U+07FF     | C2..DF     | 80..BF      |            |             |
     * | U+0800..U+0FFF     | E0         | A0..BF      | 80..BF     |             |
     * | U+1000..U+CFFF     | E1..EC     | 80..BF      | 80..BF     |             |
     * | U+D000..U+D7FF     | ED         | 80..9F      | 80..BF     |             |
     * | U+E000..U+FFFF     | EE..EF     | 80..BF      | 80..BF     |             |
     * | U+10000..U+3FFFF   | F0         | 90..BF      | 80..BF     | 80..BF      |
     * | U+40000..U+FFFFF   | F1..F3     | 80..BF      | 80..BF     | 80..BF      |
     * | U+100000..U+10FFFF | F4         | 80..8F      | 80..BF     | 80..BF      |
     *
     */

/**
 * Provides a map letting you validate a (potentially endless) UTF-8
 * sequence byte by byte.
 *
 * @return array A recursive array in the form of:
 *     [byte => [next possible byte => ...,], ...]
 */
function utf8_byte_map() : array
{
    static $utf8 = [];

    if (!empty($utf8)) {
        return $utf8;
    }

    $u40000_uFFFFF_2nd_byte = [];
    $penultimate = [];
    $last = [];

    // Common final bytes
    foreach (range("\x80", "\xBF") as $byte) {
        $last[$byte] = &$utf8;
    }

    // Common second to last bytes
    foreach (range("\x80", "\xBF") as $byte) {
        $penultimate[$byte] = &$last;
    }

    // U+0000..U+007F
    foreach (range("\x0", "\x7F") as $byte) {
        $utf8[$byte] = &$utf8;
    }

    // U+0080..U+07FF
    foreach (range("\xC2", "\xDF") as $byte) {
        $utf8[$byte] = &$last;
    }

    // U+0800..U+0FFF
    foreach (range("\xA0", "\xBF") as $byte) {
        $utf8["\xE0"][$byte] = &$last;
    }

    // U+1000..U+CFFF
    foreach (range("\xE1", "\xEC") as $byte) {
        $utf8[$byte] = &$penultimate;
    }

    // U+D000..U+D7FF
    foreach (range("\x80", "\x9F") as $byte) {
        $utf8["\xED"][$byte] = &$last;
    }

    // U+E000..U+FFFF
    foreach (range("\xEE", "\xEF") as $byte) {
        $utf8[$byte] = &$penultimate;
    }

    // U+10000..U+3FFFF
    foreach (range("\x90", "\xBF") as $byte) {
        $utf8["\xF0"][$byte] = &$penultimate;
    }

    // U+40000..U+FFFFF
    foreach (range("\xF1", "\xF3") as $byte) {
        $utf8[$byte] = &$u40000_uFFFFF_2nd_byte;
    }

    // U+40000..U+FFFFF Second byte
    foreach (range("\x80", "\xBF") as $byte) {
        $u40000_uFFFFF_2nd_byte[$byte] = &$penultimate;
    }

    // U+100000..U+10FFFF
    foreach (range("\x80", "\x8F") as $byte) {
        $utf8["\xF4"][$byte] = &$penultimate;
    }

    return $utf8;
}

/**
 * Provides a map letting you validate a single UTF-8 character
 * sequence byte by byte.
 *
 * @return array In the form of:
 *     [byte => [next possible byte => ...,], ...]
 *     The value for the last byte in a character will be false.
 */
function utf8_char_byte_map() : array
{
    static $utf8 = [];

    if (!empty($utf8)) {
        return $utf8;
    }

    $u40000_uFFFFF_2nd_byte = [];
    $penultimate = [];
    $last = [];

    // Common final bytes
    foreach (range("\x80", "\xBF") as $byte) {
        $last[$byte] = false;
    }

    // Common second to last bytes
    foreach (range("\x80", "\xBF") as $byte) {
        $penultimate[$byte] = &$last;
    }

    // U+0000..U+007F
    foreach (range("\x0", "\x7F") as $byte) {
        $utf8[$byte] = false;
    }

    // U+0080..U+07FF
    foreach (range("\xC2", "\xDF") as $byte) {
        $utf8[$byte] = &$last;
    }

    // U+0800..U+0FFF
    foreach (range("\xA0", "\xBF") as $byte) {
        $utf8["\xE0"][$byte] = &$last;
    }

    // U+1000..U+CFFF
    foreach (range("\xE1", "\xEC") as $byte) {
        $utf8[$byte] = &$penultimate;
    }

    // U+D000..U+D7FF
    foreach (range("\x80", "\x9F") as $byte) {
        $utf8["\xED"][$byte] = &$last;
    }

    // U+E000..U+FFFF
    foreach (range("\xEE", "\xEF") as $byte) {
        $utf8[$byte] = &$penultimate;
    }

    // U+10000..U+3FFFF
    foreach (range("\x90", "\xBF") as $byte) {
        $utf8["\xF0"][$byte] = &$penultimate;
    }

    // U+40000..U+FFFFF
    foreach (range("\xF1", "\xF3") as $byte) {
        $utf8[$byte] = &$u40000_uFFFFF_2nd_byte;
    }

    // U+40000..U+FFFFF Second byte
    foreach (range("\x80", "\xBF") as $byte) {
        $u40000_uFFFFF_2nd_byte[$byte] = &$penultimate;
    }

    // U+100000..U+10FFFF
    foreach (range("\x80", "\x8F") as $byte) {
        $utf8["\xF4"][$byte] = &$penultimate;
    }

    return $utf8;
}

/**
 * Recursive function to generate all possible UTF-8 encoded characters.
 *
 * @param null $graph Do not pass this, it's used when the function calls itself.
 * @param string $prefix Do not pass this, it's used when the function calls itself.
 * @return string UTF-8. All of it. 1,112,064 codepoints.
 */
function utf8_generate_all_characters($graph = null, $prefix = '') : string
{
    if ($graph === null) {
        $graph = utf8_char_byte_map();
    }
    $out = '';
    foreach ($graph as $byte => $next) {
        if ($next) {
            $out .= utf8_generate_all_characters($next, $prefix . $byte);
        } else {
            $out .= $prefix . $byte;
        }
    }
    return $out;
}
