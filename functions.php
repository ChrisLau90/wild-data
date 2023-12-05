<?php
function wild_data_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'wild_data_theme_enqueue_styles' );
 
//you can add custom functions below this line:

function disable_media_comments( $open, $post_id ) {
    $post = get_post( $post_id );
    if( 'attachment' == $post->post_type )
        $open = false;
    return $open;
}
add_filter( 'comments_open', 'disable_media_comments', 10 , 2 );
