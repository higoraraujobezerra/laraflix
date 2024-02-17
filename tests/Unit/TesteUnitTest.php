<?php

namespace Tests\Unit;

use Core\Teste;
use PHPUnit\Framework\TestCase;

class TesteUnitTest extends TestCase
{
    public function test_call_method_foo()
    {
        $test = new Teste();
        $response = $test->foo();

        $this->assertEquals('123', $response);
    }
}
