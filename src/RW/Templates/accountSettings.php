<section id="form-top-puts" class="clearfix">
    <section class="tab_action">
        <div class="tabs">
            <input type="button" class="submit-button no-bordrad active" value="Edit Info"/>
            <input type="button" class="submit-button no-bordrad" value="Subscription"/>
            <input type="button" class="submit-button no-bordrad" value="Message"/>
        </div>
        <?php echo do_shortcode('[fib appid="191514454376991" text="Invite Facebook friends" align="right"]'); ?>
    </section>
    <section class="middle_hash_content clearfix">
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
                        <span class="remove-icon remove disable"></span>
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
                        <input type="button" name="cancel" class="submit-button cancel-btn" value="Cancel" />
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