<?php
/*
 * This file is part of the phpflo\phpflo-fbp package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PhpFlo\Loader\Type;

use PhpFlo\Common\LoaderInterface;
use Symfony\Component\Yaml\Yaml as YamlLoader;

/**
 * Class Yaml
 *
 * @package PhpFlo\Loader\Type
 * @author Marc Aschmann <maschmann@gmail.com>
 */
class Yaml implements LoaderInterface
{
    /**
     * @param string $input
     * @return mixed
     */
    public function parse($input)
    {
        return YamlLoader::parse($input);
    }
}
