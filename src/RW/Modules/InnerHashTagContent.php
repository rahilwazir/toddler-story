<?php

namespace RW\Modules;

use RW\Modules\HashTagContent;
use RW\Modules\Child;
use RW\PostTypes\LifeStoryMenu;
use RW\PostTypes\Children;

class InnerHashTagContent extends HashTagContent
{

    public static function goToStory(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['action']) {
            if ( Child::exists($data['id']) ) {

                Child::getCurrent( $data['id'] );
                
                createHiddenTitle( Child::fullName(), 'Gallery' );

                $tab_menu = Children::$lifeStoryMenu; $i = 0;
                ?>
                <section id="form-top-puts" class="clearfix blog">
                    
                    <section class="tab_action">
                        <div class="tabs">
                            <?php foreach ($tab_menu->tabs_menu as $key => $val) : ?>
                                <input type="button" class="submit-button no-bordrad<?php echo ($i===0) ? ' active' : ''; ?>" value="<?php echo $val; ?>"/>
                            <?php $i++; endforeach; ?>
                        </div>
                    </section>
                    
                    <section class="life-story-section middle_hash_content">
                        <section id="gallery" class="tab_content enable">
                            
                        </section>
                        <section id="blog" class="tab_content">
                            <div class="blog_user">
                                <h1>Blogs</h1>
                                <div class="bl_user"><input type="button" class="blog_btn" value="Add Post"/></div>
                                <span class="clearfix"></span>
                            
                                <?php
                                    $childBlogPosts = Child::blogPosts( $data['id'] );

                                    if ( $childBlogPosts ) {
                                        foreach ($childBlogPosts as $_post) {
                                ?>
                                <article class="blog_post">
                                    <div class="date_box">
                                        <span class="date"><?php echo $_post->day ?></span>
                                        <span><?php echo $_post->month ?></span>
                                        <span><?php echo $_post->year ?></span>
                                    </div>
                                    <div class="blog_details">
                                        <h1><?php echo $_post->title; ?></h1>
                                        <span><?php echo $_post->total_comments; ?> comments</span>
                                        <p><?php echo trimIt($_post->content); ?></p>
                                    </div>
                                    <div class="bl_user"><input type="button" class="blog_btn" value="Read Blog"/></div>
                                    <span class="clearfix"></span>
                                </article>
                                <?php }
                                    } else {
                                        echo '<p>No blog posts found. Click add post to create new blog post for your child.</p>';
                                    }
                                ?>
                            </div>
                        </section>
                    </section>

                </section>
                <?php
            }
        }
    }

    public static function editInfo(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['action']) {
            if ( Child::exists($data['id']) ) {
                self::create(true, $data['id']);
            }
        }
    }

    public static function deleteChild(array $data)
    {
        if ( (string) __FUNCTION__ === (string) $data['action']) {
            if ( Child::exists($data['id']) ) {
                if (wp_trash_post($data['id'])) {
                    echo json_encode(array('status' => 'Child deleted successfully.'));
                }
            }
        }
    }
}
