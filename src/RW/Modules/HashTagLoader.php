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
        $hash_tag = filter_input(0, 'hashTag');

        //If there is any data
        if (filter_input(0, 'dataSet')) {
            $full_data = json_decode(filter_input(0, 'dataSet'), TRUE);

            foreach ($full_data as $key => $value) {
                if ($key === 'action') $full_data['action'] = convert_to_cc($value);
            }

            $inner_action = $full_data['action'];

            if (method_exists(__NAMESPACE__ . '\InnerHashTagContent', $inner_action)) {
                InnerHashTagContent::$inner_action($full_data);
            }
            exit;
        }

        if (in_array($hash_tag, $hashtags)) {
            HashTagContent::$hash_tag();
        }

        exit;
    }
}