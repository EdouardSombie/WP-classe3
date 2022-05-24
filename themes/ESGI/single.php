<?php get_header(); ?>

<main id="site-content">
	<div class="container">
		<article>
			<header>
				<h1><?php the_title() ?></h1>
				<div class="post-meta">
					<?php 
					$author_id = $post->post_author;
					$author_name = get_the_author_meta('nickname', $author_id);
					?>
					<img src="<?= get_avatar_url($author_id) ?>" alt="<?= $author_name ?>"> 
					<?= $author_name ?>
				</div>
			</header>
			<?= get_the_post_thumbnail($post->ID) ?>
			<?php the_content() ?>
		</article>

	</div>
</main>

<?php get_footer(); ?>