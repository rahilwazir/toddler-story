<?php

namespace RW\PostTypes;

use RW\Modules\ParentModule;

class Background implements PostTypeBase
{

    private $shows_type;
    public static $post_type = 'child-bg';
    public static $taxonomy = 'childs-bg';

    public function __construct()
    {
        //register the custom post type
        $this->registerPostType();
//        $this->registerTaxonomy();
//        $this->removePostTypeSlug();

        add_filter('manage_edit-' . self::$post_type . '_columns', array($this, 'life_story_unset_column'));

    }

    public function registerPostType()
    {
        // The following is all the names, in our tutorial, we use "Project"
        $labels = array(
            'name' => _x('Background', self::$post_type),
            'singular_name' => _x('Background', self::$post_type),
            'add_new' => _x('Add Background', self::$post_type),
            'add_new_item' => __('Add Background'),
            'edit_item' => __('Edit Backgroundren'),
            'new_item' => __('New Background'),
            'view_item' => __('View Background'),
            'search_items' => __('Search Background'),
            'not_found' => __('No Background found'),
            'not_found_in_trash' => __('No Background found in Trash'),
            'parent_item_colon' => '',
            'menu_name' => __('Background Chooser')
        );

        // Some arguments and in the last line 'supports', we say to WordPress what features are supported on the Project post type
        $this->shows_type = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => (ParentModule::currentUserisParent()) ? false : true,
            'query_var' => true,
            'rewrite' => array('slug' => 'child-backgrounds'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 8,
            'menu_icon' => ADMIN_URI . '/images/bg-chooser.png',
            'supports' => array('title', 'thumbnail'),
            'taxonomies' => array(self::$taxonomy),
        );

        register_post_type(self::$post_type, $this->shows_type);
    }

    public function registerTaxonomy()
    {
        $labels = array(
            'name' => _x('Backgrounds', self::$taxonomy),
            'singular_name' => _x('Background', self::$taxonomy),
            'search_items' => __('Search Backgrounds'),
            'popular_items' => __('Popular Backgrounds'),
            'all_items' => __('All Backgrounds'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Background'),
            'update_item' => __('Update Background'),
            'add_new_item' => __('Add New Background'),
            'new_item_name' => __('New Background'),
            'menu_name' => __('Backgrounds'),
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
            'rewrite' => array('slug' => 'backgrounds'),
        ));
    }

    public function life_story_unset_column($columns)
    {
        unset($columns['author'], $columns['comments'], $columns['taxonomy-' . self::$taxonomy]);

        return $columns;
    }

    public function removePostTypeSlug() {}

}
