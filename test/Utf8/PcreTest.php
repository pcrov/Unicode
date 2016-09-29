<?php

namespace pcrov\Unicode\Utf8;

class PcreTest extends \PHPUnit_Framework_TestCase
{
    public function testQualify()
    {
        $this->assertTrue(Pcre::qualify());
    }

    protected function setUp()
    {
        if (!extension_loaded('pcre')) {
            $this->markTestSkipped('The pcre extension is not available.');
            return;
        }

        $bools = [
            'on' => true,
            'yes' => true,
            'true' => true,
            'off' => false,
            'no' => false,
            'false' => false
        ];
        $jit = strtolower((string)ini_get("pcre.jit"));

        if (!($bools[$jit] ?? (bool)$jit)) {
            $this->markTestSkipped('Ini setting pcre.jit is not enabled.');
        }
    }
}
