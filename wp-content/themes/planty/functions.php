<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style('theme-style', get_stylesheet_directory_uri() . '/assets/css/theme.css',
array(), filemtime(get_stylesheet_directory() . '/assets/css/theme.css'));
}


// Register the shortcode
add_shortcode('css_separator', 'css_separator_func');

// Define the shortcode function
function css_separator_func($atts) {
    // Extract shortcode attributes with default values
    $atts = shortcode_atts(array(
        'height' => '20px',
        'color' => '#ECE2DA',
        'border-bottom-left-radius' => '50% 20%',
        'border-bottom-right-radius' => '50% 20%',
        'margin_bottom' => '20px'
    ), $atts, 'css_separator');

    // Construct the inline CSS style using the shortcode attributes
    $style = sprintf(
        'style="width: 100%%; height: %s; background-color: %s; border-bottom-left-radius: %s; border-bottom-right-radius: %s; margin-bottom: %s;"',
        esc_attr($atts['height']),
        esc_attr($atts['color']),
        esc_attr($atts['border-bottom-left-radius']),
        esc_attr($atts['border-bottom-right-radius']),
        esc_attr($atts['margin_bottom'])
    );

    // Generate the separator HTML
    $separator_html = sprintf('<div %s></div>', $style);

    // Return the separator HTML
    return $separator_html;
}
?>
