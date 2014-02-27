<?php

namespace RW\Modules;

use RW\PostTypes\MetaBoxes\BackgroundChooser;
use RW\PostTypes\MetaBoxes\RelationMeta;
use RW\PostTypes\Children;
use RW\PostTypes\MetaBoxes\GenderMeta;
use RW\Modules\Child;
use RW\TemplatesPackage\Template;

class HashTagContent
{
    /**
     * Add/Update data holder
     * @var array
     */
    public static $array_builder = array();
    
    /**
     * Create/Update childs
     * @param bool $update Decide whether to update or create new child
     * @param int $post_id Post id to process update
     */
    public static function create($update = false, $post_id = 0)
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
        self::$array_builder['public_access'] = ($update) ? Child::publicAccess() : '';

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
    
    public static function lifestory()
    {

    }
    
    public static function relation()
    {

    }
    
    public static function babybook()
    {

    }
    
    public static function childmanagement()
    {
        global $hashtags;

        createHiddenTitle( 'My Children Management' );

        $data['postType'] = Children::$post_type;
        $data['hashtags'] = $hashtags;

        Template::load('childManagement', $data);

    }

    public static function userinfo()
    {
        createHiddenTitle( 'User Account Setting' );

        Template::load('userInfo');
    }
}