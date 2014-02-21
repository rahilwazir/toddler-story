<?php

namespace RW\PostTypes;

use RW\Admin\LifeStoryMenu;
use RW\Modules\ParentModule;

class ChildBlog
{

    private $shows_type;

    /**
     * Name for post type
     * @var string
     */
    public static $post_type = 'child_blog';

    /**
     * Label for post type
     * @var string 
     */
    public static $label = 'Child Blog';

    /**
     * Taxonomy name strign
     * @var string
     */
    public static $taxonomy = 'childs_blog';

    
    public function __construct()
    {
        $this->registerPostType();
    }

    public function registerPostType()
    {
        // The following is all the names, in our tutorial, we use "Project"
        $labels = array(
            'name' => _x($this->label, self::$post_type),
            'singular_name' => _x($this->label, self::$post_type),
            'add_new' => _x('Add Blog', self::$post_type),
            'add_new_item' => __('Add Blog'),
            'edit_item' => __('Edit Blog'),
            'new_item' => __('New Blog'),
            'view_item' => __('View Blog'),
            'search_items' => __('Search Blog'),
            'not_found' => __('No Child found'),
            'not_found_in_trash' => __('No Blog found in Trash'),
            'parent_item_colon' => '',
            'menu_name' => __(self::$label)
        );

        // Some arguments and in the last line 'supports', we say to WordPress what features are supported on the Project post type
        $this->shows_type = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'child-blog'),
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

    public function removePostTypeSlug()
    {
        //;
    }
}