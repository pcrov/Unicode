<?php

namespace pcrov\Unicode;

use PHPUnit\Framework\TestCase;

class utf8_validate_test extends TestCase
{

    public function testValidateEmptyStringReturnsTrue()
    {
        $this->assertTrue(utf8_validate(""));
    }

    public function testValidateAllUtf8ReturnsTrue()
    {
        $this->assertTrue(utf8_validate(\file_get_contents(__DIR__ . "/data/UTF-8_all.txt")));
    }

    public function testValidateInvalidSingleByteReturnsFalse()
    {
        $this->assertFalse(utf8_validate("\xC1"));
    }

    public function testValidateIncompleteByteSequenceReturnsFalse()
    {
        $this->assertFalse(utf8_validate("\xE0\xA0"));
    }

    public function testValidateHighSurrogateReturnsFalse()
    {
        $this->assertFalse(utf8_validate("\u{D852}"));
    }

    public function testValidateLowSurrogateReturnsFalse()
    {
        $this->assertFalse(utf8_validate("\u{DF62}"));
    }

    public function testValidateSurrogatePairReturnsFalse()
    {
        $this->assertFalse(utf8_validate("\u{D852}\u{DF62}"));
    }

    public function testValidateOutOfRangeCodePointReturnsFalse()
    {
        // This would be \u{110000}
        $this->assertFalse(utf8_validate("\xF4\x8F\xBF\xC0"));
    }
}
