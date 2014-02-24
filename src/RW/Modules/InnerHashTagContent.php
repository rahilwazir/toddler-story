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
            $_comment_id = wp_insert_comment(array(
                'comment_post_ID' => $data['id'],
                'user_id' => user_info('ID'),
                'comment_author' => (user_info('ID')) ? user_info('display_name') : '',
                'comment_content' => esc_textarea($data['commentContent'])
            ));

            if ( $_comment_id > 0 ) {
                $comment = get_comment( $_comment_id );
                $output = '';
                $output .= '<article class="single-comment comment-input" data-comment-id="' . $comment->comment_ID .'">';
                $output .= '<span class="comment-meta">Commented by: ' . $comment->comment_author . ', ' . $comment->comment_date . '</span>';
                $output .= '<div class="comment-content">' . $comment->comment_content . '</div>';
                $output .= '</article>';
                echo $output;
            }
        }
    }
}
