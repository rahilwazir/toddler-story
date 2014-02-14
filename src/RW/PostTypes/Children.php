<?php

namespace RW\PostTypes;

use RW\Admin\LifeStoryMenu;
use RW\PostTypes\MetaBoxes\GenderMeta;
use RW\PostTypes\MetaBoxes\RelationMeta;
use RW\PostTypes\MetaBoxes\DateOfBirthMeta;
use RW\PostTypes\MetaBoxes\NameOfChildMeta;
use RW\PostTypes\MetaBoxes\AccessToAll;
use RW\PostTypes\MetaBoxes\BackgroundChooser;
use RW\PostTypes\MetaBoxes\ListParentUsers;

class Children implements PostTypeBase
{

    private $shows_type;
    
    public static $post_type = 'child';
    public static $taxonomy = 'childs';
    public static $slug = 'user/';

    public function __construct()
    {
        // We call this function to register the custom post type
        $this->registerPostType();
//        $this->registerTaxonomy();
//        $this->removePostTypeSlug();
        
        if (current_user_can('administrator') || get_custom_posts() !== 0) {
            /**
             * If parent has 1 or more than 1 child
             */
            new LifeStoryMenu();
        }

        //Add Gender Metabox
        new GenderMeta();

        //Add Relation Metabox
        new RelationMeta();

        //Add Date of Birth Metabox
        new DateOfBirthMeta();

        //Add Name of children box
        new NameOfChildMeta();
        
        //Add background chooser
        new BackgroundChooser();
        
        //Add access right box
        new AccessToAll();
        
        //List all parent users
        new ListParentUsers();

        add_filter('manage_edit-' . self::$post_type . '_columns', array($this, 'children_unset_column'));

        add_filter('enter_title_here', array($this, 'change_default_title'));

        add_filter( 'map_meta_cap', array($this, 'my_map_meta_cap'), 10, 4 );
        
        add_filter( 'post_row_actions', array($this, 'go_to_life_story'), 10, 2 );
        
        add_filter('post_type_link', array($this, 'wpse73228_author_tag'), 999, 4);
    }

    public function registerPostType()
    {
        // The following is all the names, in our tutorial, we use "Project"
        $labels = array(
            'name' => _x('Child', self::$post_type),
            'singular_name' => _x('Child', self::$post_type),
            'add_new' => _x('Add Child', self::$post_type),
            'add_new_item' => __('Add Child'),
            'edit_item' => __('Edit Children'),
            'new_item' => __('New Child'),
            'view_item' => __('View Child'),
            'search_items' => __('Search Child'),
            'not_found' => __('No Child found'),
            'not_found_in_trash' => __('No Child found in Trash'),
            'parent_item_colon' => '',
            'menu_name' => __('Childs')
        );

        // Some arguments and in the last line 'supports', we say to WordPress what features are supported on the Project post type
        $this->shows_type = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => self::$slug . '%author%'),
            'capability_type' => 'post',
            'map_meta_cap' => true,
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 8,
            'menu_icon' => ADMIN_URI . '/images/children.png',
            'supports' => array('title', 'editor', 'author', 'thumbnail'),
            'taxonomies' => array(self::$taxonomy),
        );

        register_post_type(self::$post_type, $this->shows_type);
    }

    public function registerTaxonomy()
    {
        $labels = array(
            'name' => _x('Childs', self::$taxonomy),
            'singular_name' => _x('Category', self::$taxonomy),
            'search_items' => __('Search Categories'),
            'popular_items' => __('Popular Categories'),
            'all_items' => __('All Categories'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Category'),
            'update_item' => __('Update Category'),
            'add_new_item' => __('Add New Category'),
            'new_item_name' => __('New Category Name'),
            'menu_name' => __('Categories'),
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
            'rewrite' => array('slug' => 'childrens'),
        ));
    }

    public function children_unset_column($columns)
    {
        unset($columns['author'], $columns['comments'], $columns['taxonomy-' . self::$taxonomy]);

        return $columns;
    }

    public function change_default_title($title)
    {
        $screen = get_current_screen();

        if (self::$post_type === $screen->post_type) {
            $title = 'Title of life story';
        }

        return $title;
    }

    public function removePostTypeSlug()
    {
        #add_filter('post_type_link', array($this, 'vipx_remove_cpt_slug'), 10, 3);
        #add_action('pre_get_posts', array($this, 'vipx_parse_request_tricksy'));
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
        if (2 != count($query->query) || !isset($query->query['page'])
        )
            return;

        // 'name' will be set if post permalinks are just post_name, otherwise the page rule will match
        if (!empty($query->query['name']))
            $query->set('post_type', array(self::$post_type));
    }

    public function my_map_meta_cap($caps, $cap, $user_id, $args)
    {

        /* If editing, deleting, or reading a movie, get the post and post type object. */
        if ('edit_' . self::$post_type == $cap || 'delete_' . self::$post_type == $cap || 'read_' . self::$post_type == $cap) {
            $post = get_post($args[0]);
            $post_type = get_post_type_object($post->post_type);

            /* Set an empty array for the caps. */
            $caps = array();
        }

        /* If editing a movie, assign the required capability. */
        if ('edit_' . self::$post_type == $cap) {
            if ($user_id == $post->post_author)
                $caps[] = $post_type->cap->edit_posts;
            else
                $caps[] = $post_type->cap->edit_others_posts;
        }

        /* If deleting a movie, assign the required capability. */
        elseif ('delete_' . self::$post_type == $cap) {
            if ($user_id == $post->post_author)
                $caps[] = $post_type->cap->delete_posts;
            else
                $caps[] = $post_type->cap->delete_others_posts;
        }

        /* If reading a private movie, assign the required capability. */
        elseif ('read_' . self::$post_type == $cap) {

            if ('private' != $post->post_status)
                $caps[] = 'read';
            elseif ($user_id == $post->post_author)
                $caps[] = 'read';
            else
                $caps[] = $post_type->cap->read_private_posts;
        }

        /* Return the capabilities required by the user. */
        return $caps;
    }
    
    /*
     * Add the row actions to post type
     */
    public function go_to_life_story( $actions, $post )
    {
        if ($post->post_type === self::$post_type) {
            $actions['go_to'] = '<a href="admin.php?page=lifestory-conf&cid=' . $post->ID . '" title="Go to life story">Go to life story</a>';
        }
        
        return $actions;
    }
    
    /**
     * Translate %author_name% to Author Name
     */
    public function wpse73228_author_tag($post_link, $post, $leavename, $sample)
    {

        if( self::$post_type != get_post_type($post) )
            return $post_link;

        $authordata = get_userdata($post->post_author);
        $author = $authordata->user_nicename;

        $post_link = str_replace('%author%', $author, $post_link);

        return $post_link;
    }
}
