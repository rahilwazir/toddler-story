<?php
namespace RW\PostTypes\MetaBoxes;

use RW\PostTypes\Children;
use RW\PostTypes\Background;
use RW\Modules\ParentModule;

if (!defined('ABSPATH')) exit;

class BackgroundChooser extends Children
{

    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'toddler_shows_metas'));

        add_action('save_post', array($this, 'toddler_bg_metas_update'));
    }

    public function toddler_shows_metas()
    {
        $post_types = array(self::$post_type);
        foreach ($post_types as $post_type) {
            add_meta_box('toddler_bg', __('Background Chooser'), array($this, 'toddler_bg_meta_setup'), $post_type, 'advanced');
        }
    }
    
    /**
     * Check and retrieve if there any background chooser posts
     * @param string $thumb The thumbnail size of the image to preview
     * @return array|int return array on true, otherwise 0 on false
     */
    public function has_post($thumb = 'small')
    {
        return get_custom_posts(array(
            'post_type'         => Background::$post_type,
            'showposts'         => -1,
            'author__not_in'    => (ParentModule::currentUserisParent()) ? array(user_info('ID')) : '',
            'post_status'       => 'publish'
        ), $thumb);
    }

    public function toddler_bg_meta_setup($post)
    {
        $toddler_bg = get_post_meta($post->ID, '_toddler_bg', true);
        
        $contents = $this->has_post();
        ?>
        <p class="bg-meta">
            <input class="bg-selector" type="radio" name="toddler_bg" value="none" <?php checked($toddler_bg, 'none'); ?> id="bg-none" />
            <label for="bg-none">
                <span class="img-wrap<?php echo ($toddler_bg === 'none') ? ' active-bg' : ''; ?>" id="bg-none-display"></span>
            </label>
            <?php
                if ($contents !== 0) {
                    foreach ($contents as $content) : ?>
                    <input class="bg-selector" type="radio" name="toddler_bg"
                        value="<?php echo $content->ID; ?>" <?php checked($toddler_bg, $content->ID); ?>
                        id="bg-<?php echo $content->ID; ?>" />
                    <label for="bg-<?php echo $content->ID; ?>">
                        <span class="img-wrap<?php echo ($content->ID == $toddler_bg) ? ' active-bg' : ''; ?>">
                            <img src="<?php echo $content->thumbnail; ?>" alt="">
                        </span>
                    </label>
            <?php
                    endforeach;
                } else {
                    echo 'No image uploaded';
                }
            ?>
        </p>
    <?php
    }

    public function toddler_bg_metas_update($post_id)
    {
        if (isset($_POST['toddler_bg'])) {
            update_post_meta($post_id, '_toddler_bg', strip_tags($_POST['toddler_bg']));
        }
    }

}