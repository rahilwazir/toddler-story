<?php

namespace RW\Modules;

use RW\Modules\HashTagContent;
use RW\Modules\InnerHashTagContent;

class HashTagLoader
{
    public static $action = null;

    public static function callHashContent($action = '')
    {
        global $hashtags;

        self::$action = $action;
        add_action('wp_ajax_' . self::$action, array(__CLASS__, __METHOD__));

        $hash_tag = sanitize_text_field(filter_input(0, 'hashTag'));

        //If there is dataID
        if ( filter_input(0, 'id') ) {

            $full_data = $_POST;

            foreach ($full_data as $key => $value) {
                $full_data[$key] = sanitize_text_field($value);
            }

            $inner_action = $full_data['hashTag'];
            if (method_exists(__NAMESPACE__ . '\InnerHashTagContent', $inner_action)) {
                InnerHashTagContent::$inner_action($full_data);
            }
            exit;

        } else {

            if (in_array($hash_tag, $hashtags)) {
                HashTagContent::$hash_tag();
            }

        }

        exit;
    }
}