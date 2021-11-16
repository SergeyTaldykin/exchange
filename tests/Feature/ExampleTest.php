<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @dataProvider textExampleProvider
     * @return void
     */
    public function test_example2($a, $b)
    {
        $this->assertTrue($a == $b);
    }

    /**
     *
     */
    public function textExampleProvider()
    {
        return [
            [1, 2],
            [2, 2.0],
            [0, ''],
        ];
    }
}
