<?php
/*
 * This file is part of the <package> package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpFlo\Common;

/**
 * Interface DefinitionInterface
 *
 * @package PhpFlo\Common
 * @author Marc Aschmann <maschmann@gmail.com>
 */
interface DefinitionInterface
{
    /**
     * @param array $definition
     * @return $this
     */
    public function definition(array $definition);

    /**
     * @return array
     */
    public function properties();

    /**
     * @return array
     */
    public function initializers();

    /**
     * @return array
     */
    public function processes();

    /**
     * @return array
     */
    public function connections();

    /**
     * @return array
     */
    public function toArray();

    /**
     * @return string
     */
    public function toJson();

    /**
     * @return string
     */
    public function toYaml();

    /**
     * @return string
     */
    public function toFbp();

    /**
     * @return string
     */
    public function name();
}
