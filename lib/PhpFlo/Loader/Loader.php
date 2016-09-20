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

use PhpFlo\Exception\LoaderException;
use PhpFlo\Fbp\FbpParser;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Loader
 *
 * @package PhpFlo\Loader
 * @author Marc Aschmann <maschmann@gmail.com>
 */
final class Loader
{
    private static $types = [
        'yml' => 'yaml',
        'yaml' => 'yaml',
        'json' => 'json',
        'fbp' => 'fbp',
    ];

    /**
     * @param string $file name/path of file to load
     * @return array
     * @throws LoaderException
     */
    public static function load($file)
    {
        $type = self::$types[self::checkType($file)];
        $content = self::loadFile($file);

        if (empty($content)) {
            throw new LoaderException("Loader::load(): no data found in file!");
        }

        switch ($type) {
            case 'fbp':
                $loader = new FbpParser($content);
                $content = $loader->run()->toArray();
                break;
            case 'yaml':
                $content = Yaml::parse($content);
                break;
            case 'json':
                $content = json_decode($content, true);
                break;
        }

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

        if (!in_array($type, array_keys(self::$types))) {
            throw new LoaderException("Loader::checkType(): Could not find parser for {$file}!");
        }

        return $type;
    }

    /**
     * @param string $file
     * @return string
     */
    private static function loadFile($file)
    {
        if (file_exists($file) && is_readable($file)) {
            $content = file_get_contents($file);
        } else {
            throw new LoaderException(
                "Loader::loadFile(): {$file} does not exist!"
            );
        }

        return $content;
    }
}
