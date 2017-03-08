<?php

namespace pcrov\Unicode;

use PHPUnit\Framework\TestCase;

class utf8_generate_all_code_points_test extends TestCase
{
    public function testAllCodePointsGenerate()
    {
        $this->assertStringEqualsFile(__DIR__ . "/data/UTF-8_all.txt", utf8_generate_all_code_points());
    }
}
