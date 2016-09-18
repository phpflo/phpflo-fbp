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

use PhpFlo\Exception\ParserException;
use PhpFlo\Fbp\FbpParser;

class FbpParserTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleSingleDef()
    {
        // base for testing: https://github.com/flowbased/fbp/blob/master/spec/json.coffee

        $file = <<<EOF
ReadFile(ReadFile) OUT -> IN SplitbyLines(SplitStr)
ReadFile ERROR -> IN Display(Output)
SplitbyLines OUT -> IN CountLines(Counter)
CountLines COUNT -> IN Display
EOF;

        $expected = [
            'properties' => ['name' => '',],
            'initializers' => [],
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

        $parser = new FbpParser($file);
        $this->assertEquals($expected, $parser->run());
    }

    public function testMultiDefWithInitializer()
    {
        $file = <<<EOF
'8003' -> LISTEN WebServer(HTTP/Server) REQUEST -> IN Profiler(HTTP/Profiler) OUT -> IN Authentication(HTTP/BasicAuth)
Authentication() OUT -> IN GreetUser(HelloController) OUT[0] -> IN[0] WriteResponse(HTTP/WriteResponse) OUT -> IN Send(HTTP/SendResponse)
EOF;

        $expected = [
            'properties' => ['name' => '',],
            'initializers' => [
                [
                    'data' => '8003',
                    'tgt' => [
                        'process' => 'WebServer',
                        'port' => 'LISTEN',
                    ],
                ]
            ],
            'processes' => [
                'WebServer' => [
                    'component' => 'HTTP/Server',
                    'metadata' => [
                        'label' => 'HTTP/Server',
                    ],
                ],
                'Profiler' => [
                    'component' => 'HTTP/Profiler',
                    'metadata' => [
                        'label' => 'HTTP/Profiler',
                    ],
                ],
                'Authentication' => [
                    'component' => 'HTTP/BasicAuth',
                    'metadata' => [
                        'label' => 'HTTP/BasicAuth',
                    ],
                ],
                'GreetUser' => [
                    'component' => 'HelloController',
                    'metadata' => [
                        'label' => 'HelloController',
                    ],
                ],
                'WriteResponse' => [
                    'component' => 'HTTP/WriteResponse',
                    'metadata' => [
                        'label' => 'HTTP/WriteResponse',
                    ],
                ],
                'Send' => [
                    'component' => 'HTTP/SendResponse',
                    'metadata' => [
                        'label' => 'HTTP/SendResponse',
                    ],
                ],
            ],
            'connections' => [
                [
                    'src' => [
                        'process' => 'WebServer',
                        'port' => 'REQUEST',
                    ],
                    'tgt' => [
                        'process' => 'Profiler',
                        'port' => 'IN',
                    ],
                ],
                [
                    'src' => [
                        'process' => 'Profiler',
                        'port' => 'OUT',
                    ],
                    'tgt' => [
                        'process' => 'Authentication',
                        'port' => 'IN',
                    ],
                ],
                [
                    'src' => [
                        'process' => 'Authentication',
                        'port' => 'OUT',
                    ],
                    'tgt' => [
                        'process' => 'GreetUser',
                        'port' => 'IN',
                    ],
                ],
                [
                    'src' => [
                        'process' => 'GreetUser',
                        'port' => 'OUT[0]',
                    ],
                    'tgt' => [
                        'process' => 'WriteResponse',
                        'port' => 'IN[0]',
                    ],
                ],
                [
                    'src' => [
                        'process' => 'WriteResponse',
                        'port' => 'OUT',
                    ],
                    'tgt' => [
                        'process' => 'Send',
                        'port' => 'IN',
                    ],
                ],
            ],
        ];

        $parser = new FbpParser($file);
        $this->assertEquals($expected, $parser->run());
    }

    public function testSimpleWithEmptyDescription()
    {
        $file = <<<EOF
ReadTemplate(ReadFile) OUT -> TEMPLATE Render(Template)
GreetUser() DATA -> OPTIONS Render() OUT -> STRING WriteResponse()
EOF;

        $expected = [
            'properties' => ['name' => '',],
            'initializers' => [],
            'processes' => [
                'ReadTemplate' => [
                    'component' => 'ReadFile',
                    'metadata' => [
                        'label' => 'ReadFile',
                    ],
                ],
                'Render' => [
                    'component' => 'Template',
                    'metadata' => [
                        'label' => 'Template',
                    ],
                ],
                'GreetUser' => [
                    'component' => 'GreetUser',
                    'metadata' => [
                        'label' => 'GreetUser',
                    ],
                ],
                'WriteResponse' => [
                    'component' => 'WriteResponse',
                    'metadata' => [
                        'label' => 'WriteResponse',
                    ],
                ],
            ],
            'connections' => [
                [
                    'src' => [
                        'process' => 'ReadTemplate',
                        'port' => 'OUT',
                    ],
                    'tgt' => [
                        'process' => 'Render',
                        'port' => 'TEMPLATE',
                    ],
                ],
                [
                    'src' => [
                        'process' => 'GreetUser',
                        'port' => 'DATA',
                    ],
                    'tgt' => [
                        'process' => 'Render',
                        'port' => 'OPTIONS',
                    ],
                ],
                [
                    'src' => [
                        'process' => 'Render',
                        'port' => 'OUT',
                    ],
                    'tgt' => [
                        'process' => 'WriteResponse',
                        'port' => 'STRING',
                    ],
                ],
            ],
        ];

        $parser = new FbpParser($file);
        $this->assertEquals($expected, $parser->run());
    }

    public function testSingleDefWithInitializer()
    {
        $file = <<<EOF
'yadda' -> IN ReadFile(ReadFile)
ReadFile(ReadFile) OUT -> IN SplitbyLines(SplitStr)
ReadFile ERROR -> IN Display(Output)
SplitbyLines OUT -> IN CountLines(Counter)
CountLines COUNT -> IN Display
EOF;

        $expected = [
            'properties' => ['name' => '',],
            'initializers' => [
                [
                    'data' => 'yadda',
                    'tgt' => [
                        'process' => 'ReadFile',
                        'port' => 'IN',
                    ],
                ],
            ],
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

        $parser = new FbpParser();
        $this->assertEquals($expected, $parser->run($file));
    }

    /**
     * @expectedException \PhpFlo\Exception\ParserException
     */
    public function testParserException()
    {
        $parser = new FbpParser();
        $parser->run();
    }
}
