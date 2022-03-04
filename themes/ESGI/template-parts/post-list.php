<ul class="post-list">
	<?php foreach ($posts as $post) { ?>
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