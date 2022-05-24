<?php get_header(); ?>

<main id="site-content">
	<div class="container">
		<?php include('template-parts/identity-card.php'); ?>
		<?php 
		if(!is_front_page()){
			include('template-parts/post-list.php');
		}

		?>
	</div>
</main>

<?php get_footer(); ?>