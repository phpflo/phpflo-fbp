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
     * @param bool $inline create multiple definitions in a single line
     * @return string
     */
    public static function toFbp(array $definition, $inline = false)
    {
        return self::createFbp($definition, $inline);
    }

    /**
     * @param array $definition
     * @param bool $inline
     * @return string
     */
    private static function createFbp(array $definition, $inline = false)
    {
        $fbp = [];
        return implode(self::FILE_LINEFEED, $fbp);
    }
}
