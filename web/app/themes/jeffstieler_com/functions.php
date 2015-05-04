<?php

add_action( 'wp_enqueue_scripts', function() {

	wp_enqueue_style( 'twentyfifteen-style', get_template_directory_uri() . '/style.css' );

	wp_enqueue_style( 'jeffstieler_com-style', get_stylesheet_uri() );

} );