<?php
namespace RW\PostTypes\MetaBoxes;

use RW\PostTypes\Children;

if (!defined('ABSPATH'))
    exit;

class NameOfChildMeta extends Children
{

    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'toddler_shows_metas'));

        add_action('save_post', array($this, 'toddler_noc_metas_update'));
    }

    public function toddler_shows_metas()
    {
        $post_types = array(self::$post_type);
        foreach ($post_types as $post_type) {
            add_meta_box('toddler_noc', __('Name of child'), array($this, 'toddler_noc_metas_setup'), $post_type, 'side', 'high');
        }
    }

    public function toddler_noc_metas_setup($post)
    {
        $child_first_name = get_post_meta($post->ID, '_child_first_name', true);
        $child_last_name = get_post_meta($post->ID, '_child_last_name', true);

        ?>
        <p class="dob-meta">
            <p>
                <label for="child_first_name">First Name:</label><br>
                <input type="text" id="child_first_name" name="child_first_name" placeholder="First Name" value="<?php echo esc_attr($child_first_name); ?>">
            </p>
            
            <p>
                <label for="child_last_name">Last Name:</label><br>
                <input type="text" id="child_last_name" name="child_last_name" placeholder="Last Name" value="<?php echo esc_attr($child_last_name); ?>">
            </p>
        </p>
    <?php
    }

    public function toddler_noc_metas_update($post_id)
    {
        if (isset($_POST['child_first_name'])) {
            update_post_meta($post_id, '_child_first_name', sanitize_text_field($_POST['child_first_name']));
        }
        
        if (isset($_POST['child_last_name'])) {
            update_post_meta($post_id, '_child_last_name', sanitize_text_field($_POST['child_last_name']));
        }
    }

}