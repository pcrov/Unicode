<?php

namespace pcrov\Unicode;

use PHPUnit\Framework\TestCase;

class utf8_get_invalid_byte_sequence_test extends TestCase
{
    public function testValidUtf8ReturnsNull()
    {
        $this->assertNull(utf8_get_invalid_byte_sequence("valid"));
    }

    public function testEmptyStringReturnsNull()
    {
        $this->assertNull(utf8_get_invalid_byte_sequence(""));
    }

    public function testGetInvalidByteSucceeds()
    {
        $this->assertSame("\xFF", utf8_get_invalid_byte_sequence("foo\xFFbar"));
    }

    public function testGetIncompleteTwoByteSequenceSucceeds()
    {
        $this->assertSame("\xC2", utf8_get_invalid_byte_sequence("foo\xC2bar"));
    }

    public function testGetIncompleteThreeByteSequenceSucceeds()
    {
        $this->assertSame("\xE0\xA0", utf8_get_invalid_byte_sequence("foo\xE0\xA0bar"));
    }

    public function testGetIncompleteFourByteSequenceSucceeds()
    {
        $this->assertSame("\xF1\x80\x80", utf8_get_invalid_byte_sequence("foo\xF1\x80\x80bar"));
    }
}
