<?php

namespace pcrov\Unicode;

use PHPUnit\Framework\TestCase;

class utf8_get_state_machine_test extends TestCase
{
    public function testStateMachineGeneratesSuccessfully()
    {
        $this->assertStringEqualsFile(
            __DIR__ . "/data/serialized_utf8_state_machine",
            \serialize(utf8_get_state_machine())
        );
    }
}
