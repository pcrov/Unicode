<?php

namespace pcrov\Unicode\Utf8;

class NativeTest extends \PHPUnit_Framework_TestCase
{
    public function testQualify()
    {
        $this->assertTrue(Native::qualify());
    }

    public function testGetByteMap()
    {
        $this->assertStringEqualsFile(__DIR__ . "/../data/utf8bytemap", serialize(Native::getByteMap()));
    }

    public function testGetAllCharacters()
    {
        $this->assertStringEqualsFile(__DIR__ . "/../../data/UTF-8_all.txt", Native::getAllCharacters());
    }
}
