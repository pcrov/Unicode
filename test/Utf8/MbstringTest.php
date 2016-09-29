<?php

namespace pcrov\Unicode\Utf8;

class MbstringTest extends \PHPUnit_Framework_TestCase
{
    public function testQualify()
    {
        $this->assertTrue(Mbstring::qualify());
    }

    protected function setUp()
    {
        if (!extension_loaded('mbstring')) {
            $this->markTestSkipped('The mbstring extension is not available.');
        }
    }
}
