//Shortcode for single post slider
function custom_swiper_shortcode($atts) {
    // Extract shortcode attributes
    $atts = shortcode_atts(array(
        'category' => '',   // category slug
        'post' => '',       // post slug
        'posts_per_slide' => 4,  // number of posts per slide
    ), $atts);

    // Query posts based on category or post slug
    $args = array(
        'posts_per_page' => $atts['posts_per_slide'],
        'post_type' => 'post',
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    if (!empty($atts['category'])) {
        $args['category_name'] = $atts['category'];
    } elseif (!empty($atts['post'])) {
        $args['name'] = $atts['post'];
    }
    $query = new WP_Query($args);

    // Start building the slider HTML
    $output = '<div class="custom-swiper-container custom-swiper">';
    $output .= '<div class="swiper-wrapper">';

    while ($query->have_posts()) {
        $query->the_post();
        $output .= '<div class="swiper-slide">';
        $output .= '<div class="slide-content">';
        $output .= '<div class="image-container">' . get_the_post_thumbnail() . '</div>';
        $output .= '<div class="post-meta">';
        $output .= '<span class="category">' . get_the_category_list(', ') . '</span>';
        $output .= '<h3 class="post-title">' . get_the_title() . '</h3>';
		$output .= '<div class="meta-details">';
        $output .= '<span class="author">Por: ' . get_the_author() . '</span>';
        $output .= '<span class="date"> | ' . get_the_date() . '</span>';
		$output .= '</div>';
		$output .= '<div class="custom-swipe-btns">';
        $output .= '<div class="swiper-button-prev"></div>';
        $output .= '<div class="swiper-button-next"></div>';
	    $output .= '</div>';
        $output .= '</div>'; // .post-meta
        $output .= '</div>'; // .slide-content
        $output .= '</div>'; // .swiper-slide
    }

    // Finish building the slider HTML
    $output .= '</div>'; // .swiper-wrapper
    $output .= '</div>'; // .swiper-container

    // Enqueue Swiper.js library and initialize the slider
    wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css');
    wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js', array(), null, true);
    $output .= '<script>
        jQuery(document).ready(function($) {
            var swiper = new Swiper(".custom-swiper-container", {
                    slidesPerView: 1,
                    spaceBetween: 30,
                    loop: true,
                navigation: {
                    prevEl: ".custom-swiper-button-prev",
                    nextEl: ".custom-swiper-button-next",
                },
				
            });
        });
    </script>';

    // Reset the global $post variable
    wp_reset_postdata();

    return $output;
}
add_shortcode('custom_swiper', 'custom_swiper_shortcode');
