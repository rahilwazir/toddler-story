<?php
namespace RW\Modules;

/**
 * Toddler Comments Module
 *
 * @package
 *
 * @author rahilwazir
 * @copyright 2014
 * @version 1.0
 * @access public
 */
class Comments
{
    /**
     * @var int
     */
    public static $postID = 0;

    /**
     * @var int
     */
    public static $commentID = 0;

    /**
     * @var int
     */
    public static $lastInsertedCommentID = 0;

    /**
     * @var int
     */
    public static $lastDeletedCommentID = 0;

    public static function add(array $data)
    {
        $content = esc_textarea( trim($data['commentContent']) );

        if ($content !== '') {
            $_comment_id = \wp_insert_comment(array(
                'comment_post_ID' => $data['id'],
                'user_id' => user_info('ID'),
                'comment_author' => (user_info('ID')) ? user_info('display_name') : '',
                'comment_content' => $content
            ));

            if ( $_comment_id > 0 ) {
                self::$lastInsertedCommentID = $_comment_id;
            }
        }
    }

    public static function delete($id)
    {
        $comment = get_comment($id);
        if ( $comment !== null ) {
            if ( wp_delete_comment($id) ) {

                self::$lastDeletedCommentID = countComment( absint( getSession( '_goto_story_post_id' ) ) );

                return true;
            }

            return false;
        }
    }

    public static function retrieve($id)
    {
        if ( $id > 0 ) {
            self::$postID = $id;
            $comment = get_comment( $id );
            $render = '';

            if ($comment !== null) {
                $render = self::renderHTML($comment);
            } else {
                $comments = get_comments( $id );
                if ( !empty($comments) ) {
                    foreach ($comments as $comment) {
                        $render .= self::renderHTML($comment);
                    }
                }
            }

            return $render;
        }

    }

    public static function renderHTML($comment)
    {
        global $hashtags;

        $output = '';

        $output .= '<article class="single-comment block removal-input specific-loader comment-input" data-comment-id="' . $comment->comment_ID .'">';
        $output .= '<span class="comment-meta">Commented by: ' . $comment->comment_author . ', ' . $comment->comment_date . '</span>';
        $output .= '<div class="comment-content">' . $comment->comment_content . '</div>';
        $output .= '<a href="' . $hashtags[8] . '-' . $comment->comment_ID.'" class="block remove-comment remove-icon disable">Remove Comment</a>';
        $output .= '</article>';

        return $output;
    }
}