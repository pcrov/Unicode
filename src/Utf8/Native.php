<?php declare(strict_types = 1);

namespace pcrov\Unicode\Utf8;

use pcrov\Unicode\Utf8;

final class Native
{
    private static $byteMap;

    private function __construct()
    {
    }

    /**
     * UTF-8 state machine in recursive array form.
     *
     * @return array A recursive array in the form of:
     *     [byte => [next possible byte => ...,], ...]
     */
    public static function getByteMap() : array
    {
        if (!isset(self::$byteMap)) {
            self::generateByteMap();
        }
        return self::$byteMap;
    }

    /**
     * @return string UTF-8. All of it. 1,112,064 codepoints.
     */
    public static function getAllCharacters() : string
    {
        return self::generateAllCharacters();
    }

    public static function validate(string $input, bool $verbose = false)
    {
        $map = Utf8::getByteMap();
        $length = \strlen($input);
        $position = 0; // position within a byte sequence

        for ($i = 0; $i < $length; $i++) {
            $position = isset($map["\x0"]) ? 0 : ++$position;
            $byte = $input[$i];
            if (isset($map[$byte])) {
                $map = $map[$byte];
            } else {
                return $verbose ? 1 + $i - $position : false;
            }
        }

        // Make sure we've looped around back to the beginning.
        if (!isset($map["\x0"])) {
            return $verbose ? $length - $position : false;
        }

        return true;
    }

    public static function qualify() : bool
    {
        return true;
    }

    private static function generateByteMap()
    {
        $start = [];
        $u40000_uFFFFF_2nd_byte = [];
        $penultimate = [];
        $last = [];

        // Common final bytes
        foreach (range("\x80", "\xBF") as $byte) {
            $last[$byte] = &$start;
        }

        // Common second to last bytes
        foreach (range("\x80", "\xBF") as $byte) {
            $penultimate[$byte] = &$last;
        }

        // U+0000..U+007F
        foreach (range("\x0", "\x7F") as $byte) {
            $start[$byte] = &$start;
        }

        // U+0080..U+07FF
        foreach (range("\xC2", "\xDF") as $byte) {
            $start[$byte] = &$last;
        }

        // U+0800..U+0FFF
        foreach (range("\xA0", "\xBF") as $byte) {
            $start["\xE0"][$byte] = &$last;
        }

        // U+1000..U+CFFF
        foreach (range("\xE1", "\xEC") as $byte) {
            $start[$byte] = &$penultimate;
        }

        // U+D000..U+D7FF
        foreach (range("\x80", "\x9F") as $byte) {
            $start["\xED"][$byte] = &$last;
        }

        // U+E000..U+FFFF
        foreach (range("\xEE", "\xEF") as $byte) {
            $start[$byte] = &$penultimate;
        }

        // U+10000..U+3FFFF
        foreach (range("\x90", "\xBF") as $byte) {
            $start["\xF0"][$byte] = &$penultimate;
        }

        // U+40000..U+FFFFF
        foreach (range("\xF1", "\xF3") as $byte) {
            $start[$byte] = &$u40000_uFFFFF_2nd_byte;
        }

        // U+40000..U+FFFFF Second byte
        foreach (range("\x80", "\xBF") as $byte) {
            $u40000_uFFFFF_2nd_byte[$byte] = &$penultimate;
        }

        // U+100000..U+10FFFF
        foreach (range("\x80", "\x8F") as $byte) {
            $start["\xF4"][$byte] = &$penultimate;
        }

        self::$byteMap = $start;
    }

    private static function generateAllCharacters($graph = null, $prefix = '') : string
    {
        if ($graph === null) {
            $graph = self::getByteMap();
        }

        // We've looped back around.
        if ($prefix !== '' && isset($graph["\x0"])) {
            return $prefix;
        }

        $out = '';
        foreach ($graph as $byte => $next) {
            $out .= self::generateAllCharacters($next, $prefix . $byte);
        }
        return $out;
    }
}
