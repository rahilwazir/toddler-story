<section id="form-top-puts" class="clearfix blog">
    <section class="tab_action">
        <div class="tabs">
            <?php foreach ($tabMenus->tabs_menu as $key => $val) : ?>
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

                <?php if ( $childBlogPosts ) { ?>
                    <section class="blog-list">
                        <?php foreach ($childBlogPosts as $_post) { ?>
                            <article class="blog_post" data-id="<?php echo $_post->ID; ?>">
                                <div class="date_box">
                                    <span class="date"><?php echo $_post->day ?></span>
                                    <span><?php echo $_post->month ?></span>
                                    <span><?php echo $_post->year ?></span>
                                </div>
                                <div class="blog_details">
                                    <h1 class="post-title"><a href="#" data-blog-value="<?php echo $_post->ID; ?>" class="read-blog"><?php echo $_post->title; ?></a></h1>
                                    <span class="comment-counter">
                                        <span class="comment-count"><?php echo $_post->total_comments; ?></span> comments
                                    </span>
                                    <p class="post-content"><?php echo $_post->fullContent; ?></p>

                                    <section class="comment-box disable">
                                        <div class="comment-input clearfix specific-loader">
                                            <textarea name="comment-content" rows="7" cols="30" required=""></textarea>
                                            <input type="button" class="blog_btn ajaxify clearfix specific" value="Add Comment" data-action='{"action" : "add_comment", "id" : <?php echo $_post->ID; ?>}'>
                                        </div>
                                        <?php $comments = get_comments($_post->ID); ?>
                                        <section class="comments-list clearfix">
                                            <?php
                                                if ( !empty($comments) ) {
                                                    foreach ($comments as $comment) {
                                            ?>
                                                <article class="single-comment comment-input" data-comment-id="<?php echo $comment->comment_ID; ?>">
                                                    <span class="comment-meta">Commented by: <?php echo $comment->comment_author; ?>, <?php echo $comment->comment_date; ?></span>
                                                    <div class="comment-content"><?php echo $comment->comment_content; ?></div>
                                                </article>
                                            <?php   }
                                                } else {
                                                    echo '<h3 id="no-comments-yet">No comments yet.</h3>';
                                                }
                                            ?>
                                        </section>
                                    </section>
                                </div>
                                <div class="bl_user"><input type="button" class="blog_btn read-blog" data-blog-value="<?php echo $_post->ID; ?>" value="Read Blog"/></div>
                                <span class="clearfix"></span>
                            </article>
                        <?php } ?>
                    </section>
                <?php } else {
                    echo '<p>No blog posts found. Click add post to create new blog post for your child.</p>';
                }
                ?>
            </div>
        </section>
    </section>

</section>