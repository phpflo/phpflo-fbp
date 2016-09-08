<?php
/*
 * This file is part of the phpflo\phpflo-fbp package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PhpFlo\Fbp;

use PhpFlo\Common\FbpDefinitionsInterface;

/**
 * Class FbpParser
 *
 * @package PhpFlo\Parser
 * @author Marc Aschmann <maschmann@gmail.com>
 */
class FbpParser implements FbpDefinitionsInterface
{

    /**
     * @var string
     */
    private $source;

    /**
     * @var array
     */
    private $settings;

    /**
     * @var array
     */
    private $schema;

    /**
     * FbpParser constructor.
     *
     * @param string $source
     * @param array $settings optional settings for parser
     */
    public function __construct($source, $settings = [])
    {
        $this->source = $source;
        $this->settings = array_replace_recursive(
            [],
            $settings
        );

        $this->schema = [
            'properties' => [],
            'processes' => [],
            'connections' => [],
        ];
    }

    /**
     * @return mixed
     */
    public function run()
    {
        $definition = [];

        // split by lines, OS-independent
        $lines = preg_split ('/$\R?^/m', $this->source);

        foreach ($lines as $line) {
            $part = $this->examine($line);
        }

        return $definition;
    }

    /**
     * @param string $line
     * @return array
     */
    private function examine($line)
    {
        $part = [
            'processes' => [],
            'connections' => [],
        ];



        return $part;
    }

    private function extractConnectionPart($part)
    {
        return [
            'process' => $process,
            'port => 
        ]
    }
}
