<?php declare(strict_types = 1);

namespace pcrov\Unicode\Unicode;

final class Native
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
        if ($high < 0xd800 || $high > 0xdbff ||
            $low < 0xdc00 || $low > 0xdfff
        ) {
            return false;
        }

        return 0x10000 + (($high & 0x03ff) << 10) + ($low & 0x03ff);
    }

    public static function qualify() : bool
    {
        return true;
    }
}
