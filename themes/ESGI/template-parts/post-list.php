<?php 

$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

$args = [
	'post_type' => 'post',
	'posts_per_page' => 2,
	'post_status' => 'publish',
	'paged' => $paged,
];

$the_query = new WP_Query($args);

?>
<?php if ($the_query->have_posts()) { ?>
<ul class="post-list">
	<?php while($the_query->have_posts()) { 
		$the_query->the_post();
		?>
		<li>
			<a href="<?= get_permalink($post->ID) ?>" alt="<?= $post->post_title ?>">
				<article>
					<h1><?= $post->post_title ?></h1>
					<time><?= date_i18n('j F Y', $post->post_date) ?></time>
				</article>
			</a>
		</li>
	<?php } ?>
</ul>

<?= paginate_links([
	'total' => $the_query->max_num_pages,
	'current' => $paged
]); ?>

<?php } ?>