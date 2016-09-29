<?php declare(strict_types = 1);

namespace pcrov\Unicode\Utf8;

final class Mbstring
{
    private function __construct()
    {
    }

    public static function validate(string $input) : bool
    {
        return mb_check_encoding($input, "UTF-8");
    }

    public static function qualify() : bool
    {
        return extension_loaded("mbstring");
    }
}
