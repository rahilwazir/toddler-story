<?php

namespace RW\TemplatesPackage;

final class Template
{
    /**
     * Load file template
     * @param array $names
     * @param mixed $data
     * return @void
     */
    public static function load($names, $data = array())
    {
        try {
            self::locate($names, $data);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Template locater
     * @param array $names
     * @param mixed $data
     * return @void
     */
    private static function locate($names, $data = array())
    {
        if ($data && (is_array($data) || is_object($data) )) extract($data);

        if ( is_array($names) ) {
            foreach ($names as $name) {
                $template = RW_DIR . '/Templates/' . str_replace('.', '/', $name) . '.php';

                if ( self::exists($template) ) require_once $template;
            }
        } else {
            $template = RW_DIR . '/Templates/' . str_replace('.', '/', $names) . '.php';

            if ( self::exists($template) ) require_once $template;
        }
    }

    /**
     * Check if template file exists before displaying
     * @param string $template
     * @void exception
     * @return bool
     */
    public static function exists($template)
    {
        try {
            if ( !file_exists($template) ) {
                throw new \Exception('Template not found');
            }

            return true;

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}