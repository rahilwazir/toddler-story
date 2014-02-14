<?php
namespace RW\PostTypes\MetaBoxes;

use RW\PostTypes\Children;

class DateOfBirthMeta extends Children
{

    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'toddler_shows_metas'));

        add_action('save_post', array($this, 'toddler_dob_metas_update'));
    }

    public function toddler_shows_metas()
    {
        $post_types = array(self::$post_type);
        foreach ($post_types as $post_type) {
            add_meta_box('toddler_dob', __('Date of birth'), array($this, 'toddler_dob_meta_setup'), $post_type, 'side', 'high');
        }
    }

    public function toddler_dob_meta_setup($post)
    {
        $dob_child = get_post_meta($post->ID, '_dob_child', true);

        ?>
        <p class="dob-meta">
            <input type="text" id="dob_child" name="dob_child" value="<?php echo esc_attr($dob_child); ?>">
            <label for="dob_child"><img src="<?php echo ADMIN_URI; ?>/images/calendar.png" alt="Calendar"></label>
        </p>
    <?php
    }

    public function toddler_dob_metas_update($post_id)
    {
        if (isset($_POST['dob_child'])) {
            update_post_meta($post_id, '_dob_child', strip_tags($_POST['dob_child']));
        }
    }
}