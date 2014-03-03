<section id="form-top-puts" class="clearfix blog">
    <section class="tab_action clearfix">
        <div class="tabs">
            <?php foreach ($tabMenus->tabs_menu as $key => $val) : ?>
                <a href="<?php echo $referrer . $key; ?>"
                   class="submit-button unblock no-bordrad<?php echo ( $deeplink == $key) ? ' active' : (($i === 0) ? ' active' : ''); ?>"
                    ><?php echo $val; ?></a>
                <?php $i++; endforeach; ?>
        </div>
    </section>

    <section class="life-story-section middle_hash_content">