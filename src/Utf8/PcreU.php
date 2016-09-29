<?php declare(strict_types = 1);

namespace pcrov\Unicode\Utf8;

/**
 * Class PcreU
 *
 * PCRE driver. Requires PCRE built with Unicode support.
 *
 * @package pcrov\Unicode\Utf8
 */
final class PcreU
{
    private function __construct()
    {
    }

    public static function validate(string $input) : bool
    {
        return (bool)preg_match('//u', $input);
    }

    public static function qualify() : bool
    {
        return extension_loaded("pcre") && @preg_match('//u', '');
    }
}
