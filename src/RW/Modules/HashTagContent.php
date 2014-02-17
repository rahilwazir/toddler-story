<?php

namespace RW\Modules;

use RW\PostTypes\MetaBoxes\BackgroundChooser;
use RW\PostTypes\MetaBoxes\RelationMeta;
use RW\PostTypes\Children;
use RW\PostTypes\MetaBoxes\GenderMeta;
use RW\Modules\Child;

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

    ?>
        <form method="post" name="<?php echo self::$array_builder['form_name']; ?>" enctype="multipart/form-data">
            <?php
                if ($update) {
                    echo '<input type="hidden" value="'.$post_id.'" name="post_id">';
                }

                $nonce = wp_create_nonce(self::$array_builder['nonce']);
                echo '<input type="hidden" value="'.$nonce.'" name="rw_nonce" />';
            ?>
            <section id="form-top-puts" class="clearfix">
                <div class="fleft">
                    <div class="input-box-wrap">
                        <div class="form-control">
                            <label for="first_name">First Name:</label>
                        </div>
                        <div class="form-control form-input">
                            <input id="first_name" name="first_name" class="child_inputs" placeholder="First Name" value="<?php echo self::$array_builder['first_name']['value']; ?>" />
                        </div>
                    </div>
                    
                    <div class="input-box-wrap">
                        <div class="form-control">
                            <label for="last_name">Last Name:</label>
                        </div>
                        <div class="form-control form-input">
                            <input id="last_name" name="last_name" class="child_inputs" placeholder="Last Name" value="<?php echo self::$array_builder['last_name']['value']; ?>" />
                        </div>
                    </div>
                    
                    <div class="input-box-wrap">
                        <div class="form-control">
                            <label for="dob">Date of Birth:</label>
                        </div>
                        <div class="form-control form-input">
                            <input id="dob" name="dob" class="child_inputs" placeholder="11-11-2011" value="<?php echo self::$array_builder['dob']['value']; ?>" />
                        </div>
                    </div>
                    
                    <div class="input-box-wrap">
                        <div class="form-control">
                            <label for="sex">Sex:</label>
                        </div>
                        <div class="form-control form-input">
                            <select id="sex" name="sex" class="child_inputs">
                                <?php foreach (GenderMeta::$field_values as $val) : ?>
                                <option value="<?php echo $val; ?>" <?php selected(self::$array_builder['sex']['value'], $val); ?>><?php echo $val; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="fright">
                    <div class="input-box-wrap">
                        <div class="form-control">
                            <label for="relation">Your Relation:</label>
                        </div>
                        <div class="form-control">
                            <select id="relation" name="relation" class="child_inputs">
                                <?php foreach (RelationMeta::$field_values  as $val) : ?>
                                <option value="<?php echo $val; ?>" <?php selected(self::$array_builder['relation']['value'], $val); ?>><?php echo $val; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="input-box-wrap">
                        <div class="form-control">
                            <label for="story_title">Story Title:</label>
                        </div>
                        <div class="form-control form-input">
                            <input id="story_title" name="story_title" class="child_inputs" placeholder="Your awesome title" value="<?php echo self::$array_builder['title']; ?>" />
                        </div>
                    </div>
                    
                    <div class="input-box-wrap">
                        <div class="form-control">
                            <label for="d_url">Direct URL:<p class="description"><?php echo get_post_type_link(); ?></p></label>
                        </div>
                        <div class="form-control form-input">
                            <input id="d_url" name="d_url" class="child_inputs" placeholder="child-slug" value="<?php echo self::$array_builder['d_url'] ?>" />
                        </div>
                    </div>
                </div>
            </section>
            <br clear="all" />
            
            <section id="background-chooser" class="clearfix">
                <?php $contents = BackgroundChooser::has_post('dp'); ?>
                <ul>
                    <li data-id="none">
                        <span id="none-bg"></span>
                        <span class="check <?php active_class('none', self::$array_builder['thumb_id']); ?>"></span>
                    </li>
                    <?php if ($contents !== 0) : foreach ($contents as $content) : ?>
                    <li data-id="<?php echo $content->ID; ?>">
                        <img src="<?php echo $content->thumbnail; ?>" />
                        <span class="check <?php active_class($content->ID, self::$array_builder['thumb_id']); ?>"></span>
                    </li>
                    <?php endforeach; endif; ?>
                </ul>
                <input type="hidden" name="chosen_bg" value="<?php echo self::$array_builder['thumb_id']; ?>" />
            </section>
            
            <section id="select-baby-image" class="clearfix">
                <input type="file" name="baby_img" id="baby_img" class="disable" />
                <div id="preview-image" class="clearfix">
                    <?php echo self::$array_builder['thumbnail']; ?>
                    <span class="remove disable"></span>
                </div>
                <label for="baby_img" class="submit-button clearfix">Upload a Child photo</label>
            </section>

            <section id="privacy_settings" class="clearfix">
                <p class="masquerade-heading">Privacy Settings:</p>
                <p>You have the option to choose whether the story should be available to everyone or only those you choose. To provide access to all check the field below. Otherwise, you select who you want to share with later.</p>

                <p class="neat-space">
                    <span class="checkbox check <?php active_class('1', self::$array_builder['public_access']); ?>"></span> Allow Public access <input type="hidden" name="public_access" value="<?php echo self::$array_builder['public_access']; ?>" />
                </p>
            </section>
            
            <section id="form_action_buttons" class="clearfix">
                <div class="fright">
                    <input type="button" name="cancel" class="submit-button cancel-btn ajaxify" value="Cancel" />
                    <?php
                        if ($update) {
                            echo '<input type="button" value="Delete Child" data-action=\'{"action" : "delete_child", "id" : '.$post->ID.'}\' class="ajaxify submit-button danger" name="delete_child">';
                        }
                    ?>
                    <input type="submit" name="save_changes" class="submit-button" value="Save Changes" />
                </div>
            </section>
        </form>
    <?php
        wp_reset_postdata();
    }
    
    public static function lifestory()
    {
    ?>
        <form>
        
        </form>
    <?php
    }
    
    public static function relation()
    {
    ?>
        <form>
        
        </form>
    <?php
    }
    
    public static function babybook()
    {
    ?>
        <form>
        
        </form>
    <?php
    }
    
    public static function childmanagement()
    {
    ?>
        <ul id="child_listings" class="container"> <!-- nothing-cool -->
            <?php
                $childs_query = get_custom_posts(array(
                    'post_type'         => Children::$post_type,
                    'posts_per_page'    => 10,
                    'post_status'       => 'publish',
                    'author'            => user_info('ID')
                ), 'chmng_dp');
                
                if ($childs_query !== 0) {
                    foreach ($childs_query as $child) :
            ?>
            <li class="child">
                <div class="child_thumb fleft clearfix">
                    <img src="<?php echo $child->thumbnail; ?>" alt="">
                </div>
                
                <div class="fleft">
                    <h3><?php echo $child->title; ?></h3>
                    <p><?php echo $child->age; ?></p>
                    <p><?php echo $child->permalink; ?></p>
                    <br clear="all">
                    <button class="classic-btn ajaxify" data-action='{"action" : "go_to_story", "id" : <?php echo $child->ID; ?>}'>Go to story</button>
                </div>
                <button class="downtown classic-btn ajaxify" data-action='{"action" : "edit_info", "id" : <?php echo $child->ID; ?>}'>Edit Information</button>
            </li>
            <?php
                    endforeach;
                } else { ?>
            <li class="child"><h3>No child is created yet! Click the above add child button.</h3></li>
            <?php } ?>
        </ul>
    <?php
    }

    public static function userinfo()
    {
    ?>
        <section id="form-top-puts" class="clearfix">
            <section class="tab_action">
                <div class="tabs">
                    <input type="button" class="submit-button no-bordrad active" value="Edit Info"/>
                    <input type="button" class="submit-button no-bordrad" value="Subscription"/>
                    <input type="button" class="submit-button no-bordrad" value="Message"/>
                </div>
            </section>
            <section class="middle_hash_content">
                <section class="tab_content enable">
                    <form method="post" name="update_user">
                        <input type="hidden" name="rw_nonce" value="<?php echo wp_create_nonce('update_user'); ?>">
                        <div class="user_image">
                            <div class="" id="preview-image" class="clearfix">
                            <?php
                                $chmng_dp = get_cupp_meta(user_info('ID'), '');
                                if (!$chmng_dp) {
                                    $chmng_dp = get_bloginfo('template_url') . '/images/avatar.png';
                                }
                            ?>
                                <span class="remove disable"></span>
                                <img src="<?php echo $chmng_dp; ?>"/>
                            </div>
                            <input type="file" name="user_profile_pic" class="disable" id="user_profile_pic">
                            <label for="user_profile_pic" class="submit-button clearfix">Add Picture</label>
                        </div>
                        <div class="user_info_form">
                            <div class="input-box-wrap">
                                <div class="form-control">
                                    <label for="fname">First Name:</label>
                                </div>
                                <div class="form-control form-input">
                                    <input type="text" name="fname" id="fname" class="child_inputs" placeholder="Firstname" value="<?php echo user_info('first_name'); ?>" /></p>
                                </div>
                            </div>
                            <div class="input-box-wrap">
                                <div class="form-control">
                                    <label for="lname">Last Name:</label>
                                </div>
                                <div class="form-control form-input">
                                    <input type="text" name="lname" id="lname" class="child_inputs" placeholder="Lastname" value="<?php echo user_info('last_name'); ?>" /></p>
                                </div>
                            </div>
                            <div class="input-box-wrap">
                                <div class="form-control">
                                    <label for="email">Email:</label>
                                </div>
                                <div class="form-control form-input">
                                    <input type="text" name="email" id="email" class="child_inputs" placeholder="xyz@gmail.com" value="<?php echo user_info('email'); ?>" /></p>
                                </div>
                            </div>
                            <div class="input-box-wrap">
                                <div class="offset2">
                                   <strong>Change your password:</strong><br>
                                   <small>Note: Changing your password will force you to signin again.</small>
                                </div>
                            </div>
                            <div class="input-box-wrap">
                                <div class="form-control">
                                    <label for="pass">Password:</label>
                                </div>
                                <div class="form-control form-input">
                                    <input type="password" name="pass" id="pass" class="child_inputs"/>
                                </div>   
                            </div>
                            <div class="input-box-wrap">
                                <div class="form-control">
                                    <label for="repass">Retype Password:</label>
                                </div>
                                <div class="form-control form-input">
                                    <input type="password" name="repass" id="repass" class="child_inputs"/>
                                </div>   
                            </div>   
                        </div>
                        <section id="form_action_buttons" class="clearfix users_update_sec">
                            <div class="fright">
                                <input type="button" name="cancel" class="submit-button cancel-btn ajaxify" value="Cancel" />
                                <input type="submit" name="save_changes" class="submit-button" value="Save Changes" />
                            </div>
                        </section>
                    </form>
                </section>
                <section class="tab_content">
                    <section class="tab_action">
                        <div class="tabs">
                            <input type="button" value="Subscription" class="submit-button small no-bordrad active">
                            <input type="button" value="Payments" class="submit-button small no-bordrad">
                        </div>
                    </section>
                    <section class="middle_hash_content">
                        <section class="tab_content enable">
                            <?php
                                global $rcp_options;
                                $user_ID = user_info('ID');
                                /*
                                if( rcp_is_recurring( $user_ID ) && ! rcp_is_expired( $user_ID ) ) {
                                    $date_text = __( 'Renewal Date', 'rw' );
                                } else {
                                    $date_text = __( 'Expiration Date', 'rw' );
                                }

                                $details = '<ul id="rcp_subscription_details">';
                                    $details .= '<li><span class="rcp_subscription_name">' . __( 'Subscription Level', 'rw' ) . '</span><span class="rcp_sub_details_separator">:&nbsp;</span><span class="rcp_sub_details_current_level">' . rcp_get_subscription( $user_ID ) . '</span></li>';
                                    if( rcp_get_expiration_date( $user_ID ) ) {
                                        $details .= '<li><span class="rcp_sub_details_exp">' . $date_text . '</span><span class="rcp_sub_details_separator">:&nbsp;</span><span class="rcp_sub_details_exp_date">' . rcp_get_expiration_date( $user_ID ) . '</span></li>';
                                    }
                                    $details .= '<li><span class="rcp_sub_details_recurring">' . __( 'Recurring', 'rw' ) . '</span><span class="rcp_sub_details_separator">:&nbsp;</span><span class="rcp_sub_is_recurring">';
                                    $details .= rcp_is_recurring( $user_ID ) ? __( 'yes', 'rw' ) : __( 'no', 'rw' ) . '</span></li>';
                                    $details .= '<li><span class="rcp_sub_details_status">' . __( 'Current Status', 'rw' ) . '</span><span class="rcp_sub_details_separator">:&nbsp;</span><span class="rcp_sub_details_current_status">' . rcp_print_status( $user_ID ) . '</span></li>';
                                    if( ( rcp_is_expired( $user_ID ) || rcp_get_status( $user_ID ) == 'cancelled' ) && rcp_subscription_upgrade_possible( $user_ID ) ) {
                                        $details .= '<li><a href="' . esc_url( get_permalink( $rcp_options['registration_page'] ) ) . '" title="' . __( 'Renew your subscription', 'rw' ) . '" class="rcp_sub_details_renew">' . __( 'Renew your subscription', 'rw' ) . '</a></li>';
                                    } elseif( !rcp_is_active( $user_ID ) && rcp_subscription_upgrade_possible( $user_ID ) ) {
                                        $details .= '<li><a href="' . esc_url( get_permalink( $rcp_options['registration_page'] ) ) . '" title="' . __( 'Upgrade your subscription', 'rw' ) . '" class="rcp_sub_details_renew">' . __( 'Upgrade your subscription', 'rw' ) . '</a></li>';
                                    } elseif( rcp_is_active( $user_ID ) && get_user_meta( $user_ID, 'rcp_paypal_subscriber', true) ) {
                                        $details .= '<li class="rcp_cancel"><a href="https://www.paypal.com/cgi-bin/customerprofileweb?cmd=_manage-paylist" target="_blank" title="' . __( 'Cancel your subscription', 'rw' ) . '">' . __( 'Cancel your subscription', 'rw' ) . '</a></li>';
                                    }
                                    $details = apply_filters( 'rcp_subscription_details_list', $details );
                                $details .= '</ul>';
                                $details .= '<div class="rcp-payment-history">';
                                    $details .= '<h3 class="payment_history_header">' . __( 'Your Payment History', 'rw' ) . '</h3>';
                                    $details .= rcp_print_user_payments( $user_ID );
                                $details .= '</div>';
                                echo $details;
                                 */
                            ?>
                            <table>
                                <tr>
                                    <th><?php echo __('Level', 'rw'); ?></th>
                                    <th><?php echo __('Status', 'rw'); ?></th>
                                    <th><?php echo __('Created On', 'rw'); ?></th>
                                    <th><?php echo __('Expires On', 'rw'); ?></th>
                                    <th><?php echo __('Recurring', 'rw'); ?></th>
                                    <th><?php echo __('Actions', 'rw'); ?></th>
                                </tr>
                                <tr>
                                    <td><?php echo rcp_get_subscription( $user_ID ); ?></td>
                                    <td><?php echo rcp_print_status( $user_ID ); ?></td>
                                    <td><?php echo date_i18n( get_option('date_format'), strtotime(user_info('created_on') ) ); ?></td>
                                    <td><?php echo (rcp_get_expiration_date( $user_ID )) ? rcp_get_expiration_date( $user_ID ) : 'None'; ?></td>
                                    <td><?php echo rcp_is_recurring( $user_ID ) ? __( 'Yes', 'rw' ) : __( 'No', 'rw' ); ?></td>
                                    <?php
                                        if( ( rcp_is_expired( $user_ID ) || rcp_get_status( $user_ID ) == 'cancelled' ) && rcp_subscription_upgrade_possible( $user_ID ) ) {
                                            $action = '<a target="_blank" href="' . esc_url( get_permalink( $rcp_options['registration_page'] ) ) . '" title="' . __( 'Renew your subscription', 'rw' ) . '" class="rcp_sub_details_renew">' . __( 'Renew your subscription', 'rw' ) . '</a>';
                                        } elseif( !rcp_is_active( $user_ID ) && rcp_subscription_upgrade_possible( $user_ID ) ) {
                                            $action = '<a target="_blank" href="' . esc_url( get_permalink( $rcp_options['registration_page'] ) ) . '" title="' . __( 'Upgrade your subscription', 'rw' ) . '" class="rcp_sub_details_renew">' . __( 'Upgrade your subscription', 'rw' ) . '</a>';
                                        } elseif( rcp_is_active( $user_ID ) && get_user_meta( $user_ID, 'rcp_paypal_subscriber', true) ) {
                                            $action = '<a target="_blank" href="https://www.paypal.com/cgi-bin/customerprofileweb?cmd=_manage-paylist" target="_blank" title="' . __( 'Cancel your subscription', 'rw' ) . '">' . __( 'Cancel your subscription', 'rw' ) . '</a>';
                                        }
                                    ?>
                                    <td><?php echo $action; ?></td>
                                </tr>
                            </table>

                        </section>
                        <section class="tab_content">
                            <table>
                                <tr>
                                    <th><?php echo __('Subscription', 'rw'); ?></th>
                                    <th><?php echo __('Payment Type', 'rw'); ?></th>
                                    <th><?php echo __('Subscription Key', 'rw'); ?></th>
                                    <th><?php echo __('Amount', 'rw'); ?></th>
                                    <th><?php echo __('Date', 'rw'); ?></th>
                                </tr>
                                <?php
                                    $payments = new \RCP_Payments();
                                    $user_payments = $payments->get_payments( array( 'user_id' => $user_ID ) );
                                    $payments_list = '';
                                    if( $user_payments ) :
                                            foreach( $user_payments as $payment ) :
                                ?>
                                <tr>
                                    <td><?php echo $payment->subscription; ?></td>
                                    <td><?php echo $payment->payment_type; ?></td>
                                    <td><?php echo $payment->subscription_key; ?></td>
                                    <td><?php echo rcp_currency_filter( $payment->amount ); ?></td>
                                    <td><?php echo date_i18n( get_option('date_format'), strtotime($payment->date) ); ?></td>
                                </tr>
                                <?php
                                        endforeach;
                                    else :
                                        echo '<tr><td colspan="5">No payments recorded</td></tr>';
                                    endif;
                                ?>

                            </table>
                        </section>
                    </section>
                </section>
                <section class="tab_content">

                </section>
            </section>
        </section>

       <!--<section id="form-top-puts" class="clearfix blog">
                <section class="tab_action">
                    <div class="tabs">
                        <input type="button" class="submit-button no-bordrad" value="Gallery"/>
                        <input type="button" class="submit-button no-bordrad" value="Blog"/>
                        <input type="button" class="submit-button no-bordrad" value="Development"/>
                        <input type="button" class="submit-button no-bordrad" value="Journal"/>
                    </div>
                </section>
                <section  id="user_blog">
                    <div class="blog_user">
                        <h1>Blogs</h1>
                        <div class="bl_user"><input type="button" class="blog_btn" value="Add Post"/></div>
                        <span class="clearfix"></span>
                    </div>  
                    <article class="blog_post">
                        <div class="date_box">
                            <span class="date">25</span><span>December</span><span>2013</span>
                        </div>
                        <div class="blog_details">
                            <h1>My Child New Teeth!!</h1>
                            <span>0 comments</span>
                            <p>Here you can add movie from youTube so you have the opportunity to collect...</p>
                        </div>
                        <div class="bl_user"><input type="button" class="blog_btn" value="Read Blog"/></div>
                        <span class="clearfix"></span>
                    </article>  
                    <article class="blog_post">
                        <div class="date_box">
                            <span class="date">25</span><span>December</span><span>2013</span>
                        </div>
                        <div class="blog_details">
                            <h1>My Child New Teeth!!</h1>
                            <span>0 comments</span>
                            <p>Here you can add movie from youTube so you have the opportunity to collect...</p>
                        </div>
                        <div class="bl_user"><input type="button" class="blog_btn" value="Read Blog"/></div>
                        <span class="clearfix"></span>
                    </article>               
                </section>
        </section>-->

        <!--<section id="form-top-puts" class="clearfix">
                <section class="tab_action">
                    <div class="tabs">
                        <input type="button" class="submit-button no-bordrad" value="Gallery"/>
                        <input type="button" class="submit-button no-bordrad" value="Blog"/>
                        <input type="button" class="submit-button no-bordrad" value="Development"/>
                        <input type="button" class="submit-button no-bordrad" value="Journal"/>
                    </div>
                </section>
                <section  id="user_blog">
                    <div class="blog_user">
                        <h1>Blogs</h1>
                        <div class="bl_user"><input type="button" class="blog_btn" value="Add Post"/></div>
                        <span class="clearfix"></span>
                    </div>  
                    <article class="blog_post">
                        <div class="date_box">
                            <span class="date">25</span><span>December</span><span>2013</span>
                        </div>
                        <div class="blog_details">
                            <h1>My Child New Teeth!!</h1>
                            <span>0 comments</span>
                            <p>Here you can add movie from youTube so you have the opportunity to collect...</p>
                        </div>
                        <div class="bl_user"><input type="button" class="blog_btn" value="Read Blog"/></div>
                        <span class="clearfix"></span>
                    </article>  
                    <article class="blog_post">
                        <div class="date_box">
                            <span class="date">25</span><span>December</span><span>2013</span>
                        </div>
                        <div class="blog_details">
                            <h1>My Child New Teeth!!</h1>
                            <span>0 comments</span>
                            <p>Here you can add movie from youTube so you have the opportunity to collect...</p>
                        </div>
                        <div class="bl_user"><input type="button" class="blog_btn" value="Read Blog"/></div>
                        <span class="clearfix"></span>
                    </article>               
                </section>
        </section>-->
        <?php    
    }
}