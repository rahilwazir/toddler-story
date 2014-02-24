<?php

namespace RW\Modules;

use RW\Modules\HashTagContent;
use RW\Modules\Child;
use RW\PostTypes\LifeStoryMenu;
use RW\PostTypes\Children;
use RW\TemplatesPackage\Template;

class InnerHashTagContent extends HashTagContent
{

    public static function goToStory(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['action']) {
            if ( Child::exists($data['id']) ) {

                Child::getCurrent( $data['id'] );

                $data['tabMenus'] = Children::$lifeStoryMenu; $i = 0;
                $data['childBlogPosts'] = Child::blogPosts( $data['id'] );

                createHiddenTitle( Child::fullName(), 'Gallery' );

                Template::load($data['action'], $data);
            }
        }
    }

    public static function editInfo(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['action']) {
            if ( Child::exists($data['id']) ) {
                self::create(true, $data['id']);
            }
        }
    }

    public static function deleteChild(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['action']) {
            if ( Child::exists($data['id']) ) {
                if (wp_trash_post($data['id'])) {
                    echo json_encode(array('status' => 'Child deleted successfully.'));
                }
            }
        }
    }

    public static function addComment(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['action']) {
            echo 'Comment';
        }
    }
}
