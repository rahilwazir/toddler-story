<?php
namespace RW\PostTypes\MetaBoxes;

use RW\PostTypes\Children;

if (!defined('ABSPATH')) exit;

class GenderMeta extends Children
{
    public static $field_values = array (
        'Boy',
        'Girl',
        'Unknown'
    );
    
    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'toddler_shows_metas'));

        add_action('save_post', array($this, 'toddler_sex_metas_update'));
    }

    public function toddler_shows_metas()
    {
        $post_types = array(self::$post_type);
        foreach ($post_types as $post_type) {
            add_meta_box('toddler_sex', __('Sex of child'), array($this, 'toddler_sex_meta_setup'), $post_type, 'side', 'high');
        }
    }

    public function toddler_sex_meta_setup($post)
    {
        $toddler_sex = get_post_meta($post->ID, '_toddler_sex', true);
        ?>
        <p class="gender-meta">
            <?php $count = 1; foreach (self::$field_values as $val) : ?>
            <input type="radio" name="toddler_sex" value="<?php echo $val; ?>" <?php checked($toddler_sex, $val); ?> id="radio<?php echo $count; ?>" />
            <label for="radio<?php echo $count; ?>"><span><?php echo $val; ?></span></label>
            <?php $count++; endforeach; ?>
        </p>
    <?php
    }

    public function toddler_sex_metas_update($post_id)
    {
        if (isset($_POST['toddler_sex'])) {
            update_post_meta($post_id, '_toddler_sex', sanitize_text_field($_POST['toddler_sex']));
        }
    }

}