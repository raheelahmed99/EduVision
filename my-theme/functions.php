<?php
function my_theme_setup() {
    add_theme_support('title-tag'); // Adds dynamic title tag support
    add_theme_support('post-thumbnails'); // Enables featured images
}
add_action('after_setup_theme', 'my_theme_setup');

if ( !class_exists( 'Redux' ) ) {
    return;
}

// Initialize Redux
Redux::setArgs( 'my_theme', array(
    'opt_name'             => 'my_theme_options',
    'display_name'         => 'Theme Options',
    'menu_title'           => 'Theme Options',
    'menu_type'            => 'menu',
    'page_slug'            => 'theme-options',
    'dev_mode'             => false,
) );

// Add a Sample Section
Redux::setSection( 'my_theme', array(
    'title'  => 'General Settings',
    'id'     => 'general',
    'fields' => array(
        array(
            'id'       => 'logo_image',
            'type'     => 'media',
            'title'    => 'Logo Upload',
            'default'  => '',
        ),
        array(
            'id'       => 'primary_color',
            'type'     => 'color',
            'title'    => 'Primary Color',
            'default'  => '#ff0000',
        ),
    ),
) );


function my_theme_enqueue_scripts() {
    // Enqueue Styles
    wp_enqueue_style('my-theme-style', get_stylesheet_uri());
    wp_enqueue_style('custom-style', get_template_directory_uri() . '/css/custom.css', array(), '1.0.0', 'all');
    
    // Enqueue Scripts
    wp_enqueue_script('custom-script', get_template_directory_uri() . '/js/custom.js', array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_scripts');

echo esc_html(get_the_title());

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
}

the_post_thumbnail('medium', ['loading' => 'lazy']);

function my_theme_menus() {
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'my-theme'),
        'footer'  => __('Footer Menu', 'my-theme'),
    ));
}
add_action('init', 'my_theme_menus');

function my_theme_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'my-theme'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here.', 'my-theme'),
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'my_theme_widgets_init');

function my_theme_customizer($wp_customize) {
    $wp_customize->add_section('my_theme_colors', array(
        'title' => __('Theme Colors', 'my-theme'),
    ));

    $wp_customize->add_setting('primary_color', array(
        'default' => '#0073e6',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'primary_color',
        array(
            'label'    => __('Primary Color', 'my-theme'),
            'section'  => 'my_theme_colors',
        )
    ));
}
add_action('customize_register', 'my_theme_customizer');

?>