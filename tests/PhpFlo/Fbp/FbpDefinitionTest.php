<?php
namespace Tests\PhpFlo\Fbp;

use PhpFlo\Fbp\FbpDefinition;

class FbpDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $definition = new FbpDefinition();
        $this->assertInstanceOf('PhpFlo\Fbp\FbpDefinition', $definition);
        $this->assertEquals(
            [
                'properties' => [
                    'name' => '',
                ],
                'initializers' => [],
                'processes' => [],
                'connections' => [],
            ],
            $definition->toArray()
        );
    }
}
