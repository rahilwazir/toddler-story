<form method="post" name="<?php echo $array_builder['form_name']; ?>" enctype="multipart/form-data">
    <?php
    if ($update) {
        echo '<input type="hidden" value="'.$post_id.'" name="post_id">';
    }

    $nonce = wp_create_nonce($array_builder['nonce']);
    echo '<input type="hidden" value="'.$nonce.'" name="rw_nonce" />';
    ?>
    <section id="form-top-puts" class="clearfix">
        <div class="fleft">
            <div class="input-box-wrap">
                <div class="form-control">
                    <label for="first_name">First Name:</label>
                </div>
                <div class="form-control form-input">
                    <input id="first_name" name="first_name" class="child_inputs" placeholder="First Name" value="<?php echo $array_builder['first_name']['value']; ?>" />
                </div>
            </div>

            <div class="input-box-wrap">
                <div class="form-control">
                    <label for="last_name">Last Name:</label>
                </div>
                <div class="form-control form-input">
                    <input id="last_name" name="last_name" class="child_inputs" placeholder="Last Name" value="<?php echo $array_builder['last_name']['value']; ?>" />
                </div>
            </div>

            <div class="input-box-wrap">
                <div class="form-control">
                    <label for="dob">Date of Birth:</label>
                </div>
                <div class="form-control form-input">
                    <input id="dob" name="dob" class="child_inputs" placeholder="11-11-2011" value="<?php echo $array_builder['dob']['value']; ?>" />
                </div>
            </div>

            <div class="input-box-wrap">
                <div class="form-control">
                    <label for="sex">Sex:</label>
                </div>
                <div class="form-control form-input">
                    <select id="sex" name="sex" class="child_inputs">
                        <?php foreach ($genderMeta as $val) : ?>
                            <option value="<?php echo $val; ?>" <?php selected($array_builder['sex']['value'], $val); ?>><?php echo $val; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="fright">
            <div class="input-box-wrap">
                <div class="form-control">
                    <label for="relation">Your Relation:</label>
                </div>
                <div class="form-control">
                    <select id="relation" name="relation" class="child_inputs">
                        <?php foreach ($relationMeta  as $val) : ?>
                            <option value="<?php echo $val; ?>" <?php selected($array_builder['relation']['value'], $val); ?>><?php echo $val; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="input-box-wrap">
                <div class="form-control">
                    <label for="story_title">Story Title:</label>
                </div>
                <div class="form-control form-input">
                    <input id="story_title" name="story_title" class="child_inputs" placeholder="Your awesome title" value="<?php echo $array_builder['title']; ?>" />
                </div>
            </div>

            <div class="input-box-wrap">
                <div class="form-control">
                    <label for="d_url">Direct URL:<p class="description"><?php echo get_post_type_link(); ?></p></label>
                </div>
                <div class="form-control form-input">
                    <input id="d_url" name="d_url" class="child_inputs" placeholder="child-slug" value="<?php echo $array_builder['d_url'] ?>" />
                </div>
            </div>
        </div>
    </section>
    <br clear="all" />

    <section id="background-chooser" class="clearfix">
        <ul>
            <li data-id="none">
                <span id="none-bg"></span>
                <span class="check <?php active_class('none', $array_builder['thumb_id']); ?>"></span>
            </li>
            <?php if ($backgroundChooser !== 0) : foreach ($backgroundChooser as $content) : ?>
                <li data-id="<?php echo $content->ID; ?>">
                    <img src="<?php echo $content->thumbnail; ?>" />
                    <span class="check <?php active_class($content->ID, $array_builder['thumb_id']); ?>"></span>
                </li>
            <?php endforeach; endif; ?>
        </ul>
        <input type="hidden" name="chosen_bg" value="<?php echo $array_builder['thumb_id']; ?>" />
    </section>

    <section id="select-baby-image" class="clearfix">
        <input type="file" name="baby_img" id="baby_img" class="disable" />
        <div id="preview-image" class="clearfix">
            <?php echo $array_builder['thumbnail']; ?>
            <span class="remove remove-icon disable"></span>
        </div>
        <label for="baby_img" class="submit-button clearfix">Upload a Child photo</label>
    </section>

    <section id="privacy_settings" class="clearfix">
        <p class="masquerade-heading">Privacy Settings:</p>
        <p>You have the option to choose whether the story should be available to everyone or only those you choose. To provide access to all check the field below. Otherwise, you select who you want to share with later.</p>

        <p class="neat-space">
            <span class="checkbox check <?php active_class('1', $array_builder['public_access']); ?>"></span> Allow Public access <input type="hidden" name="public_access" value="<?php echo $array_builder['public_access']; ?>" />
        </p>
    </section>

    <section id="form_action_buttons" class="clearfix">
        <div class="fright">
            <input type="button" name="cancel" class="submit-button cancel-btn ajaxify" value="Cancel" />
            <?php
            if ($update) {
                echo '<input type="button" value="Delete Child" data-action=\'{"action" : "delete_child", "id" : '.$post->ID.'}\' class="ajaxify submit-button danger" name="delete_child">';
            }
            ?>
            <input type="submit" name="save_changes" class="submit-button" value="Save Changes" />
        </div>
    </section>
</form>