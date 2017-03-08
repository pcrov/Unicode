<?php

namespace pcrov\Unicode;

use PHPUnit\Framework\TestCase;

class utf8_find_invalid_byte_sequence_test extends TestCase
{
    public function testValidUtf8ReturnsNull()
    {
        $this->assertNull(utf8_find_invalid_byte_sequence("valid"));
    }

    public function testEmptyStringReturnsNull()
    {
        $this->assertNull(utf8_find_invalid_byte_sequence(""));
    }

    public function testFindInvalidByteSucceeds()
    {
        $this->assertSame(3, utf8_find_invalid_byte_sequence("foo\xC0bar"));
    }

    public function testFindIncompleteUtf8SequenceSucceeds()
    {
        $this->assertSame(3, utf8_find_invalid_byte_sequence("foo\xE0\xA0bar"));
    }
}
