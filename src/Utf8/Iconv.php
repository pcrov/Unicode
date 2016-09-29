<?php declare(strict_types = 1);

namespace pcrov\Unicode\Utf8;

final class Iconv
{
    private function __construct()
    {
    }

    public static function validate(string $input) : bool
    {
        return @iconv("UTF-8", "UTF-8", $input) !== false;
    }

    public static function qualify() : bool
    {
        return extension_loaded("iconv");
    }
}
