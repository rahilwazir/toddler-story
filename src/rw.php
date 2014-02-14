<?php

use RW\Modules\ParentModule;
use RW\PostTypes\Children;
use RW\Admin\Settings;
use RW\PostTypes\Background;
use RW\PostTypes\MetaBoxes\BlockAccessContent;
use RW\Modules\CreateChild;
use RW\Modules\HashTagLoader;

$rw_settings = new Settings();

new Children();
new Background();
new BlockAccessContent();

if (ParentModule::currentUserisParent()) {
    /* On admin screen */
    if (is_admin()) {
        $rw_settings->preventAccessToDefaults();
    } else {
        
    }
   
    /**
     * Doing Ajax request
     */
    if (is_request_ajax()) {
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
            default:
                break;
        }
    }
}