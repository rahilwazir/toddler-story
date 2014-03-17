<?php

namespace RW\Modules;

use RW\PostTypes\ChildBlog;
use RW\PostTypes\ChildJournal;
use RW\PostTypes\LifeStoryMenu;
use RW\PostTypes\Children;
use RW\PostTypes\MetaBoxes\ListJournalTypes;
use RW\TemplatesPackage\Template;

class InnerHashTagContent extends HashTagContent
{
    protected static function goToStory(array $data)
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

                // Journal Posts
                $data['journalTypes'] = ListJournalTypes::$journalTypes;
                $data['journalPosts'] = get_custom_posts(array(
                    'post_type' => ChildJournal::$post_type,
                    'posts_per_page' => -1
                ));

                createHiddenTitle( Child::fullName() . ' ' . $menus->tabs_menu[$defaultHomePage] );

                Template::load(array(
                    $data['hashTag'] . '.gts_header',
                    $data['hashTag'] . '.' . $defaultHomePage,
                    $data['hashTag'] . '.gts_footer'
                ), $data);

            }
        }
    }

    protected static function editInfo(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['hashTag']) {
            if ( Child::existAt($data['id']) ) {
                self::create(true, $data['id']);
            }
        }
    }

    protected static function deleteChild(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['hashTag']) {
            if ( Child::existAt($data['id']) ) {
                if (wp_trash_post($data['id'])) {
                    echo json_encode(array('status' => 'Child deleted successfully.'));
                }
            }
        }
    }

    protected static function deleteChildBlogPost(array $data)
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

    protected static function addComment(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['hashTag']) {

            Comments::add($data);
            $_comment_id = Comments::$lastInsertedCommentID;

            if ( $_comment_id > 0 ) {
                echo Comments::retrieve($_comment_id);
            }
        }
    }

    protected static function deleteComment(array $data)
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

    protected static function deleteJournalPost(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['hashTag']) {
            if (wp_verify_nonce($data['journalToken'], $data['id'])) {
                if (Child::existAt($data['id'], user_info('ID'), ChildJournal::$post_type)) {
                    if (wp_trash_post($data['id'])) {
                        echo json_encode(array('status' => 'Blog post deleted successfully.'));
                    }
                }
            }
        }
    }
}
