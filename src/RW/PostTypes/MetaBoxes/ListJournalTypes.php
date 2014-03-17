<?php
namespace RW\PostTypes\MetaBoxes;

use RW\PostTypes\ChildJournal;

class ListJournalTypes
{

    public static $journalTypes = array('Doctor', 'Healthcare', 'Disease', 'Other');

    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'toddler_shows_metas'));
        add_action('save_post', array($this, 'toddler_parent_child_metas_update'));
    }

    public function toddler_shows_metas()
    {
        $post_types = array(ChildJournal::$post_type);
        foreach ($post_types as $post_type) {
            add_meta_box('toddler_childs_journal_types', __('Type'), array($this, 'toddler_parent_child_meta_setup'), $post_type, 'side', 'high');
        }
    }

    public function toddler_parent_child_meta_setup($post)
    {
        $_type = get_post_meta($post->ID, '_toddler_child_journal_type', true);
        $output = '';
        ?>
        <div class="bg-meta">
            <input type="hidden" name="toddler_child_journal_type_nonce" value="<?php echo wp_create_nonce('toddler_child_journal_type_nonce'); ?>">
            <?php
            if ( self::$journalTypes ) {
                $output .= '<select name="toddler_child_journal_type">';
                foreach (self::$journalTypes as $type) :
                    $output .= '<option value="' . $type . '"' . selected($_type, $type, false).'>' . $type . '</option>';
                endforeach;
                $output .= '</select>';
                echo $output;
            }
            ?>
        </div>
    <?php
    }

    public function toddler_parent_child_metas_update($post_id)
    {
        if (isset($_POST['toddler_child_journal_type']) && wp_verify_nonce($_POST['toddler_child_journal_type_nonce'], 'toddler_child_journal_type_nonce')) {
            $journal_type = sanitize_text_field($_POST['toddler_child_journal_type']);

            if ($journal_type) {
                update_post_meta($post_id, '_toddler_child_journal_type', $journal_type);
            }
        }
    }

}