<?php

//for security, hide wp version in web pages and feeds
function remove_version_info() {
     return '';
}
add_filter('the_generator', 'remove_version_info');

// use shortcodes in widgets
add_filter( 'widget_text', 'shortcode_unautop');
add_filter( 'widget_text', 'do_shortcode');

//register custom menus
if ( function_exists( 'register_nav_menus' ) ) {
	register_nav_menus(
		array(
		  'main-menu' => 'Main Menu',
		  'social-menu' => 'Social Menu'
		)
	);
}

register_nav_menus();

register_nav_menu("main_menu", "Main Menu");

$mainMenu = array(
	"theme_location" => "main_menu",
	"container" => "ul",
	"container_class" => "",
	"container_id" => "main_menu",
	"depth" => 1
);


//register sidebars
add_action( 'widgets_init', 'my_register_sidebars' );

function my_register_sidebars() {

	/* register the primary sidebar. */
	register_sidebar(
		array(
			'id' => 'primary',
			'name' => __( 'Primary Sidebar' ),
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);

	/* repeat register_sidebar() code for additional sidebars. */
}

// create page excerpts
add_post_type_support( 'page', 'excerpt' );

// get child pages
function get_child_pages() {

	global $post;

	rewind_posts(); // stop any previous loops
	query_posts(array('post_type' => 'page', 'posts_per_page' => -1, 'post_status' => publish,'post_parent' => $post->ID,'order' => 'ASC','orderby' => 'menu_order')); // query and order child pages

	while (have_posts()) : the_post();

		$childPermalink = get_permalink( $post->ID ); // post permalink
		$childID = $post->ID; // post id
		$childTitle = $post->post_title; // post title
		$childExcerpt = $post->post_excerpt; // post excerpt

		echo '<article id="page-excerpt-'.$childID.'" class="page-excerpt">';
		echo '<h3><a href="'.$childPermalink.'">'.$childTitle.' &raquo;</a></h3>';
		echo '<p>'.$childExcerpt.' <a href="'.$childPermalink.'">Read More&nbsp;&raquo;</a></p>';
		echo '</article>';

	endwhile;

	// reset query
	wp_reset_query();

}

function wsma_scripts() {
	wp_enqueue_style( 'wsma', get_stylesheet_uri() );
 
    //example add custom style
    //wp_enqueue_style( 'wsma-customcssexample', get_template_directory_uri() . '/example/example.css');
    
    //Enqueue jQuery first
	wp_enqueue_script('jquery'); 
    
    //true will load in footer which is usually what you want, false is header
    //20120206 is version number    
    wp_enqueue_script( 'wsma-bootstrap', get_template_directory_uri() . 'js/bootstrap.min.js', array('jquery'), '20151021', true );


    //example add font
    //wp_enqueue_style( 'wsma-fontexample', 'http://fonts.googleapis.com/css?family=Libre+Baskerville');


//	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
//		wp_enqueue_script( 'comment-reply' );
//	}
}
add_action( 'wp_enqueue_scripts', 'wsma_scripts' );

