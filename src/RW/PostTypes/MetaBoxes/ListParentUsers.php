<?php
namespace RW\PostTypes\MetaBoxes;

use RW\PostTypes\Children;

class ListParentUsers extends Children
{

    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'toddler_shows_metas'));

        add_action('save_post', array($this, 'toddler_user_metas_update'));
    }

    public function toddler_shows_metas()
    {
        $post_types = array(self::$post_type);
        foreach ($post_types as $post_type) {
            add_meta_box('toddler_users', __('Select Parent User'), array($this, 'toddler_user_meta_setup'), $post_type, 'side', 'high');
        }
    }

    public function toddler_user_meta_setup($post)
    {
        $parent_user_id = get_post_meta($post->ID, '_toddler_parent_user', true);
        
        $output = '';
        $blogusers = get_users('role=parent');
        ?>
        <div class="bg-meta">
            <input type="hidden" name="toddler_parent_user_nonce" value="<?php echo wp_create_nonce('toddler_parent_user_nonce'); ?>">
            <?php
                $output .= '<select name="toddler_parent_user">';
                $output .= '<option>None</option>';

                if ($blogusers) {
                    foreach ($blogusers as $user) :
                        $output .= '<option value="'.$user->ID.'"'.  selected($parent_user_id, $user->ID, false).'>'.$user->display_name.'</option>';
                    endforeach;
                }

                $output .= '</select>';
                echo $output;
            ?>
            <p class="description">If <strong>None</strong> is selected, the child will be independent and not linked with any parent user.</p>
        </div>
    <?php
    }

    public function toddler_user_metas_update($post_id)
    {
        if (isset($_POST['toddler_parent_user_nonce']) && wp_verify_nonce($_POST['toddler_parent_user_nonce'], 'toddler_parent_user_nonce')) {
            $parent_id = sanitize_text_field($_POST['toddler_parent_user']);

            if ($parent_id < 1) {
                $parent_id = 1;
            }

            update_post_meta($post_id, '_toddler_parent_user', $parent_id);

            remove_action('save_post', array($this, __FUNCTION__));

            wp_update_post(array(
                'ID' => $post_id,
                'post_author' => $parent_id
            ));
            
            add_action('save_post', array($this, __FUNCTION__));
        }
    }

}