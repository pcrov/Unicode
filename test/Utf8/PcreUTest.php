<?php

namespace pcrov\Unicode\Utf8;

class PcreUTest extends \PHPUnit_Framework_TestCase
{
    public function testQualify()
    {
        $this->assertTrue(PcreU::qualify());
    }

    protected function setUp()
    {
        if (!extension_loaded('pcre')) {
            $this->markTestSkipped('The pcre extension is not available.');
            return;
        }

        if (!@preg_match('//u', '')) {
            $this->markTestSkipped('PCRE not compiled with Unicode support.');
        }
    }
}
