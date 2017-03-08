<?php

namespace pcrov\Unicode;

use PHPUnit\Framework\TestCase;

class surrogate_pair_to_code_point_test extends TestCase
{
    public function testValidSurrogatePairReturnsProperValue()
    {
        $this->assertSame(0x24B62, surrogate_pair_to_code_point(0xD852, 0xDF62));
    }

    public function testTooLowHighSurrogateThrows()
    {
        $this->expectException(\OutOfRangeException::class);
        surrogate_pair_to_code_point(0xd7ff, 0xdc00);
    }

    public function testTooHighHighSurrogateThrows()
    {
        $this->expectException(\OutOfRangeException::class);
        surrogate_pair_to_code_point(0xdc00, 0xdc00);
    }

    public function testTooLowLowSurrogateThrows()
    {
        $this->expectException(\OutOfRangeException::class);
        surrogate_pair_to_code_point(0xd800, 0xdbff);
    }

    public function testTooHighLowSurrogateThrows()
    {
        $this->expectException(\OutOfRangeException::class);
        surrogate_pair_to_code_point(0xd800, 0xc000);
    }
}
