<?php

namespace pcrov\Unicode\Unicode;

class NativeTest extends \PHPUnit_Framework_TestCase
{
    public function testQualify()
    {
        $this->assertTrue(Native::qualify());
    }
}
