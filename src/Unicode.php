<?php declare(strict_types = 1);

namespace pcrov\Unicode;

use pcrov\Unicode\Unicode\Native;

final class Unicode
{
    private function __construct()
    {
    }

    /**
     * Translates a UTF-16 surrogate pair into a single codepoint.
     *
     * Example: \uD852\uDF62 == \u{24B62} == 𤭢
     *
     * @param int $high high surrogate
     * @param int $low low surrogate
     * @return int|false The translated codepoint or false if either surrogate is out of range.
     */
    public static function translateFromSurrogatePair(int $high, int $low)
    {
        return Native::translateFromSurrogatePair($high, $low);
    }
}
