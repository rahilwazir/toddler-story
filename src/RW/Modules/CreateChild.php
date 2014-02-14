<?php
namespace RW\Modules;

use RW\Modules\PostInsertion;
use RW\PostTypes\Children;
use RW\ErrorHandling\Error;

class CreateChild implements PostInsertion
{
    private static $_action = null;
    private static $_event = null;
    
    const CHILD_ERROR_CODE = 1;
    
    public static function insertPosts($action = '')
    {
        self::$_action = $action;
        add_action('wp_ajax_' . self::$_action, array(__CLASS__, __METHOD__));
        $full_data = filter_input(0, 'full_data');

        $rd = process_serialize_data($full_data);
        
        if (wp_verify_nonce($rd['rw_nonce'], 'add_child')) {
            self::$_event = 'add';
        } else if (wp_verify_nonce($rd['rw_nonce'], 'update_child')) {
            self::$_event = 'update';
        } else {
            exit('What the heck are you trying to do?');
        }

        if (filter_input(0, 'action') === self::$_action ) {

            $final_result = array();
            
            /**
             * Check slug uniqueness
             */
            $data = wp_unique_post_slug($rd['d_url'], $rd['post_id'], 'publish', Children::$post_type, $rd['post_id']);
            $d_url = preg_match('/(' . $rd['d_url'] . '[-?0-9]+$)/', $data);
            
            /**
             * Remove error code if already contain
             */
            Error::remove_error(self::CHILD_ERROR_CODE);

            Error::set_error(self::CHILD_ERROR_CODE, 'story_title', ($rd['story_title'] === '') ? __('Required.') : '');
            Error::set_error(self::CHILD_ERROR_CODE, 'dob', ($rd['dob'] === '') ? __('Required.') : '');
            Error::set_error(self::CHILD_ERROR_CODE, 'first_name', ($rd['first_name'] === '') ? __('Required.') : '');
            Error::set_error(self::CHILD_ERROR_CODE, 'last_name', ($rd['last_name'] === '') ? __('Required.') : '');
            Error::set_error(self::CHILD_ERROR_CODE, 'relation', ($rd['relation'] === '') ? __('Required.') : '');
            Error::set_error(self::CHILD_ERROR_CODE, 'sex', ($rd['sex'] === '') ? __('Required.') : '');
            Error::set_error(self::CHILD_ERROR_CODE, 'd_url', ($rd['d_url'] === '') ? __('Required.') :
                (($d_url) ? __('Already taken. Try something else.') : ''));

            $final_result['errors'] = Error::get_error(self::CHILD_ERROR_CODE);
            
            if ( count($final_result['errors']) < 1 ) {
                
                if (self::$_event === 'add') {
                    $my_post = array(
                        'post_title' => $rd['story_title'],
                        'post_name' => $rd['d_url'],
                        'post_status' => 'publish',
                        'post_author' => user_info('ID'),
                        'post_type' => Children::$post_type
                    );
                    
                    $_post_id = wp_insert_post($my_post);
                    
                } else if (self::$_event === 'update') {
                    
                    if (!Child::exists($rd['post_id'], user_info('ID'))) {
                        exit('The post is not yours');
                    }

                    $my_post = array(
                        'ID' => $rd['post_id'],
                        'post_title' => $rd['story_title'],
                        'post_name' => $rd['d_url'],
                        'post_author' => user_info('ID'),
                        'post_type' => Children::$post_type
                    );
                    
                    $_post_id = wp_update_post($my_post);
                }

                if (!is_wp_error($_post_id) && $_post_id !== 0) {
                    update_post_meta($_post_id, '_child_first_name', $rd['first_name']);
                    update_post_meta($_post_id, '_child_last_name', $rd['last_name']);
                    update_post_meta($_post_id, '_toddler_sex', $rd['sex']);
                    update_post_meta($_post_id, '_toddler_relation', $rd['relation']);
                    update_post_meta($_post_id, '_dob_child', $rd['dob']);
                    update_post_meta($_post_id, '_toddler_bg', $rd['chosen_bg']);
                    update_post_meta($_post_id, '_toddler_ata', $rd['public_access']);
                    update_post_meta($_post_id, '_toddler_parent_user', user_info('ID'));

                    update_post_meta($_post_id, '_qts_slug_da', $rd['d_url']);
                    update_post_meta($_post_id, '_qts_slug_en', $rd['d_url']);

                    if (isset($_FILES['baby_img'])) {
                        $uploaded_image = rw_upload_attachment($_FILES['baby_img']);
                        $thumb_id = insert_post_attachment($_post_id, $uploaded_image);
                        $test = set_post_thumbnail($_post_id, $thumb_id);
                    }

                    $final_result['ok'] = 'Child added successfully';
                }
            }

            echo json_encode($final_result);
        }
        exit;
    }
}
