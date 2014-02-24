<ul id="child_listings" class="container"> <!-- nothing-cool -->
    <?php
    $childs_query = get_custom_posts(array(
        'post_type'         => $postType,
        'posts_per_page'    => 10,
        'author'            => user_info('ID')
    ), 'chmng_dp');

    if ($childs_query !== 0) {
        foreach ($childs_query as $child) :
            ?>
            <li class="child">
                <div class="child_thumb fleft clearfix">
                    <img src="<?php echo $child->thumbnail; ?>" alt="">
                </div>

                <div class="fleft">
                    <h3><?php echo $child->title; ?></h3>
                    <p><?php echo $child->age; ?></p>
                    <p><?php echo $child->permalink; ?></p>
                    <br clear="all">
                    <button class="classic-btn ajaxify" data-action='{"action" : "go_to_story", "id" : <?php echo $child->ID; ?>}'>Go to story</button>
                </div>
                <button class="downtown classic-btn ajaxify" data-action='{"action" : "edit_info", "id" : <?php echo $child->ID; ?>}'>Edit Information</button>
            </li>
        <?php
        endforeach;
    } else { ?>
        <li class="child"><h3>No child is created yet! Click the above add child button.</h3></li>
    <?php } ?>
</ul>