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
use PhpFlo\Exception\ParserDefinitionException;

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
     * @var int
     */
    private $linecount;

    /**
     * @var array
     */
    private $definition;

    /**
     * FbpParser constructor.
     *
     * @param string $source
     * @param array $settings optional settings for parser
     */
    public function __construct($source, $settings = [])
    {
        $this->source   = $source;
        $this->settings = array_replace_recursive(
            [],
            $settings
        );

        $this->schema = [
            'properties' => [],
            'initializers' => [],
            'processes' => [],
            'connections' => [],
        ];

        $this->definition = [];
    }

    /**
     * @return mixed
     */
    public function run()
    {
        $this->definition = $this->schema; // reset
        $this->linecount = 1;

        /*
         * split by lines, OS-independent
         * work each line and parse for definitions
         */
        foreach (preg_split('/' . self::NEWLINES . '/m', $this->source) as $line) {
            $subset = $this->examineSubset($line);
            $this->definition['connections'] = array_merge_recursive($this->definition['connections'], $subset);
            $this->linecount++;
        }

        return $this->definition;
    }

    /**
     * @param string $line
     * @return array
     * @throws ParserDefinitionException
     */
    private function examineSubset($line)
    {
        $subset = [];
        $nextSrc = null;
        $hasInitializer = false;
        if (1 == $this->linecount && 0 === strpos(trim($line), "'")) {
            $hasInitializer = true;
        }

        // subset
        foreach (explode(self::SOURCE_TARGET_SEPARATOR, $line) as $definition) {
            $resolved = [];
            if (!$hasInitializer) {
                $resolved = $this->examineDefinition($definition);
            }

            $hasInport = $this->hasValue($resolved, 'inport');
            $hasOutport = $this->hasValue($resolved, 'outport');

            //define states
            switch (true) {
                case !empty($step['data']) && ($hasInport && $hasOutport): // initializer IN
                    $nextSrc = $resolved;
                    $step['tgt'] = [
                        'process' => $resolved['process'],
                        'port' => $resolved['inport'],
                    ];
                    // multi def oneliner initializer resolved
                    array_push($this->definition['initializers'], $step);
                    $step = [];
                    break;
                case $hasInport && $hasOutport: // tgt + multi def
                    $nextSrc = $resolved;
                    $step['tgt'] = [
                        'process' => $resolved['process'],
                        'port' => $resolved['inport'],
                    ];
                    break;
                case $hasInport && $nextSrc: // fall through to manage source
                    $step['src'] = [
                        'process' => $nextSrc['process'],
                        'port' => $nextSrc['outport'],
                    ];
                    $nextSrc = null;
                case $hasInport:
                    $step['tgt'] = [
                        'process' => $resolved['process'],
                        'port' => $resolved['inport'],
                    ];
                    // resolved touple
                    if (empty($step['data'])) {
                        array_push($subset, $step);
                    } else {
                        array_push($this->definition['initializers'], $step);
                    }
                    $step = [];
                    break;
                case $hasOutport:
                    $step['src'] = [
                        'process' => $resolved['process'],
                        'port' => $resolved['outport'],
                    ];
                    break;
                case $hasInitializer: // initialization value: at the moment we only support one
                    $step['data'] = str_replace("'", '', $definition);
                    $hasInitializer = false; // reset
                    break;
                default:
                    throw new ParserDefinitionException(
                        "Line ({$this->linecount}) {$line} does not contain in or out ports!"
                    );
            }
        }

        return $subset;
    }

    /**
     * Check if array has a specific key and is not empty.
     *
     * @param array $check
     * @param string $value
     * @return bool
     */
    private function hasValue(array $check, $value)
    {
        if (empty($check[$value])) {
            return false;
        }

        return true;
    }

    /**
     * @param string $line
     * @return array
     */
    private function examineDefinition($line)
    {
        preg_match('/' . self::PROCESS_DEFINITION . '/', $line, $matches);
        foreach ($matches as $key => $value) {
            if (is_numeric($key)) {
                unset($matches[$key]);
            }
        }

        if (!empty($matches['process'])) {
            if (empty($matches['component'])) {
                $matches['component'] = $matches['process'];
            }

            $this->examineProcess($matches);
        } else {
            throw new ParserDefinitionException(
                "No process definition found in line ({$this->linecount}) {$line}"
            );
        }

        return $matches;
    }

    /**
     * Add entry to processes.
     *
     * @param array $process
     */
    private function examineProcess(array $process)
    {
        if (!isset($this->definition['processes'][$process['process']])) {
            $this->definition['processes'][$process['process']] = [
                'component' => $process['component'],
                'metadata' => [
                    'label' => $process['process'],
                ],
            ];
        }
    }
}
