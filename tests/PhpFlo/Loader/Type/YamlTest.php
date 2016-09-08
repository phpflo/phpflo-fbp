<?php
/*
 * This file is part of the phpflo\phpflo-fbp package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\PhpFlo\Loader\Type;

use PhpFlo\Loader\Type\Yaml;

class YamlTest extends \PHPUnit_Framework_TestCase
{
    public function testInit()
    {
        $yaml = <<<EOT
test_key: testvalue
test_key2:
  test_subkey: 1234
EOT;

        $source = [
            'test_key' => 'testvalue',
            'test_key2' => [
                 'test_subkey' => 1234,
            ],
        ];

        $parser = new Yaml();
        $result = $parser->parse($yaml);

        $this->assertEquals($source, $result);
    }
}
