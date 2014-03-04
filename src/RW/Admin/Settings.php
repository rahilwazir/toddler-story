<?php
namespace RW\Admin;

use RW\Modules\ParentModule;
use RW\Modules\Child;
use RW\ErrorHandling\DefaultErrors;
use RW\PostTypes\Children;
use RW\PostTypes\LifeStory;
use RW\External\GeoPlugin;

/**
 * Toddler Settings Menu
 *
 * @package
 *
 * @author rahilwazir
 * @copyright 2014
 * @version 1.0
 * @access public
 */
class Settings
{
    /**
     * Registration decider
     * @var bool 
     */
    protected $registered = 'false';

    /**
     * Should only contain Alphabetic and space characters
     * @var string
     */
    public $fl_valid = '/^[a-zA-Z\s?]+$/';

    /**
     * Should not contain except alphabetic, digits and '_' without quotes.
     * @var string
     */
    public $username_valid = '/^\w+$/';

    /**
     * Should contain at least any character except white-space
     * @var string
     */
    public $atleast = '/(?:[\S])/';

    /**
     * Access deny pages
     * @var array
     */
    protected static $deny_access_pages = array(
        'wpcf7',
        'tools.php',
    );

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts_styles'), 999);
        add_action('wp_enqueue_scripts', array($this, 'front_scripts_styles'), 999);

        add_action('wp_ajax_nopriv_registration', array($this, 'user_registration'));

        add_action('wp_ajax_nopriv_rw_login', array($this, 'user_login'));
        
        /**
         * Load default text domain
         */
        load_default_textdomain();
        
        //Remove menus from dashboard
        if (ParentModule::getCurrentParentId())
            add_action('admin_menu', array($this, 'removeMenus'));
        
        add_filter( 'locale', array($this, 'get_lang') );
    }

    /**
     * Set theme localization
     * @param string|object $locale
     * @return string
     */
    public function get_lang( $locale )
    {
        /*
        //Initialize Geo Plugin
        $geoplugin = new GeoPlugin();
        
        //Locate the location
        $geoplugin->locate();
        
        //Decide the language
        switch ($geoplugin->countryCode) {
            case 'DK': //Denmark
            case 'DE'; //Germany
            case 'GL': //Greenland
                return 'da_DK';
                break;

            default:
                return $locale;
                break;
        }
        
        if ($wp_query->query_vars['lang'] === "da") {
            return 'da_DK';
        }

        return $locale; // Default language */
    }

    /**
     * Front view, load scripts and styles
     */
    public function front_scripts_styles($hook)
    {
        global $hashtags;
        $js_lib = explode(',', (is_admin_pages()) ? 'jquery,jquery-ui-datepicker,underscore' : 'jquery,underscore');

        wp_enqueue_style('slicknav', get_template_directory_uri() . '/css/slicknav.css');
        wp_enqueue_style('dropdown-css', get_template_directory_uri() . '/css/lib/dropdown.css');
        wp_enqueue_style('main-style', get_stylesheet_uri());
        
        wp_enqueue_script('modernizr', get_template_directory_uri() . '/js/lib/modernizr.custom.63321.js', array('jquery'), '', true);
        wp_enqueue_script('slickNav', get_template_directory_uri() . '/js/jquery.slicknav.min.js', array('jquery'), '', true);
        wp_enqueue_script('dropdown-js', get_template_directory_uri() . '/js/lib/jquery.dropdown.js', array('jquery'), '', true);

        if (Child::page()) {
            $child_page = "true";
            wp_enqueue_style('child-style', get_template_directory_uri() . '/css/child-style.css');
        } else {
            $child_page = "false";
        }
        
        $localize_args = array(
            'theme_uri'         => get_stylesheet_directory_uri(),
            'site_url'          => site_url(),
            'admin_url'         => admin_url(),
            'ajax_url'          => admin_url('admin-ajax.php'),
            'is_user_logged_in' => (is_user_logged_in()) ?
            array(
                'is_admin'      => (ParentModule::currentUserisParent()) ? 'true' : 'false',
                'is_parent'      => (current_user_can('administrator')) ? 'true' : 'false',
            ) :
            'false',
            'profilePage'       => get_parent_admin_profile(),
            'registration'      => $this->registered,
            'isChildPage'       => $child_page,
            'parentAdminPage'   => (is_admin_pages()) ? 'true' : 'false',
            'link_pages'        => $hashtags,
            'defaultLanguage'  => array (
                'langCode' => rw_qtrans_getLanguage(),
                'langName' => rw_qtrans_getLanguageName()
            ),
        );

        if (is_user_logged_in()) {
            $localize_args['token'] = generateToken( user_info('ID') . '_' . user_info('login'), false );
        }

        // Public child viewing page
        if ( Child::page() || is_admin_pages() ) {
            wp_dequeue_style('slickNav');
            wp_dequeue_style('main-style');
            wp_dequeue_style('genericons');
            wp_dequeue_style('woocommerce_frontend_styles');
            wp_dequeue_style('mr_social_sharing');
            wp_dequeue_style('mr_social_sharing_custom');
            wp_dequeue_style('cnss_css');
            wp_dequeue_style('component');
            wp_dequeue_style('contact-form-7');

            if (!current_user_can('administrator'))
                wp_deregister_style('dashicons');
        }

        // Parent users admin panel
        if (is_user_logged_in() && is_admin_pages()) {
            wp_enqueue_style('confirm', get_template_directory_uri() . '/css/lib/confirm.css');
            wp_enqueue_style('child-admin', get_template_directory_uri() . '/css/child-admin.css');
            wp_enqueue_style('jquery-ui-css', ADMIN_URI . '/css/jquery-ui.css');

            wp_enqueue_script('jquery-hashchange-js', get_template_directory_uri() . '/js/lib/jquery.hashchange.js', array('jquery'), '', true);

            wp_enqueue_script('fb-inviter', '//connect.facebook.net/en_US/all.js', array('jquery'), '', true);

            // Removing woocommerce scripts/styles enqueue
            wp_dequeue_script('woocommerce');
            wp_dequeue_script('wc-cart-fragments');
            wp_dequeue_script('wc-add-to-cart');
            
            // Remove contact form script
            wp_deregister_script('jquery-form');
            
            // Remove social sharing script
            wp_dequeue_script('mr_social_sharing');
        }

        
        wp_enqueue_script('simple-modal', get_template_directory_uri() . '/js/lib/jquery.simplemodal.js', array('jquery'), '', true);
        
        wp_enqueue_script('toddler-init', get_template_directory_uri() . '/js/init.js', $js_lib, '', true);
        wp_localize_script('toddler-init', 'Toddler_Conf', $localize_args);
    }

    /**
     * Admin view, load scripts and styles
     */
    public function admin_scripts_styles($hook)
    {
        if (is_admin()) {
            if ($hook === 'post-new.php' || $hook === 'post.php') {
                wp_enqueue_style('toddler_style', ADMIN_URI . '/css/style.css');
                wp_enqueue_style('jquery-ui-css', ADMIN_URI . '/css/jquery-ui.css');
                wp_enqueue_style('chosen', ADMIN_URI . '/css/chosen.min.css');
            }

            if ($hook == 'post.php' || $hook == 'post-new.php') {
                wp_enqueue_script('chosen-js', ADMIN_URI . '/js/chosen.jquery.min.js', array('jquery', 'jquery-ui-datepicker'), '', true);
                wp_enqueue_script('toddlr_admin_init', ADMIN_URI . '/js/init.js', array('jquery', 'jquery-ui-datepicker'), '', true);
                wp_localize_script('toddlr_admin_init', 'Toddler_Conf', array(
                    'rw_uri' => ADMIN_URI,
                    'theme_uri' => get_stylesheet_directory_uri(),
                    'site_url' => site_url(),
                    'admin_url' => admin_url('admin-ajax.php'),
                    'post_types' => array(
                        'child' => Children::$post_type,
                        'life_story' => LifeStory::$post_type,
                        'is_child' => (strpos($_SERVER['REQUEST_URI'], Children::$post_type) !== false) ? 'true' : 'false',
                    )
                ));
            }

            if (ParentModule::currentUserisParent()) {
                wp_enqueue_style('parent', ADMIN_URI . '/css/parent.css');
            }

            /**
             * Custom css file to make admin dashboard more fancy
             */
           wp_enqueue_style('toddler-admin-css', ADMIN_URI.'/css/admin.css');
        }
    }

    public function user_registration()
    {

        $reg_data = json_decode(filter_input(INPUT_POST, 'all_data'));

        /**
         * Registration data
         */
        $rd = $final_result = $rw_errors = array();
        
        foreach ($reg_data as $val) {
            $rd[$val->name] = $val->value;
        }

        if (wp_verify_nonce($rd['user_reg_nonce'], 'user_reg')
            && $_POST['action'] === 'registration'
        ) {
            $user_id = username_exists($rd['reg_username']);

            $rw_errors['reg_firstname'] = ($rd['reg_firstname'] === '') ? __('Required.')
                    : ((preg_match($this->fl_valid, $rd['reg_firstname']) === 0) ? __('Should be alphabetic.') : '');

            $rw_errors['reg_lastname'] = ($rd['reg_lastname'] === '') ? __('Required.')
                    : ((preg_match($this->fl_valid, $rd['reg_lastname']) === 0) ? __('Should be alphabetic.') : '');

            $rw_errors['reg_username'] = ($rd['reg_username'] === '') ? __('Required')
                    : (($user_id) ? __('Already exists.') : ((preg_match($this->username_valid, $rd['reg_username']) === 0) ? __('Should not contain any special characters except "_" without quotes.') : '') );

            $rw_errors['reg_email'] = ($rd['reg_email'] === '') ? __('Required')
                    : ((email_exists($rd['reg_email'])) ? __('Already exists.') : ((is_email($rd['reg_email']) === false) ? __('Email is not valid.') : '') );

            $rw_errors['reg_pass'] = ($rd['reg_pass'] === '') ? __('Required.')
                : ((preg_match($this->atleast, $rd['reg_pass']) === 0) ? __('Required') : '');

            $rw_errors['reg_repass'] = ($rd['reg_repass'] === '') ? __('Required.')
                : ((preg_match($this->atleast, $rd['reg_repass']) === 0) ? __('Required') : (($rd['reg_pass'] !== $rd['reg_repass']) ? __('Passwords did not match.') : ''));

            $rw_errors['tos_n_pp'] = ($rd['tos_n_pp'] === '1') ? '' : __('Required.');

            /**
             * Remove empty values
             */
            $rw_errors_cleaned = array_filter($rw_errors);

            if (count($rw_errors_cleaned) > 0) {
                $final_result['errors'] = $rw_errors_cleaned;
            } else {
                //$random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);

                $_new_user_id = array(
                    'user_pass' => trim($rd['reg_pass']),
                    'user_login' => trim($rd['reg_username']),
                    'user_email' => trim($rd['reg_email']),
                    'display_name' => trim(ucwords($rd['reg_firstname'] . ' ' . $rd['reg_lastname'])),
                    'first_name' => trim(ucwords($rd['reg_firstname'])),
                    'last_name' => trim(ucwords($rd['reg_lastname'])),
                );

                $user_id = wp_insert_user($_new_user_id);
                
                //RCP Free membership
                update_rcp_meta($user_id);

                update_user_meta($user_id, 'user_default_lang', $rd['reg_lang']);

                //Registration succeed
                $this->registered = true;

                if (!is_wp_error($user_id)) {
                    $body = '<table><tbody>
                            <tr><th>'.get_bloginfo('title').' Registration Information</th></tr>
                        <tr><td>Username:</td><td>' . $rd['reg_username'] . '</td></tr>
                        <tr><td>Password:</td><td>' . replace_chars($rd['reg_pass']) . '</td></tr>
                    </tbody></table>
                    <strong>Note: </strong> Keep this information confidential.';

                    wp_mail($rd['reg_email'], get_bloginfo('title'), $body);
                } else {
                    $final_result['errors'];
                }

                $final_result['success'] = 'You have been registered successfully. You have recieved an email.';
            }

            if ($this->registered === true) {
                $this->registered = 'true';
            }
            
            echo json_encode($final_result);
        }
        
        exit;
    }

    public function user_login()
    {
        global $hashtags;
        $login_data = json_decode(filter_input(INPUT_POST, 'all_data'));

        /**
         * Login data
         */
        $rd = $final_result = $rw_errors = array();

        foreach ($login_data as $val) {
            $rd[$val->name] = $val->value;
        }

        if (wp_verify_nonce($rd['user_login_nonce'], 'user_login')
            && filter_input(INPUT_POST, 'action') === 'rw_login'
        ) {
            $user_login = $rd['login_username'];
            $user_pass = $rd['login_password'];
            $remember_me = $rd['login_remember'];

            $user_id = username_exists($user_login);

            $rw_errors['login_username'] = ($user_login === '') ? __('Required')
                : (($user_id === NULL) ? __('User doesn\'t exists.') : '');

            $rw_errors['login_password'] = ($user_pass === '') ? __('Required.')
                : ((preg_match($this->atleast, $user_pass) === 0) ? __('Required') : '');

            $creds = $final_result = array();

            $rw_errors_cleaned = array_filter($rw_errors);

            if (count($rw_errors_cleaned) > 0) {
                $final_result['errors'] = $rw_errors_cleaned;
            } else {
                $creds['user_login'] = $user_login;
                $creds['user_password'] = $user_pass;
                $creds['remember'] = ($remember_me === "true") ? true : false;

                $user = wp_signon( $creds, false );

                if ( is_wp_error($user) ) :
                    $final_result['errors']['top_error'] = $user->get_error_message();
                else :
                    $final_result['success'] = array(
                        'message'       => 'Logged in successfully. Please wait while you\'re being redirecting...',
                        'redirect_to'   => in_array('parent', $user->roles) ? get_parent_admin_profile($hashtags[4]) : admin_url(),
                    );
                endif;
            }

            echo json_encode($final_result);
        }

        exit;

    }

    /**
     * Prevent default access to wordpress native pages
     * @return void
     */
    public function preventAccessToDefaults()
    {
        if (is_array(self::$deny_access_pages)) {
            foreach (self::$deny_access_pages as $denied_page) {
                if (strpos($_SERVER['REQUEST_URI'], $denied_page) !== false) {
                    DefaultErrors::notAllow();
                }
            }
        }

        if (strpos($_SERVER['REQUEST_URI'], 'edit.php') !== false
            && strpos($_SERVER['REQUEST_URI'], 'post_type') === false
        ) {
            DefaultErrors::notAllow();
        }

        if (strpos($_SERVER['REQUEST_URI'], 'post-new.php') !== false
            && strpos($_SERVER['REQUEST_URI'], 'post_type') === false
        ) {
            DefaultErrors::notAllow();
        }

    }

    /**
     * Remove native and custom admin menus
     * @return void
     */
    public function removeMenus()
    {
        if (is_array(self::$deny_access_pages)) {
            foreach (self::$deny_access_pages as $denied_page) {
                remove_menu_page($denied_page);
            }
        }
    }
}