<?php
namespace RW\PostTypes\MetaBoxes;

use RW\PostTypes\Children;
use RW\PostTypes\ChildBlog;

class ListParentChilds extends Children
{

    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'toddler_shows_metas'));

        add_action('save_post', array($this, 'toddler_parent_child_metas_update'));
    }

    public function toddler_shows_metas()
    {
        $post_types = array(ChildBlog::$post_type);
        foreach ($post_types as $post_type) {
            add_meta_box('toddler_parent_childs', __('Select Parent Child'), array($this, 'toddler_parent_child_meta_setup'), $post_type, 'side', 'high');
        }
    }

    public function toddler_parent_child_meta_setup($post)
    {
        $parent_child_user_id = get_post_meta($post->ID, '_toddler_parent_child_user', true);
        
        $output = '';

        $blogusers = get_users('role=parent');

        ?>
        <div class="bg-meta">
            <input type="hidden" name="toddler_parent_child_user_nonce" value="<?php echo wp_create_nonce('toddler_parent_child_user_nonce'); ?>">
            <?php
                $output .= '<select name="toddler_parent_child_user">';

                if ( $blogusers ) {
                    foreach ($blogusers as $user) :
                            $output .= '<optgroup label="' . $user->display_name . '">';

                            $childBlogPosts = \get_custom_posts(array(
                                'post_type'         => Children::$post_type,
                                'posts_per_page'    => -1,
                                'author'            => $user->ID
                            ));

                            if ( $childBlogPosts ) {    
                                foreach ($childBlogPosts as $_post) :
                                    $output .= '<option value="' . $_post->ID . '"' . selected($parent_child_user_id, $_post->ID, false).'>' . $_post->title . '</option>';
                                endforeach;
                            }
                        $output .= '</optgroup>';
                    endforeach;
                }

                $output .= '</select>';
                echo $output;
            ?>
            <p class="description">Select the parent child to assign this blog post.</p>
        </div>
    <?php
    }

    public function toddler_parent_child_metas_update($post_id)
    {
        if (isset($_POST['toddler_parent_child_user_nonce']) && wp_verify_nonce($_POST['toddler_parent_child_user_nonce'], 'toddler_parent_child_user_nonce')) {
            $child_id = sanitize_text_field($_POST['toddler_parent_child_user']);

            if ($child_id > 1) {
                update_post_meta($post_id, '_toddler_parent_child_user', $child_id);
                
                remove_action('save_post', array($this, __FUNCTION__));

                $child_author = get_custom_posts(array(
                    'p' => $child_id,
                    'post_type' => Children::$post_type
                ));

                wp_update_post(array(
                    'ID' => $post_id,
                    'post_author' => $child_author[0]->authorID
                ));

                add_action('save_post', array($this, __FUNCTION__));
            }
        }
    }

}