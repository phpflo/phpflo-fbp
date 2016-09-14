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
 * Class DefinitionsTrait
 *
 * @package PhpFlo\Common
 * @author Marc Aschmann <maschmann@gmail.com>
 */
interface FbpDefinitionsInterface
{
    const SOURCE_TARGET_SEPARATOR = '->';
    const PROCESS_DEFINITION = '((?P<inport>[A-Z]+(\[(?P<inport_no>[0-9]+)\])?)\s)?((?P<process>[\w\/]+)(\((?P<component>[\w\/\\\.]+)?\))?)(\s(?P<outport>[A-Z]+(\[(?P<outport_no>[0-9]+)\])?))?';
    const NEWLINES = '$\R?^';
}
