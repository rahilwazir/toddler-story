<?php

namespace RW\PostTypes\MetaBoxes;

use RW\PostTypes\Children;

class BlockAccessContent extends Children
{

    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'toddler_shows_metas'));

        add_action('save_post', array($this, 'toddler_bca_metas_update'));
    }

    public function toddler_shows_metas()
    {
        $post_types = array('post', 'page');
        foreach ($post_types as $post_type) {
            add_meta_box('toddler_bca', __('Block this page'), array($this, 'toddler_bca_meta_setup'), $post_type, 'side', 'high');
        }
    }

    public function toddler_bca_meta_setup($post)
    {
        $toddler_bca = get_post_meta($post->ID, '_toddler_bca', true);
        ?>
        <p>Block this page from non registered users</p>
        <p class="description">If this page is within parent admin template than you don't need to check this option. <a href="#page_template" rel="rw_sscroll">Click here to find out.</a></p>
        <p class="dob-meta">
            <input type="checkbox" value="1" id="toddler_bca_c" name="toddler_bca_c" <?php checked($toddler_bca, '1') ?>>
            <label for="toddler_bca_c"><span>Block this page</span></label>
        </p>
    <?php
    }

    public function toddler_bca_metas_update($post_id)
    {
        if (isset($_POST['toddler_bca_c'])) {
            update_post_meta($post_id, '_toddler_bca', sanitize_text_field($_POST['toddler_bca_c']));
        } else {
            update_post_meta($post_id, '_toddler_bca', 0);
        }
    }

}