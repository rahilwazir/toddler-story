<?php

namespace RW\TemplatesPackage;

class Template
{
    /**
     * Load file template
     * @param string $name
     * @param mixed $data
     * return @void
     */
    final public static function load($name, $data = array())
    {
        try {

            self::locate($name, $data);

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Template locater
     * @param string $name
     * @param mixed $data
     * return @void
     */
    private static function locate($name, $data)
    {
        if ($data && (is_array($data) || is_object($data) )) extract($data);

        require_once (RW_DIR . '/Templates/' . $name . '.php');
    }
}