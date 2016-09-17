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
class FbpDumper implements FbpDefinitionsInterface
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
        // handle initializer
        if (!empty($definition[self::INITIALIZERS_LABEL])) {
            if (empty($definition[self::INITIALIZERS_LABEL][self::DATA_LABEL])) {
                throw new DumperException("Defintion has " .
                    self::INITIALIZERS_LABEL . " but no 
                    " . self::DATA_LABEL . " node"
                );
            }

            if (empty($definition[self::INITIALIZERS_LABEL][self::TARGET_LABEL])) {
                throw new DumperException("Defintion has " .
                    self::INITIALIZERS_LABEL . " but no 
                    " . self::TARGET_LABEL . " node"
                );
            }

            array_push(
                $fbp,
                self::connectPorts(
                    $definition[self::INITIALIZERS_LABEL][self::DATA_LABEL],
                    self::examineProcess(self::TARGET_LABEL, $definition[self::INITIALIZERS_LABEL][self::TARGET_LABEL])
                )
            );
        }

        if (self::hasElement(self::PROCESSES_LABEL, $definition)) {
            self::$processes = $definition[self::PROCESSES_LABEL];
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
     * @return string
     */
    private static function examineProcess($type, array $processPart)
    {
        self::hasElement(self::PROCESS_LABEL, $processPart);
        self::hasElement(self::PORT_LABEL, $processPart);

        $meta = '';
        $inport = '';
        $outport = '';
        $process = $processPart[self::PROCESS_LABEL];
        $port = $processPart[self::PORT_LABEL];

        if (self::hasElement($process, self::$processes, false)) {
            $meta = "(" . self::$processes[$process][self::COMPONENT_LABEL] . ")";
        } else {
            throw new DumperException("{$process} is not defined in " . self::PROCESSES_LABEL);
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
                throw new DumperException("Element has no {$needle}");
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
    private function connectPorts($sourcePort, $targetPort)
    {
        return implode(
            " " . self::SOURCE_TARGET_SEPARATOR . " ",
            [
                $sourcePort,
                $targetPort
            ]
        );
    }
}
