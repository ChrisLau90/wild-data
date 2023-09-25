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

function display_posts_by_tag_shortcode($atts) {
    // Extract shortcode attributes
    $atts = shortcode_atts(array(
        'tag' => '', // Default tag is empty
    ), $atts);

    // Define the query arguments
    $args = array(
        'tag' => $atts['tag'],
        'post_type' => 'post', // You can specify other post types if needed
        'posts_per_page' => -1, // To retrieve all posts with the tag
    );

    $query = new WP_Query($args);

    // Initialize the output
    $output = '';

    // Check if there are posts with the specified tag
    if ($query->have_posts()) {
        $output .= '<div class="post-card-list">'; // Create a container for the cards

        while ($query->have_posts()) {
            $query->the_post();

            // Get featured image URL
            $featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'medium'); // Adjust 'medium' to your preferred image size

            // Get post categories
            $categories = get_the_category();
            $category_list = join(', ', wp_list_pluck($categories, 'name'));

            // Get post tags
            $tags = get_the_tags();
            $tag_list = join(', ', wp_list_pluck($tags, 'name'));

            // Build the post card (Divi column structure)
            $output .= '<div class="et_pb_column et_pb_column_4_12">'; // Each card is 4 out of 12 columns for a 3-column layout
            $output .= '<div class="post-card">';
            $output .= '<div class="post-thumbnail"><img src="' . esc_url($featured_image_url) . '" alt="' . esc_attr(get_the_title()) . '"></div>';
            $output .= '<div class="post-content">';
            $output .= '<h2 class="post-title"><a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a></h2>';
            $output .= '<div class="post-meta">' . get_the_date() . ' by ' . get_the_author() . '</div>';
            $output .= '<div class="post-categories">Categories: ' . esc_html($category_list) . '</div>';
            $output .= '<div class="post-tags">Tags: ' . esc_html($tag_list) . '</div>';
            $output .= '<div class="post-excerpt">' . get_the_excerpt() . '</div>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
        }

        $output .= '</div>'; // Close the container
        // Restore original post data
        wp_reset_postdata();
    } else {
        $output = 'No posts found with the specified tag.';
    }

    return $output;
}

add_shortcode('display_posts_by_tag', 'display_posts_by_tag_shortcode');