<?php get_header(); ?>

<?php 
$term = get_queried_object();
// Sélectionner tous les projects ayant ce terme
$args = [
	'post_type' => 'project',
  'numberposts' => -1,
  'tax_query' => [
    [
      'taxonomy' => 'skills',
      'field' => 'id',
      'terms' => $term->term_id
    ]
  ]
];
$projects = get_posts($args);
?>
<h1><?= $term->name ?></h1>
<?= $term->description ?>
<?php if(!empty($projects)){ ?>
	<h2><?= ucfirst(__('projects', 'ESGI')) ?></h2>
	<ul>
		<?php foreach ($projects as $project) { ?>
			<li><a href="<?= get_permalink($project) ?>"><?= $project->post_title ?></a></li>
		<?php } ?>
	</ul>
<?php } ?>

<?php get_footer(); ?>