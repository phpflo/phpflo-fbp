<?php
/*
 * This file is part of the phpflo\phpflo-fbp package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PhpFlo\Loader;

class Loader
{
    private static $loaders = [
        'yml' => '\PhpFlo\Loader\Type\Yaml',
        'yaml' => '\PhpFlo\Loader\Type\Yaml',
        'json' => '\PhpFlo\Loader\Type\Json',
        'fbp' => '\PhpFlo\Loader\Type\Fbp',
    ];

    final public static function load($file, $filecheck = true)
    {
        $type = self::checkType($file);
        $class = self::$loaders[$type];
        $content = '';

        /*
         * if filecheck is disabled, we assume you're just providing
         * the data as string
         */
        if ($filecheck) {
            $content = self::loadFile($file);
        }

        $loader = new $class();


        return $content;
    }

    /**
     * Check file if extension matches a loader.
     *
     * @param string $file
     * @return string
     */
    private static function checkType($file)
    {
        $parts = explode('.', $file);
        $type = array_pop($parts);

        if (!in_array($type, array_keys(self::$loaders))) {
            throw new \InvalidArgumentException("Loader::checkType(): Could not find loader for {$file}!");
        }

        return $type;
    }

    /**
     * @param string $file
     * @return string
     */
    private static function loadFile($file)
    {
        if (is_file($file)) {
            $content = file_get_contents($file);
        } else {
            throw new \InvalidArgumentException(
                "Loader::load(): {$file} does not exist!"
            );
        }

        return $content;
    }
}
