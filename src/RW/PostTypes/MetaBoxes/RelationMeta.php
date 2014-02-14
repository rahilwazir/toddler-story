<?php
namespace RW\PostTypes\MetaBoxes;

use RW\PostTypes\Children;

if (!defined('ABSPATH')) exit;

class RelationMeta extends Children
{
    public static $field_values = array(
        'Dad',
        'Mom',
        'Family',
        'Friend'
    );
    
    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'toddler_shows_metas'));

        add_action('save_post', array($this, 'toddler_relation_metas_update'));
    }

    public function toddler_shows_metas()
    {
        $post_types = array(self::$post_type);
        foreach ($post_types as $post_type) {
            add_meta_box('toddler_relation_child', __('Relation with child'), array($this, 'toddler_relation_meta_setup'), $post_type, 'side', 'high');
        }
    }

    public function toddler_relation_meta_setup($post)
    {
        $toddler_relation = get_post_meta($post->ID, '_toddler_relation', true);

        ?>
        <p class="relation-meta">
            <select name="toddler_relation">
                <?php foreach (self::$field_values as $val) : ?>
                <option value="<?php echo $val; ?>" <?php selected($toddler_relation, $val) ?>><?php echo $val; ?></option>
                <?php endforeach; ?>
            </select>
        </p>
    <?php
    }

    public function toddler_relation_metas_update($post_id)
    {
        if (isset($_POST['toddler_relation'])) {
            update_post_meta($post_id, '_toddler_relation', strip_tags($_POST['toddler_relation']));
        }
    }

}