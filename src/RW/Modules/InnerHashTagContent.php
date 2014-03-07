<?php

namespace RW\Modules;

use RW\Modules\HashTagContent;
use RW\Modules\Child;
use RW\PostTypes\ChildBlog;
use RW\PostTypes\LifeStoryMenu;
use RW\PostTypes\Children;
use RW\TemplatesPackage\Template;
use RW\Modules\Comments;

class InnerHashTagContent extends HashTagContent
{
    public static function goToStory(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['hashTag']) {
            if ( Child::existAt($data['id']) ) {
                $defaultHomePage = 'gallery';

                if ( $data['deeplink'] ) $defaultHomePage = $data['deeplink'];

                global $hashtags;

                $menus = Children::$lifeStoryMenu;

                Child::getCurrent( $data['id'] );

                setSession( '_goto_id', $data['id'] );

                $data['tabMenus'] = $menus;
                $data['childBlogPosts'] = Child::blogPosts( $data['id'] );

                $data['hashtags'] = $hashtags;

                createHiddenTitle( Child::fullName() . ' ' . $menus->tabs_menu[$defaultHomePage] );

                Template::load(array(
                    $data['hashTag'] . '.gts_header',
                    $data['hashTag'] . '.' . $defaultHomePage,
                    $data['hashTag'] . '.gts_footer'
                ), $data);

            }
        }
    }

    public static function editInfo(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['hashTag']) {
            if ( Child::existAt($data['id']) ) {
                self::create(true, $data['id']);
            }
        }
    }

    public static function deleteChild(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['hashTag']) {
            if ( Child::existAt($data['id']) ) {
                if (wp_trash_post($data['id'])) {
                    echo json_encode(array('status' => 'Child deleted successfully.'));
                }
            }
        }
    }

    public static function deleteChildBlogPost(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['hashTag']) {
            if ( wp_verify_nonce($data['blogToken'], $data['id']) ) {
                if ( Child::existAt($data['id'], null, ChildBlog::$post_type) ) {
                    if (wp_trash_post($data['id'])) {
                        echo json_encode(array('status' => 'Blog post deleted successfully.'));
                    }
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
            if ( Comments::exists( $data['parentPostID'], $data['id'] ) ) {
                $comment_trashed = Comments::delete( $data['id'] );

                if ( $comment_trashed ) {
                    echo json_encode(array(
                        'deleted' => true,
                        'commentTotal' => Comments::$lastDeletedCommentID
                    ));
                }
            }
        }
    }
}
