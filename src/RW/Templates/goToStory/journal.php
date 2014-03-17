<section id="journal" class="tab_content enable">
	<section id="journal_user">
        <h1>GENERAL INFORMATION</h1>
        <div class="jl_user">
        	<input type="button" class="blog_btn" value="Edit" />
        </div>
        <span class="clearfix"></span>
        <section class="journal_detail">
            <article id="associated_doctor">
                <label>Associated doctor</label>
                <span>Rahil Newbie</span>
            </article>
            <article id="blood_group">
                <label>Blood Group</label>
                <span>AB+</span>
            </article>
            <article id="notes">
            	<label>Notes<label>
                <p>Well, i don't know how to write notes :)</p>
            </article>
        </section>
        <section  class="jurnal_content">
        	<h2>GENERAL INFORMATION</h2>
        	<div class="jl_user">
        	    <input type="button" class="blog_btn pop" value="Add" />
        	</div>
            <div class="clearfix"></div>
            <div class="table_journal">
            	<table>
                <thead>
                	<tr>
                        <th>Type</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($journalPosts) { foreach($journalPosts as $jpost) : ?>
                	<tr>
                        <td><?php echo get_post_meta($jpost->ID, '_toddler_child_journal_type', true); ?></td>
                        <td><?php echo $jpost->title; ?></td>
                        <td><?php echo get_post_meta($jpost->ID, '_dob_child', true); ?></td>
                        <td><?php echo $jpost->fullContent; ?></td>
                        <td><input type="button" class="blog_btn pop edit" value="Edit" />
                            <input type="button" class="blog_btn delete_journal danger" value="Delete" />
                            <div class="hd">
                                <input type="hidden" name="journal_id" value="<?php echo $jpost->ID; ?>">
                                <input type="hidden" name="journal_type" value="<?php echo get_post_meta($jpost->ID, '_toddler_child_journal_type', true); ?>">
                                <input type="hidden" name="journal_title" value="<?php echo $jpost->title; ?>">
                                <input type="hidden" name="journal_date" value="<?php echo get_post_meta($jpost->ID, '_dob_child', true); ?>">
                                <input type="hidden" name="journal_description" value="<?php echo $jpost->fullContent; ?>">
                                <input type="hidden" name="journal_token" value="<?php echo wp_create_nonce($jpost->ID); ?>">
                                <input type="hidden" name="journal_update_token" value="<?php echo wp_create_nonce('add_journal_update'); ?>">
                            </div>
                        </td>
                	</tr>
                    <?php endforeach; } else { ?>
                    <tr>
                        <td colspan="5"><strong>No activity</strong></td>
                    </tr>
                    <?php } ?>
                </tbody>
                </table>
            </div>
        </section>
    </section>

    <div class="popup disable">
        <form name="add_journal">
            <div class="fleft">
                <div class="form-control full">
                    <label for="type">Type:</label>
                </div>
                <div class="form-control full form-input">
                    <?php if ($journalTypes) { ?>
                        <select class="child_inputs" id="type" name="journal_type">
                        <?php foreach ($journalTypes as $type) :
                            echo '<option value="' . $type . '">' . $type . '</option>';
                        endforeach; ?>
                        </select>
                    <?php } ?>
                </div>
            </div>

            <div class="fright">
                <div class="form-control full">
                    <label for="dob">Date:</label>
                </div>
                <div class="form-control full form-input">
                    <input type="text" id="dob" value="" class="child_inputs" name="journal_date">
                </div>
            </div>

            <div class="clearfix full">
                <div class="form-control full">
                    <label for="title">Title:</label>
                </div>
                <div class="form-control full form-input">
                    <input type="text" name="journal_title" id="title" class="child_inputs" placeholder="Title" value="" />
                </div>

                <div class="form-control full">
                    <label for="description">Description:</label>
                </div>
                <div class="form-control full form-input">
                    <textarea rows="10" name="journal_description" rows="" id="description" class="child_inputs" placeholder="Description"></textarea>
                </div>
            </div>
            <div class="signin_reg">
                <input type="hidden" value="<?php echo wp_create_nonce('add_journal'); ?>" name="rw_nonce" />
                <input type="hidden" name="child_token" value="<?php echo wp_create_nonce(getSession('_goto_id')); ?>">
                <input type="hidden" name="child_id" value="<?php echo getSession('_goto_id'); ?>">

                <input type="submit" class="anc" name="journal_post" value="Publish">
            </div>
        </form>
    </div>
</section>


<script>Toddler.journalUtils();</script>