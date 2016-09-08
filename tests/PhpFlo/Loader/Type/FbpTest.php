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

use PhpFlo\Loader\Type\Fbp;

class FbpTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        // base for testing: https://github.com/flowbased/fbp/blob/master/spec/json.coffee

        $source = <<<EOF
ReadFile(ReadFile) OUT -> IN SplitbyLines(SplitStr)
ReadFile ERROR -> IN Display(Output)
SplitbyLines OUT -> IN CountLines(Counter)
CountLines COUNT -> IN Display
EOF;

        $expected = [

        ];

        $parser = new Fbp();
        $result = $parser->parse($source);

        $this->assertEquals($expected, $result);
    }
}
