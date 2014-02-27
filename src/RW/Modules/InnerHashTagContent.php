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

    public static function goToStory(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['hashTag']) {
            if ( Child::exists($data['id']) ) {

                Child::getCurrent( $data['id'] );

                setSession( '_goto_story_post_id', $data['id'] );

                $data['tabMenus'] = Children::$lifeStoryMenu; $i = 0;
                $data['childBlogPosts'] = Child::blogPosts( $data['id'] );

                createHiddenTitle( Child::fullName(), 'Gallery' );

                Template::load($data['hashTag'], $data);
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
