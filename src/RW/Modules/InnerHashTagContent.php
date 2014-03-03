<?php

namespace RW\Modules;

use RW\Modules\HashTagContent;
use RW\Modules\Child;
use RW\PostTypes\LifeStoryMenu;
use RW\PostTypes\Children;
use RW\TemplatesPackage\Template;
use RW\Modules\Comments;

class InnerHashTagContent extends HashTagContent
{

    private static function _load($template = '', $data = array())
    {
        global $hashtags;

        $menus = Children::$lifeStoryMenu;

        Child::getCurrent( $data['id'] );

        setSession( '_goto_story_post_id', $data['id'] );

        $data['tabMenus'] = $menus;
        $data['childBlogPosts'] = Child::blogPosts( $data['id'] );

        createHiddenTitle( Child::fullName(), 'Gallery' );

        $data['hashtags'] = $hashtags;

        Template::load($data['hashTag'] . '.gts_header', $data);

        Template::load($template);

        Template::load($data['hashTag'] . '.gts_footer');
    }

    public static function goToStory(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['hashTag']) {
            if ( Child::exists($data['id']) ) {
                $defaultHomePage = 'gallery';

                self::_load($data['hashTag'] . '.' . $defaultHomePage, $data);
            }
        }
    }

    public static function editInfo(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['hashTag']) {
            if ( Child::exists($data['id']) ) {
                self::create(true, $data['id']);
            }
        }
    }

    public static function deleteChild(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['hashTag']) {
            if ( Child::exists($data['id']) ) {
                if (wp_trash_post($data['id'])) {
                    echo json_encode(array('status' => 'Child deleted successfully.'));
                }
            }
        }
    }

    public static function addComment(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['hashTag']) {

            Comments::add($data);
            $_comment_id = Comments::$lastInsertedCommentID;

            if ( $_comment_id > 0 ) {
                echo Comments::retrieve($_comment_id);
            }
        }
    }

    public static function deleteComment(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['hashTag']) {
            $total_comment = Comments::delete( $data['id'] );

            if ( $total_comment ) {
                echo json_encode(array(
                    'deleted' => true,
                    'commentTotal' => Comments::$lastDeletedCommentID
                ));
            }
        }
    }
}
