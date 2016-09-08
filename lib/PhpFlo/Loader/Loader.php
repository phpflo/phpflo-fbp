<?php
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

        if (is_file($file)) {
            $content = file_get_contents($file);
        } else {
            throw new \InvalidArgumentException(
                "Loader::load(): {$file} does not exist!"
            );
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
}
