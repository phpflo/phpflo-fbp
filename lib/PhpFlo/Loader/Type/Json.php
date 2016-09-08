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

/**
 * Class Json
 *
 * @package PhpFlo\Loader\Type
 * @author Marc Aschmann <maschmann@gmail.com>
 */
class Json implements LoaderInterface
{

    /**
     * @todo add definition schema validation.
     *
     * @param string $input
     * @return array|bool
     */
    public function parse($input)
    {
        return json_decode($input, true);
    }
}
