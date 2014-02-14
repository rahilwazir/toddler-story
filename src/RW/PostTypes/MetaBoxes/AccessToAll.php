<?php

namespace RW\PostTypes\MetaBoxes;

use RW\PostTypes\Children;

class AccessToAll extends Children
{

    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'toddler_shows_metas'));

        add_action('save_post', array($this, 'toddler_ata_metas_update'));
    }

    public function toddler_shows_metas()
    {
        $post_types = array(self::$post_type);
        foreach ($post_types as $post_type) {
            add_meta_box('toddler_ata_c', __('Access Right of Life Story'), array($this, 'toddler_ata_meta_setup'), $post_type);
        }
    }

    public function toddler_ata_meta_setup($post)
    {
        $toddler_ata = get_post_meta($post->ID, '_toddler_ata', true);

        #echo $toddler_ata;

        ?>
        <p class="dob-meta">
            <input type="checkbox" value="1" id="toddler_ata" name="toddler_ata" <?php checked('1', $toddler_ata); ?>>
            <input type="hidden" value="<?php echo wp_create_nonce('toddler_ata'); ?>" name="toddler_ata_nonce">
            <label for="toddler_ata"><span>Access to all</span></label>
        </p>
    <?php
    }

    public function toddler_ata_metas_update($post_id)
    {
        if (isset($_POST['toddler_ata_nonce']) && wp_verify_nonce($_POST['toddler_ata_nonce'], 'toddler_ata')) {
            update_post_meta($post_id, '_toddler_ata', sanitize_text_field($_POST['toddler_ata']));
        }
    }

}