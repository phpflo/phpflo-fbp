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
}
