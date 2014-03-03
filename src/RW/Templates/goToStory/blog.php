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
                                    <a href="<?php echo $hashtags[9] . '-' . $_post->ID; ?>" class="blog_btn fright ajaxify clearfix specific block">Add Comment</a>
                                </div>
                                <?php $comments = get_comments($_post->ID); ?>
                                <section class="comments-list clearfix">
                                    <?php
                                    if ( !empty($comments) ) {
                                        foreach ($comments as $comment) {
                                            ?>
                                            <article class="single-comment removal-input specific-loader comment-input" data-comment-id="<?php echo $comment->comment_ID; ?>">
                                                <span class="comment-meta">Commented by: <?php echo $comment->comment_author; ?>, <?php echo $comment->comment_date; ?></span>
                                                <div class="comment-content"><?php echo $comment->comment_content; ?></div>
                                                <a href="<?php echo $hashtags[8] . '-' . $comment->comment_ID; ?>" class="remove-comment block remove-icon disable">Remove Comment</a>
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