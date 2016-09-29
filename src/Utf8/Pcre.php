<?php declare(strict_types = 1);

namespace pcrov\Unicode\Utf8;

/**
 * Class Pcre
 *
 * PCRE driver. Doesn't require PCRE built with Unicode support, but does require that jit is enabled.
 *
 * @package pcrov\Unicode\Utf8
 */
final class Pcre
{
    // Faster expressions are possible but this is safe from backtracking
    // limits and such on very long strings.
    private static $invalidUtf8Regex = <<<'REGEX'
        /
        (?(DEFINE)
            (?<valid>
                [\x00-\x7F]                            |  # U+0000..U+007F
                [\xC2-\xDF] [\x80-\xBF]                |  # U+0080..U+07FF
                \xE0        [\xA0-\xBF] [\x80-\xBF]    |  # U+0800..U+0FFF
                [\xE1-\xEC] [\x80-\xBF]{2}             |  # U+1000..U+CFFF
                \xED        [\x80-\x9F] [\x80-\xBF]    |  # U+D000..U+D7FF
                [\xEE-\xEF] [\x80-\xBF]{2}             |  # U+E000..U+FFFF
                \xF0        [\x90-\xBF] [\x80-\xBF]{2} |  # U+10000..U+3FFFF
                [\xF1-\xF3] [\x80-\xBF]{3}             |  # U+40000..U+FFFFF
                \xF4        [\x80-\x8F] [\x80-\xBF]{2} |  # U+100000..U+10FFFF
                \Z
            )
        )
        \A(?!(?&valid)) |
        (?&valid)(?!(?&valid))
        /x
REGEX;

    private function __construct()
    {
    }

    public static function validate(string $input, bool $verbose = false)
    {
        $valid = !preg_match(self::$invalidUtf8Regex, $input, $matches, PREG_OFFSET_CAPTURE);
        if (!$verbose || $valid) {
            return $valid;
        }
        return \strlen($matches[0][0]) + $matches[0][1] + 1;
    }

    public static function qualify() : bool
    {
        $bools = [
            'on' => true,
            'yes' => true,
            'true' => true,
            'off' => false,
            'no' => false,
            'false' => false
        ];
        $jit = strtolower((string)ini_get("pcre.jit"));

        return extension_loaded("pcre") && ($bools[$jit] ?? (bool)$jit);
    }
}
