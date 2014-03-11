<?php

namespace RW\Modules;

use RW\PostTypes\MetaBoxes\BackgroundChooser;
use RW\PostTypes\MetaBoxes\RelationMeta;
use RW\PostTypes\Children;
use RW\PostTypes\MetaBoxes\GenderMeta;
use RW\TemplatesPackage\Template;

class HashTagContent
{
    /**
     * Add/Update data holder
     * @var array
     */
    private static $array_builder = array();

    public static function __callStatic($method, $args)
    {
        global $pagesConstants;

        if (method_exists(get_called_class(), $method)) {

            if (activePremiumSubscriber()) {
                return call_user_func_array(get_called_class() . '::' . $method, $args);
            } else if (!activePremiumSubscriber() && $pagesConstants[5] === $method) {
                return call_user_func_array(get_called_class() . '::' . $method, $args);
            } else {
                Template::load('upgrade');
            }

        }
    }
    
    /**
     * Create/Update childs
     * @param bool $update Decide whether to update or create new child
     * @param int $post_id Post id to process update
     */
    protected static function create($update = false, $post_id = 0)
    {
        if ($post_id > 0) {
            global $post; 
            $post = get_post( $post_id, OBJECT );
            setup_postdata( $post );
        }

        $update = ($update === true && $post_id > 0) ? true : false;
        
        /**
         * Building array for both child creation and updation
         */
        self::$array_builder['form_name'] = ($update) ? 'update_child' : 'add_child';
        self::$array_builder['nonce'] = ($update) ? 'update_child' : 'add_child';
        self::$array_builder['title'] = ($update) ? Child::title() : '';
        self::$array_builder['first_name'] = array (
            'value' => ($update) ? Child::firstName() : ''
        );
        self::$array_builder['last_name'] = array (
            'value' => ($update) ? Child::lastName() : ''
        );
        self::$array_builder['dob'] = array (
            'value' => ($update) ? Child::birthDate() : ''
        );
        self::$array_builder['sex'] = array (
            'value' => ($update) ? Child::sex() : ''
        );
        self::$array_builder['relation'] = array (
            'value' => ($update) ? Child::relation() : ''
        );
        self::$array_builder['thumb_id'] = ($update) ? Child::bg(true) : 'none';
        self::$array_builder['d_url'] = ($update) ? Child::dURL() : '';
        self::$array_builder['thumbnail'] = Child::thumb('chmng_dp');
        self::$array_builder['protected_access'] = ($update) ? Child::publicAccess() : '';

        $data['update'] = $update;
        $data['post'] = $post;
        $data['post_id'] = $post_id;
        $data['array_builder'] = self::$array_builder;
        $data['backgroundChooser'] = BackgroundChooser::has_post('dp');
        $data['genderMeta'] = GenderMeta::$field_values;
        $data['relationMeta'] = RelationMeta::$field_values;

        createHiddenTitle( (!$update ? 'Add new child' : 'Edit child') );

        Template::load('createUpdateChild', $data);

        wp_reset_postdata();
    }
    
    protected static function lifeStory()
    {

    }
    
    protected static function relation()
    {

    }
    
    protected static function babyBook()
    {

    }
    
    protected static function childManagement()
    {
        global $hashtags;

        createHiddenTitle( 'My Children Management' );

        $data['postType'] = Children::$post_type;
        $data['hashtags'] = $hashtags;

        Template::load('childManagement', $data);

    }

    protected static function accountSettings()
    {
        createHiddenTitle( 'User Account Setting' );

        Template::load('accountSettings');
    }

    protected static function sharing()
    {
        //
    }
}