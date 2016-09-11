<?php
/*
 * This file is part of the phpflo\phpflo-fbp package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\PhpFlo\Fbp;

use PhpFlo\Fbp\FbpParser;

class FbpParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        // base for testing: https://github.com/flowbased/fbp/blob/master/spec/json.coffee

        $file1 = <<<EOF
ReadFile(ReadFile) OUT -> IN SplitbyLines(SplitStr)
ReadFile ERROR -> IN Display(Output)
SplitbyLines OUT -> IN CountLines(Counter)
CountLines COUNT -> IN Display
EOF;

        $file2 = <<<EOF
'8003' -> LISTEN WebServer(HTTP/Server) REQUEST -> IN Profiler(HTTP/Profiler) OUT -> IN Authentication(HTTP/BasicAuth)
Authentication() OUT -> IN GreetUser(HelloController) OUT[0] -> IN[0] WriteResponse(HTTP/WriteResponse) OUT -> IN Send(HTTP/SendResponse)
EOF;

        $file3 = <<<EOF
ReadTemplate(ReadFile) OUT -> TEMPLATE Render(Template)
GreetUser() DATA -> OPTIONS Render() OUT -> STRING WriteResponse()
EOF;

        $file4 = <<<EOF
'yadda' -> IN ReadFile(ReadFile)
ReadFile(ReadFile) OUT -> IN SplitbyLines(SplitStr)
ReadFile ERROR -> IN Display(Output)
SplitbyLines OUT -> IN CountLines(Counter)
CountLines COUNT -> IN Display
EOF;


        $expected1 = [
            'properties' => [],
            'processes' => [
                'ReadFile' => [
                    'component' => 'ReadFile',
                    'metadata' => [
                        'label' => 'ReadFile',
                    ],
                ],
                'SplitbyLines' => [
                    'component' => 'SplitStr',
                    'metadata' => [
                        'label' => 'SplitStr',
                    ],
                ],
                'Display' => [
                    'component' => 'Output',
                    'metadata' => [
                        'label' => 'Output',
                    ],
                ],
                'CountLines' => [
                    'component' => 'Counter',
                    'metadata' => [
                        'label' => 'Counter',
                    ],
                ]
            ],
            'connections' => [
                [
                    'src' => [
                        'process' => 'ReadFile',
                        'port' => 'OUT',
                    ],
                    'tgt' => [
                        'process' => 'SplitbyLines',
                        'port' => 'IN',
                    ],
                ],
                [
                    'src' => [
                        'process' => 'ReadFile',
                        'port' => 'ERROR',
                    ],
                    'tgt' => [
                        'process' => 'Display',
                        'port' => 'IN',
                    ],
                ],
                [
                    'src' => [
                        'process' => 'SplitbyLines',
                        'port' => 'OUT',
                    ],
                    'tgt' => [
                        'process' => 'CountLines',
                        'port' => 'IN',
                    ],
                ],
                [
                    'src' => [
                        'process' => 'CountLines',
                        'port' => 'COUNT',
                    ],
                    'tgt' => [
                        'process' => 'Display',
                        'port' => 'IN',
                    ],
                ],
            ],
        ];

        $expected2 = [];

        $expected3 = [];

        $expected4 = [];

//        $parser = new FbpParser($file1);
//        $this->assertEquals($expected1, $parser->run());

        $parser = new FbpParser($file2);
        $this->assertEquals($expected2, $parser->run());
/*
        $parser = new FbpParser($file3);
        $this->assertEquals($expected3, $parser->run());

        $parser = new FbpParser($file4);
        $this->assertEquals($expected3, $parser->run());

*/
    }
}
