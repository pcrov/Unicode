<?php declare(strict_types = 1);

namespace pcrov\Unicode;

use pcrov\Unicode\Utf8\PcreU;
use pcrov\Unicode\Utf8\Iconv;
use pcrov\Unicode\Utf8\Pcre;
use pcrov\Unicode\Utf8\Mbstring;
use pcrov\Unicode\Utf8\Native;

final class Utf8
{
    private static $validators = [
        PcreU::class,
        Iconv::class,
        Pcre::class,
        Mbstring::class,
        Native::class,
    ];
    private static $verboseValidators = [
        Pcre::class,
        Native::class,
    ];
    private static $validator;
    private static $verboseValidator;

    private function __construct()
    {
    }

    public static function init()
    {
        foreach (self::$validators as $verboseValidator) {
            if ($verboseValidator::qualify()) {
                self::$validator = $verboseValidator;
                break;
            }
        }
        foreach (self::$verboseValidators as $verboseValidator) {
            if ($verboseValidator::qualify()) {
                self::$verboseValidator = $verboseValidator;
                break;
            }
        }
    }

    /**
     * UTF-8 state machine in recursive array form.
     *
     * @return array A recursive array in the form of:
     *     [byte => [next possible byte => ...,], ...]
     */
    public static function getByteMap() : array
    {
        return Native::getByteMap();
    }

    /**
     * @return string UTF-8. All of it. 1,112,064 codepoints.
     */
    public static function getAllCharacters() : string
    {
        return Native::getAllCharacters();
    }

    /**
     * @param string $input
     * @param bool $verbose
     * @return bool|int
     */
    public static function validate(string $input, bool $verbose = false)
    {
        if (!$verbose) {
            return (self::$validator)::validate($input);
        }

        if (self::$validator === self::$verboseValidator) {
            return (self::$verboseValidator)::validate($input, true);
        }

        // else short-circuit the common case, valid, with a quicker non-verbose validator
        return (self::$validator)::validate($input) ?: (self::$verboseValidator)::validate($input, true);
    }
}

Utf8::init();
