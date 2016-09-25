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
use PhpFlo\Exception\DumperException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class FbpDumper
 *
 * @package PhpFlo\Fbp
 * @author Marc Aschmann <maschmann@gmail.com>
 */
final class FbpDumper implements FbpDefinitionsInterface
{
    /**
     * @var array
     */
    private static $processes;

    /**
     * @param array $definition
     * @return string json
     */
    public static function toJson(array $definition)
    {
        return json_encode($definition, JSON_PRETTY_PRINT);
    }

    /**
     * @param array $definition
     * @param int $inline level until inlining starts
     * @return string yaml
     */
    public static function toYaml(array $definition, $inline = 3)
    {
        return Yaml::dump($definition, $inline);
    }

    /**
     * @param array $definition
     * @return string
     */
    public static function toFbp(array $definition)
    {
        return self::createFbp($definition);
    }

    /**
     * @param array $definition
     * @return string
     */
    private static function createFbp(array $definition)
    {
        $fbp = [];

        // first check for process definitions
        if (self::hasElement(self::PROCESSES_LABEL, $definition)) {
            self::$processes = $definition[self::PROCESSES_LABEL];
        }

        // handle initializer
        if (!empty($definition[self::INITIALIZERS_LABEL])) {
            foreach ($definition[self::INITIALIZERS_LABEL] as $initializer) {
                if (empty($initializer[self::DATA_LABEL])) {
                    self::throwDumperException('no_definition', self::DATA_LABEL);
                }
                if (empty($initializer[self::TARGET_LABEL])) {
                    self::throwDumperException('no_definition', self::TARGET_LABEL);
                }
                array_push(
                    $fbp,
                    self::connectPorts(
                        $initializer[self::DATA_LABEL],
                        self::examineProcess(self::TARGET_LABEL, $initializer[self::TARGET_LABEL])
                    )
                );
            }
        }

        foreach ($definition[self::CONNECTIONS_LABEL] as $connection) {
            array_push($fbp, self::examineConnectionTouple($connection));
        }

        return implode(self::FILE_LINEFEED, $fbp);
    }

    /**
     * Look for all needed fields and build a port -> port connection.
     *
     * @param array $connectionTouple
     * @return string
     */
    private static function examineConnectionTouple(array $connectionTouple)
    {
        self::hasElement(self::SOURCE_LABEL, $connectionTouple);
        self::hasElement(self::TARGET_LABEL, $connectionTouple);

        return self::connectPorts(
            self::examineProcess(self::SOURCE_LABEL, $connectionTouple[self::SOURCE_LABEL]),
            self::examineProcess(self::TARGET_LABEL, $connectionTouple[self::TARGET_LABEL])
        );
    }

    /**
     * @param string $type
     * @param array $processPart
     * @throws DumperException
     * @return string
     */
    private static function examineProcess($type, array $processPart)
    {
        self::hasElement(self::PROCESS_LABEL, $processPart);
        self::hasElement(self::PORT_LABEL, $processPart);

        $inport = '';
        $outport = '';
        $process = $processPart[self::PROCESS_LABEL];
        $port = $processPart[self::PORT_LABEL];

        if (self::hasElement($process, self::$processes, false)) {
            $meta = "(" . self::$processes[$process][self::COMPONENT_LABEL] . ")";
        } else {
            self::throwDumperException('process', $process);
        }

        if (self::SOURCE_LABEL == $type) {
            $outport = " {$port}";
        } else {
            $inport = "{$port} ";
        }

        return "{$inport}{$process}{$meta}{$outport}";
    }

    /**
     * @param string $needle
     * @param array $haystack
     * @param bool $triggerException
     * @return bool
     */
    private static function hasElement($needle, array $haystack, $triggerException = true)
    {
        if (empty($haystack[$needle])) {
            if ($triggerException) {
                self::throwDumperException('elmeent', $needle);
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $sourcePort
     * @param string $targetPort
     * @return string
     */
    private static function connectPorts($sourcePort, $targetPort)
    {
        return implode(
            " " . self::SOURCE_TARGET_SEPARATOR . " ",
            [
                $sourcePort,
                $targetPort
            ]
        );
    }

    private static function throwDumperException($type, $value)
    {
        switch ($type) {
            case 'element':
                throw new DumperException("Element has no {$value}");
                break;
            case 'process':
                throw new DumperException("{$value} is not defined in " . self::PROCESSES_LABEL);
                break;
            case 'no_definition':
                throw new DumperException("Defintion has " .
                    self::INITIALIZERS_LABEL . " but no {$value} node"
                );
                break;
        }
    }
}
