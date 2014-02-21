<?php

namespace RW\Admin;

use RW\Modules\ParentModule;
use RW\Modules\Child;

class LifeStoryMenu
{
    /**
     * @var array
     */
    public $tabs_menu = array (
        'gallery' => 'Gallery',
        'blog' => 'Blog',
        'development' => 'Physical Development',
        'journal' => 'Journal',
        'follow' => 'Follow'
    );
    
    public function __construct()
    {
        add_action('admin_menu', array($this, 'lifestory_menu'));
    }
    
    public function __call($name, $arguments)
    {
        echo 'Empty';
    }

    /**
     * Menu hook
     */
    public function lifestory_menu()
    {
        add_menu_page('Life Story', 'Life Story', (!ParentModule::currentUserisParent()) ? 'administrator' : 'parent', "lifestory-conf", array($this, 'lifestory_setup'), ADMIN_URI . '/images/life_story.png');
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Register Settings, optional for now
     */
    public function register_settings()
    {
        register_setting('lifestory-settings-group', 'lifestory');
    }
    
    /**
     * Setup life story page
     */
    public function lifestory_setup()
    {
        ?>
	<div class="wrap">
            <div id="icon-themes" class="icon32"></div>
            <h2>Life Story</h2>
            <?php
                settings_errors();

                $actab = \array_keys($this->tabs_menu);

                $active_tab = (\array_key_exists('tab', $_GET)) ? \rw_get('tab') : $actab[0];

                $cid = (rw_get('cid')) ? '&cid=' . rw_get('cid') : '';

                if (!\array_key_exists('cid', $_GET)) {
                    wp_die('Child is not selected.');
                } else {
                    if (!Child::exists(rw_get('cid'))) wp_die('Child is not selected.');
                }
            ?>

            <h2 class="nav-tab-wrapper">
                <?php foreach ($this->tabs_menu as $tab_key => $tab) : ?>
                    <a href="?page=lifestory-conf&tab=<?php echo $tab_key . $cid; ?>" class="nav-tab<?php echo ($active_tab === $tab_key) ? ' nav-tab-active' : '' ?>"><?php echo $tab; ?></a>
                <?php endforeach; ?>
            </h2>

            <div class="wrap">
                <?php
                    foreach ($this->tabs_menu as $tab_key => $tab) :
                        if ($tab_key === $active_tab && \is_callable(array($this, $tab_key))) :
                            \call_user_func(array($this, $tab_key));
                        endif;
                    endforeach;
                ?>
            </div>
        </div>
        <?php
    }

    /**
     * Gallery tab
     */
    public function gallery()
    {
    ?>
        <section id="life-images">
            <h1>Images</h1>
            
            <nav>
                
            </nav>
        </section>

        <section id="life-videos">
            <h1>Videos</h1>
            
            <nav>
                
            </nav>
        </section>
    <?php
    }

}
