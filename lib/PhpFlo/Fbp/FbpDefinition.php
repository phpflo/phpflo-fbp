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

use PhpFlo\Common\DefinitionInterface;
use PhpFlo\Common\FbpDefinitionsInterface;

/**
 * Class FbpDefinition
 *
 * @package PhpFlo\Fbp
 * @author Marc Aschmann <maschmann@gmail.com>
 */
class FbpDefinition implements DefinitionInterface, FbpDefinitionsInterface
{
    /**
     * @var array
     */
    private $schema;

    public function __construct()
    {
        $this->schema = [
            self::PROPERTIES_LABEL => [
                'name' => '',
            ],
            self::INITIALIZERS_LABEL => [],
            self::PROCESSES_LABEL => [],
            self::CONNECTIONS_LABEL => [],
        ];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->schema;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return FbpDumper::toJson($this->schema);
    }

    /**
     * @return string
     */
    public function toYaml()
    {
        return FbpDumper::toYaml($this->schema);
    }

    /**
     * @return string
     */
    public function toFbp()
    {
        return FbpDumper::toFbp($this->schema);
    }
}
