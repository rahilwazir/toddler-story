<?php
namespace RW\Modules;

use RW\ErrorHandling\Error;

class ParentModule
{
    
    private static $_action = null;
    const USER_ERROR_CODE = 23;
    /**
     * Retrieve Parent ID
     * @return int
     */
    public static function getCurrentParentId()
    {
        return (check_user_role('parent')) ? user_info('ID') : false;
    }
    
    /**
     * Check if current user is parent
     * @return bool
     */
    public static function currentUserisParent()
    {
        return (check_user_role('parent')) ? true : false;
    }
    
    public static function update($action)
    {
        self::$_action = $action;
        add_action('wp_ajax_' . self::$_action, array(__CLASS__, __METHOD__));
        $full_data = filter_input(0, 'full_data');

        $rd = process_serialize_data($full_data, array('pass', 'repass'));
        
        if (filter_input(0, 'action') === self::$_action &&
                wp_verify_nonce($rd['rw_nonce'], 'update_user')) {

            $final_result = array();
            
            /**
             * Remove error code if already contain
             */
            Error::remove_error(self::USER_ERROR_CODE);

            Error::set_error(self::USER_ERROR_CODE, 'fname', ($rd['fname'] === '') ? __('Required.') : '');
            Error::set_error(self::USER_ERROR_CODE, 'lname', ($rd['lname'] === '') ? __('Required.') : '');
            Error::set_error(self::USER_ERROR_CODE, 'email', ($rd['email'] === '') ? __('Required.') : '');
                    
            Error::set_error(self::USER_ERROR_CODE, 'repass', (($rd['pass'] !== '' && $rd['repass'] === '') ? __('Required') :
                    (($rd['pass'] !== $rd['repass']) ? __('Password must match') : '')));

            $final_result['errors'] = Error::get_error(self::USER_ERROR_CODE);

            if ( count($final_result['errors']) < 1 ) {
                $_args = array (
                    'ID' => user_info('ID'),
                    'first_name' => $rd['fname'],
                    'last_name' => $rd['lname'],
                    'user_email' => $rd['email'],
                );

                $pass_listener = false;

                if (!empty($rd['pass']) && !empty($rd['repass'])) {
                    $_args['user_pass'] = $rd['pass'];
                    $pass_listener = true;
                }

                $_user_id = wp_update_user( $_args ) ;

                if (!is_wp_error($_user_id)) {

                    if (isset($_FILES['user_profile_pic'])) {
                        $uploaded_image = rw_upload_attachment($_FILES['user_profile_pic']);
                        insert_post_attachment(0, $uploaded_image);
                        update_user_meta($_user_id, 'cupp_upload_meta', $uploaded_image['url']);
                    }

                    if ($pass_listener) {
                        $final_result['redirect'] = esc_url(get_permalink_by_slug('login'));
                        wp_clear_auth_cookie();
                    }

                    $final_result['ok'] = 'User updated successfully';
                }
            }
            
            echo json_encode($final_result);
        }
        
        exit;
    }

}