<?php

namespace RW\TemplatesPackage;

final class Template
{
    /**
     * Load file template
     * @param string $name
     * @param mixed $data
     * return @void
     */
    public static function load($name, $data = array())
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
    private static function locate($name, $data = array())
    {
        if ($data && (is_array($data) || is_object($data) )) extract($data);

        require_once (RW_DIR . '/Templates/' . str_replace('.', '/', $name) . '.php');
    }
}