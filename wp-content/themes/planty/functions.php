<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style('theme-style', get_stylesheet_directory_uri() . '/assets/css/theme.css',
array(), filemtime(get_stylesheet_directory() . '/assets/css/theme.css'));
}

// Enregistre le shortcode
add_shortcode('css_separator', 'css_separator_func');

// Défini la fonction shortcode
function css_separator_func($atts) {
    // Extrait les attributs du shortcode avec les valeurs par défaut
    $atts = shortcode_atts(array(
        'height' => '20px',
        'color' => '#ECE2DA',
        'border-bottom-left-radius' => '50% 20%',
        'border-bottom-right-radius' => '50% 20%',
        'margin_bottom' => '20px'
    ), $atts, 'css_separator');

    // Construiction du style CSS à l'aide des attributs du shortcode
    $style = sprintf(
        'style="width: 100%%; height: %s; background-color: %s; border-bottom-left-radius: %s; border-bottom-right-radius: %s; margin-bottom: %s;"',
        esc_attr($atts['height']),
        esc_attr($atts['color']),
        esc_attr($atts['border-bottom-left-radius']),
        esc_attr($atts['border-bottom-right-radius']),
        esc_attr($atts['margin_bottom'])
    );

    // Génère le séparateur HTML
    $separator_html = sprintf('<div %s></div>', $style);

    // Renvoie le séparateur HTML
    return $separator_html;
}

add_filter( 'wp_nav_menu_header-menu_items', 'prefix_add_menu_item', 10, 2 );

// Défini la fonction pour modifier les éléments de menu
function prefix_add_menu_item( $items, $args ) {
    // Vérifie si l'utilisateur est connecté
    if ( is_user_logged_in() ) {
        // Initialise un tableau pour stocker les éléments du menu
        $menu_items = [];

        // Décompose les éléments du menu dans un tableau
        $menu_items_array = explode( '</li>', $items );

        // Parcoure les éléments du menu
        foreach ( $menu_items_array as $index => $menu_item ) {
            // Ajoute les éléments du menu actuel au nouveau tableau
            $menu_items[] = $menu_item;

            // Insère un élément de menu personnalisé après le premier élément de menu
            if ( $index === 0 ) {
                $menu_items[] = '<li class="menu-item"><a class="menu-admin" href="' . esc_url( get_site_url() ) . '/wp-admin/">Admin</a></li>';
            }
        }

        // Recompose les éléments du menu dans une chaîne de caractère
        $items = implode( '', $menu_items );
    }

    // Renvoie les éléments de menu modifiés
    return $items;
}
?>
