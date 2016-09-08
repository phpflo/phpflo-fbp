<?php
namespace Tests\PhpFlo\Loader;

use PhpFlo\Loader\Loader;

class LoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testStaticLoad()
    {
        $data = Loader::load('test.yml');
    }
}
