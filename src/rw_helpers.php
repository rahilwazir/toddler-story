<?php

use RW\PostTypes\Children;
use RW\Modules\Child;

/**
 * Custom image sizes
 */
add_image_size('small', 75, 75, true);
add_image_size('dp', 147, 149, true);
add_image_size('chmng_dp', 170, 166, true);

/**
 * Same as default get_terms except it adds additional `link` and `thumbnail`
 * @param string $taxonomy
 * @param array|string $args
 * @return array
 */
function rw_get_terms($taxonomy, $args = '')
{
    $location_term = get_terms($taxonomy, $args);

    $i = 0;

    foreach ($location_term as $loc) {
        if (z_taxonomy_image_url($loc->term_id))
            $location_term[$i]->thumbnail = z_taxonomy_image_url($loc->term_id);

        $location_term[$i]->link = get_term_link($loc);
        $i++;
    }

    return $location_term;
}

/**
 * Get full thumbnail uri
 * @param int $post_id
 * @return string
 */
function get_full_thumbnail_uri($post_id)
{
    $large_image_url = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full');
    return ($large_image_url[0]) ? $large_image_url[0] : '';
}

/**
 * Get custom post type posts
 * @param array $args
 * @param string $thumb_size Thumbnail size of the image
 * @return mixed
 */
function get_custom_posts($args = array(), $thumb_size = 'full')
{
    global $post;
    
    $originalpost = $post;

    $default_args = array(
        'post_type'         => (array_key_exists('post_type', $args) ? $args['post_type'] : Children::$post_type),
        'posts_per_page'    => (array_key_exists('posts_per_page', $args) ? $args['posts_per_page'] : 1),
        'post_status'       => (array_key_exists('post_status', $args) ? $args['post_status'] : 'publish'),
    );

    $diff = array_diff_assoc($args, $default_args);
    $merged = array_merge($default_args, $diff);

    $new_query = new WP_Query($merged);

    if ($new_query->have_posts()) {

        $array = array();
        $i = 0;

        while ($new_query->have_posts()) : $new_query->the_post();

            $array[$i] = new stdClass();

            $array[$i]->ID = get_the_ID();
            $array[$i]->title = get_the_title();
            $array[$i]->permalink = get_permalink();

            $array[$i]->time = get_the_time('d F, Y');

            $array[$i]->day = get_the_time('d');
            $array[$i]->month = get_the_time('F');
            $array[$i]->year = get_the_time('Y');

            $comments = wp_count_comments( $post->ID );

            $array[$i]->approved = $comments->approved;
            $array[$i]->total_comments = $comments->total_comments;

            $large_image_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $thumb_size);
            $array[$i]->thumbnail = (!$large_image_url[0]) ? image_placeholder() : $large_image_url[0];

            $array[$i]->content = get_the_excerpt();
            
            if ( Child::age() ) {
                $array[$i]->age = Child::age();
            }
            
            $i++;
        endwhile;
        
        wp_reset_postdata();

        $post = $originalpost;

        return $array;
    }

    return 0;
}

/**
 * Check current user role e.g: contributor|subscriber ...
 * @param string $role
 * @param string|int $user_id
 * @return boolean
 */
function check_user_role($role, $user_id = null)
{
    if (is_numeric($user_id))
        $user = get_userdata($user_id);
    else
        $user = wp_get_current_user();

    if (empty($user))
        return false;

    return in_array($role, (array)$user->roles);
}

/**
 * Get Current Logged in user info
 * @param string $data_string
 * @param int $user_id
 * @return string
 */
function user_info($data_string, $user_id = null)
{
    if (empty($data_string)) return;

    if (is_user_logged_in()) {
        global $current_user;
        get_currentuserinfo();
    } else {
        $current_user = get_userdata($user_id);
    }
    $result = null;

    switch ($data_string) {
        case 'login':
            $result = $current_user->user_login;
            break;
        case 'email':
            $result = $current_user->user_email;
            break;
        case 'first_name':
            $result = $current_user->user_firstname;
            break;
        case 'last_name':
            $result = $current_user->user_lastname;
            break;
        case 'display_name':
            $result = $current_user->display_name;
            break;
        case 'role':
            $result = $current_user->roles;
            break;
        case 'created_on':
            $result = $current_user->user_registered;
            break;
        case 'ID':
        case 'id':
            $result = intval($current_user->ID);
        default:
            break;
    }

    return $result;
}

/**
 * Convert lower case or any special chars to nice human readable word.
 * @param $str string
 * @return string
 */
function nice_word($str)
{
    $slug = preg_replace('/[^a-zA-Z0-9]/', ' ', strip_tags($str));
    $slug = ucwords(strtolower($slug));
    return trim($slug);
}

/**
 * Debug
 * @param mixed $dump The variable to be debugged
 * @return array
 */
if (!function_exists('dump')) {
    function dump($dump)
    {
        echo '<pre>';
        var_dump($dump);
        echo '</pre>';
    }
}

/**
 * Replace slang/abusive/sensitive... with '*' without quotes.
 * @param string $input The string to be replace with '*' without quotes.
 * @return string
 */
function replace_chars($input)
{
    return preg_replace_callback('/(?:.*?)(.{3})$/', function ($matches) {
        return preg_replace('/./', '*', $matches[0]) . $matches[1];
    }, $input);
}

/*
 * Usage for a custom post type named 'movies':
 * unregister_post_type( 'movies' );
 *
 * Usage for the built in 'post' post type:
 * unregister_post_type( 'post', 'edit.php' );
 *
 * @param string $post_type
 * @param string $slug
 * @return void
*/
function unregister_post_type($post_type, $slug = '')
{
    global $wp_post_types;
    if (isset($wp_post_types[$post_type])) {
        unset($wp_post_types[$post_type]);
        $slug = (!$slug) ? 'edit.php?post_type=' . $post_type : $slug;
        remove_menu_page($slug);
    }
}

/**
 * Get variable globally like $_GET, but safest way.
 * @param string $var
 * @param constant $filter
 * @return mixed
 */
function rw_get($var, $filter = FILTER_SANITIZE_STRING)
{
    return (array_key_exists($var, $_GET))
        ?  filter_input(INPUT_GET, $var, $filter)
        : false;
}

/**
 * Post variable globally like $_POST, but safest way.
 * @param string $var
 * @param constant $filter
 * @return mixed
 */
function rw_post($var, $filter = FILTER_SANITIZE_STRING)
{
    return (array_key_exists($var, $_GET))
        ?  filter_input(INPUT_POST, $var, $filter)
        : false;
}

/**
 * Generate extendable date from given timestamp //0 Year 0 Month 9 Days| 2 Years 5 Months 1 Day
 * @param string $birthdate Date Form 1-11-2000, 2000-11-1. See more at DateTime
 * @return string
 */
function process_date($birthdate)
{
    $datetime1 = new DateTime($birthdate);
    $datetime2 = new DateTime(date('d-m-Y'));
    $interval = $datetime1->diff($datetime2);
    $up_down = $interval->format('%R');
    
    if ( strpos($up_down, '-') !== false ) {
        $format = $interval->format('%y Years %m Months %d Days Remaining');
    } else {
        $format = $interval->format('%y Years %m Months %d Days');
    }

    return $format;
}

/**
 * Display login link
 */
function rw_login_link()
{
    if (!is_user_logged_in()) {
        echo '<p>Don\'t have an account? <a href="'.get_permalink(330).'">Register</a></p>';
    }
}

/**
 * Display register link
 */
function rw_register_link()
{
    if (!is_user_logged_in()) {
        echo '<p>Already have an account? <a href="'.get_permalink(332).'">Log in</a></p>';
    }
}

/**
 * Retrieve permalink by slug
 */
function get_permalink_by_slug($slug)
{
    return get_permalink(get_page_by_path($slug));
}

/**
 * Retrieve ids of post/pages which have marked as block
 * @param int|string $id
 * @return array
 */
function in_block_posts($id)
{
    $post_array = get_custom_posts(array(
        'post_type' => array('post', 'page'),
        'posts_per_page' => -1
    ));

    $ids = array();

    foreach ($post_array as $p) {
        $data = get_post_meta($p->ID, '_toddler_bca', true);
        if ($data === "1")
            $ids[] = $p->ID;
    }
 
    return in_array($id, $ids);
}

/**
 * Retrieve boolean indicating is this post is within admin templates
 * @return bool
 */
function is_admin_pages($template = 'parent-admin-tpl')
{
    return (strpos(get_page_template(), $template) !== false) ? true : false;
}

/**
 * Retrieve user (parent) profile
 * @param string Append string to url
 * @return string
 */
function get_parent_admin_profile($str = '')
{
    global $hashtags;
    
    if ( '' === $str ) {
        $str = '#' . $hashtags[4];
    }
    
    return esc_url(get_permalink(351) . $str);
}

/**
 * Check if request is xmlhttprequest
 * @return bool
 */
function is_request_ajax()
{
    return (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ? true :  false;
}

/**
 * Convert underscore string to camelCase string
 * @param string $str
 * @return string
 */
function convert_to_cc($str)
{
    return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $str))));
}

/**
 * Strict comparison between two strings and add class of active of given element
 * @param string $current
 * @param string $new_value
 * @param string $class_name
 * @return string
 */
function active_class($current, $new_value, $class_name='active', $echo = true)
{
    if ( (string) $current === (string) $new_value ) {
        if ($echo) {
            echo $class_name;
        } else {
            return $class_name;
        }
    }
}

/**
 * Uploads attachment to the wordpress directory
 * @param array $files Should be $_FILES['file_input_name']
 * @return array
 */
function rw_upload_attachment(array $files) {
    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        $uploadedfile = $files;

        if (strpos($files['type'], 'image/') !== false) {
            $upload_overrides = array( 'test_form' => false );
            $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

            if ( $movefile ) {
                return $movefile;
            }
        }
    }
}

/**
 * Insert attachment to the post
 * @param int $post_id
 * @param array $movefile
 * @return array
 */
function insert_post_attachment($post_id, array $movefile)
{
    $wp_filetype = wp_check_filetype(basename($movefile['file']), null );
    $wp_upload_dir = wp_upload_dir();

    $attachment = array(
        'guid' => $wp_upload_dir['url'] . '/' . basename( $movefile['file'] ), 
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $movefile['file'] ) ),
        'post_status' => 'inherit'
    );

    $attach_id = wp_insert_attachment( $attachment, $movefile['file'], absint($post_id) );

    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    $attach_data = wp_generate_attachment_metadata( $attach_id, $movefile['file'] );
    wp_update_attachment_metadata( $attach_id, $attach_data );

    return $attach_id;
}

/**
 * Process serialize and sanitize data
 * @param string $serialzed_data JSON Javascript Array container objects [{...}]
 * @param array $skip Keys to skip from sanitizing mostly used for passwords
 * @return array
 */
function process_serialize_data($serialzed_data, $skip = array())
{
     $login_data = json_decode($serialzed_data);

    /**
     * Request data
     */
    foreach ($login_data as $val) {
        if (!in_array($val->name, $skip)) {
            $rd[$val->name] = sanitize_text_field($val->value);
        } else {
            $rd[$val->name] = $val->value;
        }
    }
    
    return $rd;
}

/**
 * Retrieve placeholder image
 * @return string
 */
function image_placeholder()
{
    return get_template_directory_uri() . '/images/avatar.png';
}

/**
 * Retrieve custom post type link
 * @return string
 */
function get_post_type_link()
{
    return site_url(Children::$slug . user_info('login') . '/');
}

/**
 * Update RCP user meta
 * @param int $user_id
 * @return void
 */

function update_rcp_meta( $user_id )
{
    $subscription_key = rcp_generate_subscription_key();
    update_user_meta($user_id, 'rcp_subscription_key', $subscription_key);
    update_user_meta($user_id, 'rcp_subscription_level', 2); //2 as Free
    update_user_meta($user_id, 'rcp_status', 'free'); //2 as Free
    update_user_meta($user_id, 'rcp_expiration', 'none');
}

/**
 * Genereate dropdown language selection
 * @param array $params
 * @return mixed If success return string, otherwise false
 */
function qtrans_language_dropdown($params = array())
{
    $output = false;
    $atts = $active = ' ';
    
    extract($params);
    
    $selector = (( $selector ) ? $selector : 'select');
    $selectorChild = (( $selectorChild ) ? $selectorChild : 'option');
    $atts .= ' id="' . (( $id ) ? $id : '') . '" ';
    $atts .= ' class="' . (( $class ) ? $class : 'lang_selector') . '" ';
    $atts .= ' name="' . (( $name ) ? $name : 'reg_lang') . '" ';
    
    $selected = ($selected) ? $selected : qtrans_getLanguage();
    
    if ( qtrans_getAvailableLanguages(',') ) :
        $output = '';
        $output = '<' . $selector . $atts . '>';
            foreach ( qtrans_getAvailableLanguages(',') as $lang ) :
                
                if ( $selector === 'select' ) {
                    $active = selected($selected, $lang, false);
                    $value = 'value="' . $lang . '"';
                } else if (  $selector === 'ul'  ) {
                    $active = active_class($lang, $selected, false);
                    $value = 'data-lang-value="' . $lang . '"';
                }
                
                $output .= '<' . $selectorChild . ' ' . $value . ' ' . $active . '>' . qtrans_getLanguageName($lang) . '</' . $selectorChild . '>';
            endforeach;
        $output .= '</'.$selector.'>';
    endif;
    
    return $output;
}

/**
 * Generate language dropdown selection
 * @return void
 */
function generate_language_dropdown($params = array())
{
    extract($params);

    if ( is_active_sidebar(5) && $sidebar ) {
        echo '<div class="hide">';
            dynamic_sidebar(5);
        echo '</div>';
    }

    echo qtrans_language_dropdown(array (
        'class' => 'lang_selector',
        'selected' => qtrans_getLanguage()
    ));
}

/**
 * Output input hidden field
 * @param string $title
 */
function createHiddenTitle($title, $default = '')
{
    echo '<input type="hidden" value="' . $title . '" id="title_name">';
    if ( $default ) echo '<input type="hidden" value="' . $default . '" id="title_desc">';
}

/**
 * Trim content
 */
function trimIt($content, $start = 0, $end = 100, $cStr = '...')
{
    return substr($content, $start, $end) . $cStr;
}