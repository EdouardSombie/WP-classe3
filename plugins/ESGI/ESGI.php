<?php
/*
Plugin Name: Plugin ESGI
Plugin URI: https://esgi.fr
Description: Module WP
Author: ESGI team
Author URI: https://esgi.fr
Version: 1.0.0
*/

function esgi_custom_post_type() {

	$labels = [
	 'name' => __( 'Projets' ),
	 'singular_name' => __( 'Projet' ),
	 'all_items' => __( 'Tous les Projets' ),
	 'add_new' => _x( 'Ajouter un Projet', 'Projets' ),
	 'add_new_item' => __( 'Ajouter un Projet' ),
	 'edit_item' => __( 'Modifier un Projet' ),
	 'new_item' => __( 'Nouveaux Projets' ),
	 'view_item' => __( 'Voir le Projet' ),
	 'search_items' => __( 'Rechercher parmi les Projets' ),
	 'not_found' => __( 'Aucun Projet trouvé' ),
	 'not_found_in_trash' => __( 'Aucun Projet trouvé dans la corbeille' ),
	 'parent_item_colon' => ''
	 ];

	$args = [
	 'labels' => $labels,
	 'public' => true,
	 'has_archive' => true, 
	 'menu_icon' => 'dashicons-welcome-learn-more', //pick one here ~> https://developer.wordpress.org/resource/dashicons/
	 'rewrite' => array( 'slug' => 'project' ),
	 //'taxonomies' => array( 'post_tag' ),
	 'query_var' => true,
	 'menu_position' => 1,
	 'publicly_queryable' => true,
	 'supports' => array( 'thumbnail', 'editor', 'title', 'custom-fields'),
	 'show_in_rest' => true
	 ];

    register_post_type('project', $args);


    // Enregistrer la taxonomie
    $labels = [
	 'name' => __( 'Skills' ),
	 'singular_name' => __( 'Skill' ),
	 'all_items' => __( 'Tous les Skills' ),
	 'add_new' => _x( 'Ajouter un Skill', 'Skills' ),
	 'add_new_item' => __( 'Ajouter un Skill' ),
	 'edit_item' => __( 'Modifier un Skill' ),
	 'new_item' => __( 'Nouveaux Skills' ),
	 'view_item' => __( 'Voir le Skill' ),
	 'search_items' => __( 'Rechercher parmi les Skills' ),
	 'not_found' => __( 'Aucun Skill trouvé' ),
	 'not_found_in_trash' => __( 'Aucun Skill trouvé dans la corbeille' ),
	 'parent_item_colon' => ''
	 ];

	 $args = array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_in_rest'			=> true,
		'show_admin_column'     => true,
		'query_var'             => true,
		'rewrite'               => ['slug' => 'skills'],
	);

    register_taxonomy('skills', 'project', $args);

}
add_action('init', 'esgi_custom_post_type');


// Ajout d'un template pour le type single project et la taxonomie skills
function esgi_custom_post_template($template){
	//var_dump($template);
	if(is_single() && get_query_var('post_type') == 'project'){
		// Charger le template à partir du module
		$templates = [
            'single-project.php',
            'ESGI/templates/single-project.php'
        ];
        $template = locate_template($templates);
        if (!$template) {
            $template = __DIR__ . '/templates/single-project.php';
        }
	}

	if(is_tax('skills')){
    	$templates = [
            'taxonomy-skills.php',
            'ESGI/templates/taxonomy-skills.php'
        ];
        $template = locate_template($templates);
        if (!$template) {
            $template = __DIR__ . '/templates/taxonomy-skills.php';
        }
    }

	return $template;
}

add_filter('template_include', 'esgi_custom_post_template', 99);


// Shortcode
add_action( 'init', 'esgi_add_shortcode' );
function esgi_add_shortcode() {
    add_shortcode( 'skills-list', 'esgi_skills_list' );
}

function esgi_skills_list($att){
	$terms = get_terms('skills');
	$output = '';
	$title = isset($att['title']) ? $att['title'] : 'Titre par défaut';
	if(!empty($terms)){
		$output .= '<h2>' . $title . '</h2>';
		$output .= '<ul>';
		foreach($terms as $term){
			$output .= '<li><a href="' . get_term_link($term) . '">' . $term->name . '</a></li>';
		}
		$output .= '</ul>';
	}

	return $output;
}


// Ajouter un widget

class Skills_list_widget extends WP_Widget {
 
    public function __construct() {
        parent::__construct(
            'skills_list', // Base ID
            'skills list Widget', // Name
            array( 'description' => __( 'Affiche la liste des skills', 'ESGI' ), ) // Args
        );
    }
 
    public function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );
 		
        echo $before_widget;
        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }
        echo $this->getSkillsList();
        echo $after_widget;
    }
 
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'New title', 'text_domain' );
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
         </p>
    <?php
    }
 
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }

    public function getSkillsList(){
    	$terms = get_terms('skills');
		$output = '';
		if(!empty($terms)){
			$output .= '<ul>';
			foreach($terms as $term){
				$output .= '<li><a href="' . get_term_link($term) . '">' . $term->name . '</a></li>';
			}
			$output .= '</ul>';
		}
		return $output;
    }
}

add_action( 'widgets_init', 'esgi_register_widgets' );
 
function esgi_register_widgets() {
    register_widget( 'Skills_list_widget' );
}


// Internationalisation du thème

add_action( 'init', 'esgi_load_textdomain' );
function esgi_load_textdomain() {
	load_plugin_textdomain( 'ESGI', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

add_filter( 'load_textdomain_mofile', 'esgi_load_textdomain_mo', 10, 2 );
function esgi_load_textdomain_mo( $mofile, $domain ) {
    if ( 'ESGI' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
        $locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
        $mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' . $domain . '-' . $locale . '.mo';
    }
    return $mofile;
}


// INCLUSION DU PLUGIN ACF

// Define path and URL to the ACF plugin.
define( 'MY_ACF_PATH', WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/includes/acf/' );
define( 'MY_ACF_URL', WP_PLUGIN_URL . '/' . dirname( plugin_basename( __FILE__ ) ) . '/includes/acf/' );

// Include the ACF plugin.
include_once( MY_ACF_PATH . 'acf.php' );

// Customize the url setting to fix incorrect asset URLs.
add_filter('acf/settings/url', 'my_acf_settings_url');
function my_acf_settings_url( $url ) {
    return MY_ACF_URL;
}

// (Optional) Hide the ACF admin menu item.
//add_filter('acf/settings/show_admin', 'my_acf_settings_show_admin');
function my_acf_settings_show_admin( $show_admin ) {
    return false;
}


// Ajouter un custom field aux projects




function esgi_add_local_field_groups() {
	
	$post_object_field = array(
	
		/* ... Insert generic settings here ... */
		/* (string) Unique identifier for the field. Must begin with 'field_' */
		'key' => 'field_related_posts',
		
		/* (string) Visible when editing the field value */
		'label' => ucfirst(__('related posts', 'ESGI')),
		
		/* (string) Used to save and load data. Single word, no spaces. Underscores and dashes allowed */
		'name' => 'related_posts',
		
		/* (string) Type of field (text, textarea, image, etc) */
		'type' => 'post_object',
		
		/* (string) Instructions for authors. Shown when submitting data */
		'instructions' => __('Choose related posts', 'ESGI'),
		
		/* (int) Whether or not the field value is required. Defaults to 0 */
		'required' => 0,
		
		/* (mixed) Conditionally hide or show this field based on other field's values. 
		Best to use the ACF UI and export to understand the array structure. Defaults to 0 */
		'conditional_logic' => 0,
		
		/* (array) An array of attributes given to the field element */
		'wrapper' => array (
			'width' => '',
			'class' => '',
			'id' => '',
		),
		
		/* (mixed) A default value used by ACF if no value has yet been saved */
		'default_value' => '',
		
		/* (mixed) Specify an array of post types to filter the available choices. Defaults to '' */
		'post_type' => 'post',
		
		/* (mixed) Specify an array of taxonomies to filter the available choices. Defaults to '' */
		'taxonomy' => '',
		
		/* (bool) Allow a null (blank) value to be selected. Defaults to 0 */
		'allow_null' => 0,
		
		/* (bool) Allow mulitple choices to be selected. Defaults to 0 */
		'multiple' => 1,
		
		/* (string) Specify the type of value returned by get_field(). Defaults to 'object'.
		Choices of 'object' (Post object) or 'id' (Post ID) */
		'return_format' => 'object',
		
	);


	acf_add_local_field_group(array(
		'key' => 'group_1',
		'title' => ucfirst(__('project fields', 'ESGI')),
		'fields' => array (
			$post_object_field
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'project',
				),
			),
		),
	));
	
}

add_action('acf/init', 'esgi_add_local_field_groups');






