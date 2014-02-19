<footer>
    <div class="footer">

        <div class="nav_btm">
            <h1>Quick Links</h1>
            <?php wp_nav_menu(array('menu' => 'foot-menu')); ?>
        </div>
        <div class="nav_btm">
            <h1>What We Do</h1>
            <?php wp_nav_menu(array('menu' => 'foot-menu')); ?>
        </div>

        <div class="social_media">
            <h1>Connect With Us</h1>
            <?php echo do_shortcode('[cn-social-icon]'); ?>
        </div>

        <div class="address">
            <?php dynamic_sidebar('sidebar-2'); ?>
        </div>

    </div>
    <br clear="all" />
</footer>

<div id="value" class="popup">
    <div class="login clearfix">
        <div class="popping_wrapper">
            <p>It’s free to create a user and try to see if Toddlerstory is something you like. All you need to do is to press the Facebook signup button or enter your name and email and choose a password and you are done!</p>
            <div class="line_div"></div>
            <div class="signin_fb">
                <?php
                if (!get_option($opt_jfb_hide_button)) {
                    jfb_output_facebook_btn();
                }
                ?>
                <br />
                Not a member yet? <a href="<?php echo get_permalink(330); ?>" class="switch-modal">Register Now</a>
            </div>
            <section class="sec">
                <div class="hr_mid"><p>OR</p></div>
            </section>
            <div class="main_sign_up">
                <div class="svr_messages"></div>
                <div class="reg_preloader"><img src="<?php echo get_template_directory_uri(); ?>/images/preload.gif" alt=""></div>
                <form id="login_form" name="login_form" method="POST" class="clearfix">
                    <div class="form-control clearfix">
                        <label class="label" for="login_username">Username</label>
                        <input type="text" required id="login_username" name="login_username" />
                    </div>
                    <div class="form-control clearfix">
                        <label class="label" for="login_password">Password</label>
                        <input type="password" required id="login_password" placeholder="" name="login_password" />
                    </div>
                    <div class="forgetting">
                        <div class="fleft inline-childs">
                            <input type="checkbox" id="login_remember" value="true" name="login_remember"> <label for="login_remember">Remember me</label>
                        </div>

                        <div class="fright inline-childs">
                            <a class="forget" href="<?php echo get_permalink(86); ?>">Forget Password?</a>
                        </div>
                    </div>
                    <input type="hidden" value="<?php echo wp_create_nonce('user_login'); ?>" name="user_login_nonce" />
                    <br clear="all"/>
                    <div class="signin_reg"><input type="submit" value="Login" name="user_login" class="anc">
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<div id="value_R" class="popup">
    <div class="regist">
        <div class="popping_wrapper">
            <p>It’s free to create a user and try to see if Toddlerstory is something you like. All you need to do is to press the Facebook signup button or enter your name and email and choose a password and you are done!</p>            <div class="line_div"></div>
            <div class="signin_fb">
                <?php
                if (!get_option($opt_jfb_hide_button)) {
                    jfb_output_facebook_btn();
                }
                ?>
                <br />
                Already a member? <a href="<?php echo get_permalink(332); ?>" class="switch-modal">Sign in</a>
            </div>
            <section class="sec">
                <div class="hr_mid"><p>OR</p></div>
            </section>
            <div class="main_sign_up">
                <div class="svr_messages"></div>
                <div class="reg_preloader"><img src="<?php echo get_template_directory_uri(); ?>/images/preload.gif" alt=""></div>
                <form id="reg_form" name="reg_form" method="POST" class="clearfix">
                    <div class="form-control clearfix">
                        <label for="reg_firstname" class="label">First name</label>
                        <input type="text" placeholder="First Name" required id="reg_firstname" name="reg_firstname" />
                    </div>
                    <div class="form-control clearfix">
                        <label class="label" for="reg_lastname">Last name</label>
                        <input type="text" id="reg_lastname" required name="reg_lastname" placeholder="Last Name" />
                    </div>
                    <div class="form-control clearfix">
                        <label class="label" for="reg_username">Username</label>
                        <input type="text" id="reg_username" required name="reg_username" placeholder="Username" />
                    </div>
                    <div class="form-control clearfix">
                    <label class="label" for="reg_email">Email</label>
                    <input type="text" id="reg_email" required name="reg_email" placeholder="Email Address" />
                    </div>
                    <div class="form-control clearfix">
                        <label class="label" for="reg_pass">Password</label>
                        <input type="password" id="reg_pass" required name="reg_pass" class="small" placeholder="Enter password"/>
                    </div>
                    <div class="form-control clearfix">
                        <label class="label" for="reg_repass">Re-enter password</label>
                        <input type="password" id="reg_repass" required name="reg_repass" class="small" placeholder="Re-Enter password"/>
                    </div>
                    <div class="form-control clearfix">
                        <label class="label" for="reg_lang">Default language:</label>
                        <?php echo qtrans_language_dropdown(array(
                            'class' => 'child_inputs fright'
                        )); ?>
                    </div>
                    <br clear="all"/>
                    <div class="footer_para">
                        <div class="clearfix">
                            <input type="checkbox" name="tos_n_pp" id="tos_n_pp" required value="1">
                            <label for="tos_n_pp">By signing up, you agree to our <a href="#">Terms &amp; Conditions</a></label>
                        </div>
                        <div class="clearfix">
                            <input type="checkbox" name="subs_news" id="subs_news" checked="" value="1">
                            <label for="subs_news">Subscribe for newsletter</label>
                        </div>
                    </div>
                    <div class="signin_reg"><input type="submit" class="anc" value="Sign Up Now" name="sign_up"></div>
                    <input type="hidden" value="<?php echo wp_create_nonce('user_reg'); ?>" name="user_reg_nonce" id="user_reg_nonce">
                </form>
            </div>
            <br clear="all"/>
        </div>
    </div>
</div>	
<?php wp_footer(); ?>
</body>
</html>