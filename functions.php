<?php

/** 
 * After theme setup hook actions
 */
add_action( 'after_setup_theme', function(){

    // This theme uses wp_nav_menu() in two locations.
	register_nav_menus(
		array(
			'menu' => __( 'Top Menu', 'tema' ),
		)
	);
    add_theme_support(
        'custom-logo',
        array(
            'height'      => 197,
            'width'       => 50,
            'flex-height' => true,
            'flex-width'  => true,
        )
    );
    
});

/** 
 * Load theme assets
 */
add_action('wp_enqueue_scripts', function() {
	$assets_src = get_template_directory_uri().'/assets';
	$version = wp_get_theme()->get( 'Version' );

	// Load theme style
	if(strpos(get_bloginfo('url'), 'local.wp4') !== false) {
		wp_enqueue_style( 'theme', "{$assets_src}/css/main.css", [], $version, 'all' );
	} else {
		wp_enqueue_style( 'theme', "{$assets_src}/css/main.min.css", [], $version, 'all' );
	}

	// Load theme 
	if(strpos(get_bloginfo('url'), 'local.wp4') !== false) {
		wp_enqueue_script( 'theme', "{$assets_src}/js/bundle.js", ['jquery'], $version, true );
	} else {
		wp_enqueue_script( 'theme', "{$assets_src}/js/bundle.min.js", ['jquery'], $version, true );
	}
}, 999, 1);

//registrando visitas
function US_set_post_views($postID) {
    $count_key = 'US_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
//Removendo pré-buscas para melhorar a precisão dos dados
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

function US_post_views_count($column) {
    $column['US_post_views_count'] = 'Visualizações';
    return $column;
}
add_filter('manage_posts_columns', 'US_post_views_count');
function views_count_show_columns($name) {
	global $post;
	switch ($name) {
		case 'US_post_views_count':
				$views = get_post_meta($post->ID, 'US_post_views_count', true);
				echo $views;
	}
}
add_action('manage_posts_custom_column',  'views_count_show_columns');
