<?php

use RW\Modules\ParentModule;
use RW\PostTypes\Children;
use RW\Admin\Settings;
use RW\PostTypes\Background;
use RW\PostTypes\MetaBoxes\BlockAccessContent;
use RW\Modules\CreateChild;
use RW\Modules\HashTagLoader;
use RW\Shortcodes\ShortcodePack;
use RW\PostTypes\ChildBlog;

$rw_settings = new Settings();

new ShortcodePack();
new Children();
new Background();
new BlockAccessContent();
new ChildBlog();

if (ParentModule::currentUserisParent()) {
    /* On admin screen */
    if (is_admin()) {
        $rw_settings->preventAccessToDefaults();
    } else {
        
    }
   
    /**
     * Handling Ajax request (Main route)
     */
    if ( is_request_ajax() ) {

        if (wp_verify_nonce($_REQUEST['token'], user_info('ID') . '_' . user_info('login'))) {
            switch (filter_input(0, 'action')) {
                case 'add_children':
                    CreateChild::insertPosts('add_children');
                    break;

                case 'hash_load':
                    HashTagLoader::callHashContent('hash_load');
                    break;

                case 'update_user_parent':
                    ParentModule::update('update_user_parent');
                    break;

                case 'au_child_blog':
                    ChildBlog::update('au_child_blog');
                    break;

                default:
                    break;
            }
        }

        exit; // preventing users from viewing admin dashboard
    }
}