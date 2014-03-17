<section id="blog">
    <div class="blog_user">
        <h1>Blogs</h1>
        <div class="bl_user"><input type="button" class="blog_btn" id="add_new_blog" value="Add Post"/></div>
        <span class="clearfix"></span>

        <?php if ( $childBlogPosts ) { ?>
            <section class="blog-list">
                <?php foreach ($childBlogPosts as $_post) { ?>
                    <article class="blog_post clearfix" data-id="<?php echo $_post->ID; ?>">
                        <div class="date_box">
                            <span class="date"><?php echo $_post->day ?></span>
                            <span class="date_month"><?php echo $_post->month ?></span>
                            <span class="date_year"><?php echo $_post->year ?></span>
                        </div>
                        <div class="blog_details">
                            <h1 class="post-title"><a href="#" data-blog-value="<?php echo $_post->ID; ?>" class="read-blog"><?php echo $_post->title; ?></a></h1>
                            <span class="comment-counter">
                                <span class="comment-count"><?php echo $_post->total_comments; ?></span> comments
                            </span>
                            <p class="post-content"><?php echo $_post->fullContent; ?></p>

                            <section class="comment-box disable">
                                <div class="comment-input clearfix specific-loader">
                                    <textarea class="child_inputs" name="comment-content" rows="7" cols="30" required=""></textarea>
                                    <input type="button" data-value="<?php echo $hashtags[9] . '-' . $_post->ID; ?>" class="blog_btn fright ajaxify clearfix specific block" value="Add Comment">
                                </div>
                                <?php $comments = get_comments(array('post_id' => $_post->ID)); ?>
                                <section class="comments-list clearfix">
                                    <?php
                                    if ( !empty($comments) ) {
                                        foreach ($comments as $comment) {
                                            ?>
                                            <article class="single-comment removal-input specific-loader comment-input" data-comment-id="<?php echo $comment->comment_ID; ?>">
                                                <span class="comment-meta">Commented by: <?php echo $comment->comment_author; ?>, <?php echo $comment->comment_date; ?></span>
                                                <div class="comment-content"><?php echo $comment->comment_content; ?></div>
                                                <input data-value="<?php echo $hashtags[8] . '-' . $comment->comment_ID; ?>" class="remove-comment block remove-icon disable" value="Remove Comment">
                                            </article>
                                        <?php   }
                                    } else {
                                        echo '<h3 class="no-comments-yet">No comments yet.</h3>';
                                    }
                                    ?>
                                </section>
                            </section>
                        </div>
                        <div class="bl_user">
                            <input type="button" class="blog_btn read-blog" data-blog-value="<?php echo $_post->ID; ?>" value="Read Blog"/>
                            <input type="button" class="blog_btn disable edit-blog" data-blog-value="<?php echo $_post->ID; ?>" value="Edit"/>
                            <input type="button" class="blog_btn disable danger delete-blog" data-blog-value="<?php echo $_post->ID; ?>" value="Delete"/>
                            <input type="hidden" name="blog_id" value="<?php echo $_post->ID; ?>">
                            <input type="hidden" name="blog_token" value="<?php echo wp_create_nonce($_post->ID); ?>">
                            <input type="hidden" name="blog_update_token" value="<?php echo wp_create_nonce('au_child_blog_update'); ?>">
                        </div>
                        <span class="clearfix"></span>
                    </article>
                <?php } ?>
            </section>
        <?php } else {
            echo '<p>No blog posts found. Click add post to create new blog post for your child.</p>';
        }
        ?>

        <!-- child adding/updating form -->
        <section class="disable clearfix" id="new-post-screen">
            <article class="blog_post clearfix">
                <div class="date_box">
                    <span class="date"><?php echo date('d'); ?></span>
                    <span class="date_month"><?php echo date('F'); ?></span>
                    <span class="date_year"><?php echo date('Y'); ?></span>
                </div>

                <div class="blog_details">
                    <form name="au_child_blog" method="post">
                        <div class="input-box-wrap">
                            <div class="form-control">
                                <label for="child_blog_title">Title:</label>
                            </div>
                            <div class="form-control form-input">
                                <input type="text" name="child_blog_title" id="child_blog_title" class="child_inputs">
                            </div>
                        </div>
                        <div class="input-box-wrap">
                            <div class="form-control">
                                <label for="child_blog_description">Description</label>
                            </div>
                            <div class="form-control form-input">
                                <textarea class="child_inputs" name="child_blog_description" id="child_blog_description" rows="15" cols="10"></textarea>
                            </div>
                        </div>
                        <div class="input-box-wrap">
                            <div class="form-control fright">
                                <input type="hidden" value="<?php echo wp_create_nonce('au_child_blog'); ?>" name="rw_nonce" />
                                <input type="hidden" name="child_token" value="<?php echo wp_create_nonce(getSession('_goto_id')); ?>">
                                <input type="hidden" name="child_id" value="<?php echo getSession('_goto_id'); ?>">
                                <input type="submit" class="submit-button" name="publish_child_blog">
                            </div>
                        </div>
                    </form>
                </div>

            </article>
        </section>
    </div>
</section>
<script>Toddler.blogUtils();</script>