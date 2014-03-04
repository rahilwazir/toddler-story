<section id="form-top-puts" class="clearfix blog">
    <section class="tab_action clearfix">
        <div class="tabs">
            <?php if ($tabMenus->tabs_menu) : ?>
            <?php foreach ($tabMenus->tabs_menu as $key => $val) : ?>
                <a href="<?php echo deepLinking(6, $key) ?>"
                   class="submit-button unblock no-bordrad<?php echo ($deeplink == $key) ? ' active' : (($i === 0) ? ' active' : ''); ?>"
                    ><?php echo $val; ?></a>
                <?php $i++; endforeach; endif; ?>
        </div>
        <a href="" class="fright refresh-icon">Refresh</a>
    </section>

    <section class="life-story-section middle_hash_content">