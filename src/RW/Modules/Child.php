<?php
namespace RW\Modules;

use RW\PostTypes\Children;
use RW\PostTypes\ChildBlog;

/**
 * Toddler Child Module
 *
 * @package
 *
 * @author rahilwazir
 * @copyright 2014
 * @version 1.0
 * @access public
 */
class Child extends ParentModule
{
    public static $post_id = 0;
    
    /**
     * @var int
     */
    public static $global_post_id = 0;

    public static function customPost()
    {
        return (self::$post_id > 0) ? self::$post_id : false;
    }

    /**
     * Retrieve Child ID
     * @return int
     */
    public static function id()
    {
        if (self::getCurrentParentId() !== 0)
        {
            return user_info('ID');
        }
    }
    
    public static function page()
    {
        return (is_singular(Children::$post_type));
    }
    
    public static function title()
    {
        global $post;
        return get_the_title($post);
    }
    
    public static function dURL()
    {
        global $post;
        return $post->post_name;
    }


    public static function thumb($size = 'dp')
    {
        global $post;
        
        return (!has_post_thumbnail()) ?
            '<img src="'.get_template_directory_uri() . '/images/avatar.png" alt="'.$post->post_title.'">' :
            get_the_post_thumbnail($post->ID, $size);
    }
    
    public static function firstName()
    {
        global $post;
        return (get_post_meta($post->ID, '_child_first_name', true))
                ? get_post_meta($post->ID, '_child_first_name', true)
                : '';
    }
    
    public static function lastName()
    {
        global $post;
        return (get_post_meta($post->ID, '_child_last_name', true))
                ? get_post_meta($post->ID, '_child_last_name', true)
                : '';
    }
    
    public static function fullName($split_by = ' ')
    {
        return self::firstName() . $split_by . self::lastName();
    }

    public static function description()
    {
        global $post;

        $content = get_post_field('post_content', $post->ID);
        
        return ($content !== "")
            ? $content
            : 'No description';
    }
    
    public static function birthDate()
    {
        global $post;
        return (get_post_meta($post->ID, '_dob_child', true))
                ? get_post_meta($post->ID, '_dob_child', true)
                : '';
    }
    
    public static function age()
    {
        return (self::birthDate() !== '') ?
                process_date(self::birthDate()) :
                '';
    }
    
    public static function sex()
    {
        global $post;
        return (get_post_meta($post->ID, '_toddler_sex', true))
                ? get_post_meta($post->ID, '_toddler_sex', true)
                : '';
    }
    
    public static function relation()
    {
        global $post;
        return (get_post_meta($post->ID, '_toddler_relation', true))
                ? get_post_meta($post->ID, '_toddler_relation', true)
                : '';
    }
    
    public static function bg($id = false)
    {
        global $post;
        $bg_id = get_post_meta($post->ID, '_toddler_bg', true);
        
        return ($bg_id) ? ( ($id === true) ? $bg_id : get_full_thumbnail_uri($bg_id)) : 0;
    }
    
    public static function publicAccess()
    {
        global $post;
        return (get_post_meta($post->ID, '_toddler_ata', true))
                ? get_post_meta($post->ID, '_toddler_ata', true)
                : '';
    }
    
    /**
     * Check if child post is exists with the id given optionally with the post author
     * @param int $id
     * @param int $user_id
     * @return bool
     */
    public static function exists($id, $user_id = 0)
    {
        $args = array(
            'post_type' => Children::$post_type,
            'p' => intval($id),
            'posts_per_page' => 1,
        );

        if ($user_id > 0) {
            $args['post_author'] = user_info('ID');
        }

        $query = get_custom_posts($args);

        return ($query !== 0) ? true : false;
    }

    public static function blogPosts()
    {
        $blog_posts = \get_custom_posts(array(
            'post_type' => ChildBlog::$post_type,
            'author__in' => array(1),
        ));
        
        return $blog_posts;
    }
    
    public static function getCurrent($id)
    {
        global $post;
        self::$global_post_id = $post->ID;
        $post = get_post( $id, OBJECT );
        setup_postdata( $post );
    }

    public static function setCurrent()
    {
        global $post;
        $post = self::$global_post_id;
    }
}