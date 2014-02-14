<?php get_header('child'); ?>
<div class="content">
    <div class="left_section">
        <h1>Hey, I’m <?php echo RW\Modules\Child::firstName(); ?></h1>
        <div class="column_left">
            <div class="profile_pic">
                <?php echo RW\Modules\Child::thumb(); ?>
            </div>
            <div class="info">
                <h1>Name</h1>
                <h2><?php echo RW\Modules\Child::firstName() . ' ' . RW\Modules\Child::lastName(); ?></h2>
            </div>
        </div>
        <div class="column_right">
            <div class="info">
                <h1>Nick Name</h1>
                <h2>Andy</h2>
            </div>
            <div class="info">
                <h1>Age</h1>
                <h2><?php echo RW\Modules\Child::age(); ?></h2>
            </div>
            <div class="info">
                <h1>Birthday</h1>
                <h2><?php echo RW\Modules\Child::birthDate(); ?></h2>
            </div>
            <div class="info">
                <h1>weight</h1>
                <h2>4 Kg</h2>
            </div>
            <div class="info">
                <h1>Height</h1>
                <h2>12 cm</h2>
            </div>
        </div>
    </div>
    <div class="right_section">
        <h2>Right Now</h2>
        <div class="green_box">
            <p>Am I 12 cm and Weight 0 Kilo. Furthermore, I have 2 Teeth</p>
        </div>
        <h1 class="wid_title">Description</h1>
        <p><?php echo RW\Modules\Child::description(); ?></p>
    </div>
<?php get_footer('child'); ?>
<?php #var_dump(get_post_meta($post->ID)); ?>