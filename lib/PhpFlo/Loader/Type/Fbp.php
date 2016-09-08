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
use PhpFlo\Parser\FbpParser;

/**
 * Class Fbp
 *
 * @package PhpFlo\Loader\Type
 * @author Marc Aschmann <maschmann@gmail.com>
 */
class Fbp implements LoaderInterface
{

    /**
     * @param string $input
     * @return array|bool
     */
    public function parse($input)
    {
        $parser = new FbpParser($input);

        return $parser->run();
    }
}
