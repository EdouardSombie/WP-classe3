<?php get_header(); ?>

<main id="site-content">
	<div class="container">
		<?php include('template-parts/identity-card.php'); ?>
		<?php 
		if(!is_front_page()){

			// Récupérer les 6 derniers articles
			$args = [
				'post_type' => 'post',
				'numberposts' => 6
			];
			$posts = get_posts($args);
			include('template-parts/post-list.php');
		}

		?>
	</div>
</main>

<?php get_footer(); ?>