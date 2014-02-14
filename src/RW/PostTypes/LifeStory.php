<?php

namespace RW\PostTypes;

class LifeStory implements PostTypeBase
{

    private $shows_type;
    public static $post_type = 'life-story';
    public static $taxonomy = 'life-stories';

    public function __construct()
    {
        //register the custom post type
        $this->registerPostType();
//        $this->registerTaxonomy();
//        $this->removePostTypeSlug();

        add_filter('manage_edit-' . self::$post_type . '_columns', array($this, 'life_story_unset_column'));

        add_filter('enter_title_here', array($this, 'change_default_title'));
    }

    public function registerPostType()
    {
        // The following is all the names, in our tutorial, we use "Project"
        $labels = array(
            'name' => _x('Life Story', self::$post_type),
            'singular_name' => _x('Life Story', self::$post_type),
            'add_new' => _x('Add Life Story', self::$post_type),
            'add_new_item' => __('Add Life Story'),
            'edit_item' => __('Edit Life Storyren'),
            'new_item' => __('New Life Story'),
            'view_item' => __('View Life Story'),
            'search_items' => __('Search Life Story'),
            'not_found' => __('No Life Story found'),
            'not_found_in_trash' => __('No Life Story found in Trash'),
            'parent_item_colon' => '',
            'menu_name' => __('Life Stories')
        );

        // Some arguments and in the last line 'supports', we say to WordPress what features are supported on the Project post type
        $this->shows_type = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'Life Story'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 8,
            'menu_icon' => ADMIN_URI . '/images/life_story.png',
            'supports' => array('title', 'editor', 'author', 'thumbnail'),
            'taxonomies' => array(self::$taxonomy),
        );

        register_post_type(self::$post_type, $this->shows_type);
    }

    public function registerTaxonomy()
    {
        $labels = array(
            'name' => _x('Life Stories', self::$taxonomy),
            'singular_name' => _x('Life Story', self::$taxonomy),
            'search_items' => __('Search Life Stories'),
            'popular_items' => __('Popular Life Stories'),
            'all_items' => __('All Life Stories'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Life Story'),
            'update_item' => __('Update Life Story'),
            'add_new_item' => __('Add New Life Story'),
            'new_item_name' => __('New Life Story'),
            'menu_name' => __('Life Stories'),
        );

        // Now register the non-hierarchical taxonomy like tag
        register_taxonomy(self::$taxonomy, self::$post_type, array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'update_count_callback' => '_update_post_term_count',
            'taxonomies' => array(self::$taxonomy),
            'query_var' => true,
            'rewrite' => array('slug' => 'Life Storyrens'),
        ));
    }

    public function life_story_unset_column($columns)
    {
        unset($columns['author'], $columns['comments'], $columns['taxonomy-' . self::$taxonomy]);

        return $columns;
    }

    public function change_default_title($title)
    {
        $screen = get_current_screen();

        if (self::$post_type == $screen->post_type) {
            $title = 'Title of life story';
        }

        return $title;
    }

    public function removePostTypeSlug()
    {
        add_filter('post_type_link', array($this, 'vipx_remove_cpt_slug'), 10, 3);
        add_action('pre_get_posts', array($this, 'vipx_parse_request_tricksy'));
    }

    /**
     * Remove the slug from published post permalinks. Only affect our CPT though.
     */
    public function vipx_remove_cpt_slug($post_link, $post, $leavename)
    {

        if (!in_array($post->post_type, array(self::$post_type)) || 'publish' != $post->post_status)
            return $post_link;

        $post_link = str_replace('/' . $post->post_type . '/', '/', $post_link);

        return $post_link;
    }

    /**
     * Some hackery to have WordPress match postname to any of our public post types
     * All of our public post types can have /post-name/ as the slug, so they better be unique across all posts
     * Typically core only accounts for posts and pages where the slug is /post-name/
     */
    public function vipx_parse_request_tricksy($query)
    {

        // Only noop the main query
        if (!$query->is_main_query())
            return;

        // Only noop our very specific rewrite rule match
        if (2 != count($query->query) || !isset($query->query['page']))
            return;

        // 'name' will be set if post permalinks are just post_name, otherwise the page rule will match
        if (!empty($query->query['name']))
            $query->set('post_type', array(self::$post_type));
    }

}
