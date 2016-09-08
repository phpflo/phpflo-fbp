<?php
/*
 * This file is part of the <package> package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\PhpFlo\Loader\Type;

use PhpFlo\Loader\Type\Json;

class JsonTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $source = [
            'i_am_some_test' => [
                'subkey' => 'testvalue',
            ],
            'testkey2' => 'testval2',
        ];
        $json = json_encode($source);

        $loader = new Json();
        $result = $loader->parse($json);

        $this->assertEquals($source, $result);
    }
}
