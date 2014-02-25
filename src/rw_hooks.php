<?php

use RW\ErrorHandling\DefaultErrors;
use RW\Modules\ParentModule;

//Fix for woocommerce product category %product_cat%
remove_filter( 'post_type_link', 'woocommerce_product_post_type_link', 10, 2 );
add_filter( 'post_type_link', 'woocommerce_product_post_type_link', 600, 2 );

// Remove admin notice from post/pages
// remove_filter('the_editor', 'qtrans_modifyRichEditor');

/**
 * Set html headers for wordpress email sender
 * @return string
 */
add_filter('wp_mail_content_type', function () {
    return 'text/html';
});

/**
 * Logout to home page
 * @return void
 */
add_action('wp_logout', function () {
    session_destroy();
    wp_redirect(get_permalink_by_slug( 'login' ));
    exit;
});

/**
 * Block pages from non registered users
 */
add_action( 'template_redirect', function () {
    global $post;

    $data = get_post_meta($post->ID, '_toddler_bca', true);

    if ( ($data === '1' || is_admin_pages()) && !is_user_logged_in() ) {
        wp_redirect(get_permalink_by_slug('login'));
        exit;
    }
});

/**
 * Disable admin bar for all users except administrators
 */
if (!current_user_can('administrator')) {
    show_admin_bar (false);
}

/**
 * Stop any users from accessing wp-admin except administrators (But use wp-admin for ajax request)
 */
add_action('admin_init', function () {
    if (!is_request_ajax() ) {
        if (!current_user_can('administrator')) {
           DefaultErrors::notAllow();
        }
    }
});

/**
 * Redirect non-admins to profile page after logging into the site.
 */
add_filter( 'login_redirect', function ( $redirect_to, $request, $user ) {
    if (!is_wp_error($user)) {
        return ( in_array( 'administrator', $user->roles ) ) ?
            admin_url() :
            ( (in_array( 'parent', $user->roles )) ? get_parent_admin_profile() : home_url() );
    }
}, 10, 3 );

/**
 * Removing the action hooks (most of the plugins) if user is logged in
 */
if ( is_user_logged_in() ) {
    /**
     * Remove facebook for signup
     */
    remove_action('wp_footer', 'jfb_output_facebook_init');
    remove_action('wp_footer', 'jfb_output_facebook_callback');
    remove_action('wp_footer', 'jfb_output_facebook_init');
}

/**
 * Update user meta after registered from facebook
 */
add_action('wpfb_inserted_user', function( $args ) {
    //RCP Free membership
    update_rcp_meta($args['WP_ID']);
});

/**
 * Filter hook to rename Credit Card to Braintree
 */
add_filter( 'rcp_payment_gateways', function ( $gateways ) {
    $gateways['braintree'] = __( 'Credit Card (Braintree)', 'rcp_braintree' );
    return $gateways;
});

/**
 * Add language selection to RCP registration form
 */
add_action('rcp_after_password_registration_field', function () {
    echo '<p id="rcp_password_again_wrap">
            <label for="reg_lang">Default language</label>';
    echo qtrans_language_dropdown(array(
        'class' => 'child_inputs',
        'id' => 'reg_lang'
    ));
    echo '</p>';
});

/**
 * Process language selection of RCP registration form
 */
add_action('rcp_form_processing', function ($global_post, $user_id, $price) {
    update_user_meta($user_id, 'user_default_lang', sanitize_text_field($_POST['reg_lang']));
}, 10, 3);

/**
 * Initialize the session
 */
add_action( 'init', function() {
    if ( ! session_id() && is_user_logged_in() ) {
        session_start();
    }
});