<?php get_header(); ?>
<h1><?php the_title() ?></h1>
<?= get_the_post_thumbnail($post->ID) ?>
<?php the_content() ?>
<?php get_footer(); ?>