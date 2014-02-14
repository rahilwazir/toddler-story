<?php

namespace RW\Modules;

use RW\Modules\HashTagContent;

class InnerHashTagContent extends HashTagContent
{

    public static function goToStory(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['action']) {
            echo 'Story';
        }
    }

    public static function editInfo(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['action']) {
            self::create(true, $data['id']);
        }
    }

    public static function deleteChild(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['action']) {
            if (wp_trash_post($data['id'])) {
                echo json_encode(array('status' => 'Child deleted successfully.'));
            }
        }
    }
}
