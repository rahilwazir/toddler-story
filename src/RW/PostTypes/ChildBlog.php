<?php

namespace RW\PostTypes;

use RW\Admin\LifeStoryMenu;
use RW\Modules\ParentModule;
use RW\PostTypes\MetaBoxes\ListParentChilds;
use RW\ErrorHandling\Error;
use RW\Modules\Child;

class ChildBlog
{
    private static $_action = null;
    private static $_event = null;

    const USER_ERROR_CODE = 57;

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

        new ListParentChilds();
    }

    public function registerPostType()
    {
        // The following is all the names, in our tutorial, we use "Project"
        $labels = array(
            'name' => _x(self::$label, self::$post_type),
            'singular_name' => _x(self::$label, self::$post_type),
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

    public static function update($action)
    {
        self::$_action = $action;
        add_action('wp_ajax_' . self::$_action, array(__CLASS__, __METHOD__));
        $full_data = filter_input(0, 'full_data');

        $rd = process_serialize_data($full_data);

        if (($rd['blog_id'] > 0) &&
            wp_verify_nonce($rd['blog_token'], $rd['blog_id']) &&
            wp_verify_nonce($rd['blog_update_token'], self::$_action . '_update')) {

            self::$_event = 'update';

        } else if (wp_verify_nonce($rd['rw_nonce'], self::$_action) &&
            wp_verify_nonce($rd['child_token'], $rd['child_id']) ) {

            self::$_event = 'add';

        }

        if (filter_input(0, 'action') === self::$_action &&
            (self::$_event === 'add' || self::$_event === 'update') ) {

            $final_result = array();

            /**
             * Remove error code if already contain
             */
            Error::remove_error(self::USER_ERROR_CODE);

            Error::set_error(self::USER_ERROR_CODE, 'child_blog_title', ($rd['child_blog_title'] === '') ? __('Required.') : '');
            Error::set_error(self::USER_ERROR_CODE, 'child_blog_description', ($rd['child_blog_description'] === '') ? __('Required.') : '');

            $final_result['errors'] = Error::get_error(self::USER_ERROR_CODE);

            if ( count($final_result['errors']) < 1 ) {

                if (self::$_event === 'add') {
                    $my_post = array (
                        'post_title' => $rd['child_blog_title'],
                        'post_content' => $rd['child_blog_description'],
                        'post_author' => user_info('ID'),
                        'post_type' => self::$post_type,
                        'post_status' => 'publish'
                    );

                    $_post_id = wp_insert_post($my_post);

                } else if (self::$_event === 'update') {

                    if (!Child::existAt($rd['post_id'], user_info('ID'), self::$post_type)) {
                        exit('The post is not yours');
                    }

                    $my_post = array(
                        'ID' => $rd['blog_id'],
                        'post_title' => $rd['child_blog_title'],
                        'post_content' => $rd['child_blog_description'],
                        'post_author' => user_info('ID'),
                        'post_type' => self::$post_type,
                        'post_status' => 'publish'
                    );

                    $_post_id = wp_update_post($my_post);
                }

                if (!is_wp_error($_post_id) && $_post_id !== 0) {
                    update_post_meta($_post_id, '_toddler_parent_child_user', $rd['child_id']);

                    $final_result['ok'] = 'Blog added successfully';
                }
            }

            echo json_encode($final_result);
        }

        exit;
    }

    /**
     * @param array $data
     * return int
     */
    public static function delete($data = array())
    {

    }
}