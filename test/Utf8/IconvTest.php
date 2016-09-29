<?php

namespace pcrov\Unicode\Utf8;

class IconvTest extends \PHPUnit_Framework_TestCase
{
    public function testQualify()
    {
        $this->assertTrue(Iconv::qualify());
    }

    protected function setUp()
    {
        if (!extension_loaded('iconv')) {
            $this->markTestSkipped('The iconv extension is not available.');
        }
    }
}
