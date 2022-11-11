<?php

namespace SangoBlocks;

class App
{
    private static $singleton;
    private $instances = [];
    private $dirName = '';
    private $url = '';

    public static function register($name, $class)
    {
        $singleton = self::singleton();
        $singleton->instances[$name] = new $class();
    }

    public static function singleton()
    {
        if (!isset(self::$singleton)) {
            self::$singleton = new App();
        }
        return self::$singleton;
    }

    public static function get($name)
    {
        $singleton = self::singleton();
        return $singleton->instances[$name];
    }

    public static function requireDir($dirName)
    {
        if (!is_dir($dirName)) {
          return;
        }
        foreach (scandir($dirName) as $filename) {
            $path = $dirName . '/' . $filename;
            $pathInfo = pathinfo($path);
            if (is_file($path) && $pathInfo['extension'] === 'php') {
                include_once $path;
            }
        }
    }

    public static function setRootPluginDir($dirName)
    {
        $singleton = self::singleton();
        $singleton->dirName = $dirName;
    }

    public static function setRootPluginUrl($url)
    {
        $singleton = self::singleton();
        $singleton->url = $url;
    }

    public static function rootPluginDir()
    {
        $singleton = self::singleton();
        return $singleton->dirName;
    }

    public static function rootPluginUrl()
    {
        $singleton = self::singleton();
        return $singleton->url;
    }

    public static function run()
    {
        self::hook('init');
    }

    public static function hook($hookName)
    {
        $singleton = self::singleton();
        $instances = $singleton->instances;
        foreach ($instances as $instance) {
          $instance->$hookName();
        }
    }
}
