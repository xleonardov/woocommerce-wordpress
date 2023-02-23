<?php
function theme_load_assets()
{
    if (is_page('personalizar')) {
        wp_enqueue_script('app-personalizados', get_stylesheet_directory_uri() . '/assets/personalizados/js/main.60b1ec85.js', array(), null, true);
        wp_enqueue_style('style-personalizados', get_stylesheet_directory_uri() . '/assets/personalizados/css/main.fb2facf8.css', array(), null, '');
    }
    wp_enqueue_style('swiper_css', get_theme_file_uri('/assets/css/swiper-bundle.min.css'));
    wp_enqueue_style('thememaincss', get_theme_file_uri('/build/index.css'));
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=EB+Garamond&family=Roboto+Condensed:wght@300;400;700&display=swap', false);
    wp_enqueue_script('thememainjs', get_theme_file_uri('/assets/js/index.js'), [], '1.0', true);
    wp_enqueue_script('menujs', get_theme_file_uri('/assets/js/menu.js'), [], '1.0', true);
    wp_enqueue_script('acordeaojs', get_theme_file_uri('/assets/js/acordeao.js'), [], '1.0', true);
    wp_enqueue_script('swiper_js', get_theme_file_uri('/assets/js/swiper-bundle.min.js'), [], '1.0', false);
    wp_localize_script(
        'theme-woocommerce-scripts',
        'woocommerce_scritps_helper',
        array(
            'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php',
        )
    );
    wp_enqueue_script('theme-woocommerce-scripts');
}

add_action('wp_enqueue_scripts', 'theme_load_assets');


function theme_add_support()
{
    $logo_defaults = array(
        'height' => 44,
        'width' => 150,
        'flex-height' => true,
        'flex-width' => true,
        'header-text' => array('site-title', 'site-description'),
        'unlink-homepage-logo' => true,
    );
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', $logo_defaults);
    register_nav_menus(
        array(
            'main' => esc_html__('Principal', 'theme-tailwind'),
            'footer-main' => esc_html__('Footer Principal', 'theme-tailwind'),
            'footer-secondary' => esc_html__('Footer Secundário', 'theme-tailwind'),
            'footer-tertiatry' => esc_html__('Footer Terciário', 'theme-tailwind'),
        )
    );

    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );
}

add_action('after_setup_theme', 'theme_add_support');


if (class_exists('WooCommerce')) {
    include get_template_directory() . '/includes/woocommerce.php';
}

if (function_exists('acf_add_options_page')) {
    acf_add_options_page(
        array(
            'page_title' => 'Definições Tema',
            'menu_title' => 'Definições Tema',
            'menu_slug' => 'theme-settings',
            'capability' => 'edit_posts',
            'redirect' => true,
            'icon_url' => 'dashicons-admin-settings'
        )
    );
    acf_add_options_sub_page(
        array(
            'page_title' => 'Definições Tema - Footer',
            'menu_title' => 'Footer',
            'parent_slug' => 'theme-settings'
        )
    );
}

/*
Admin Login
function login_logo()
{ ?>
<style type="text/css">
// login h1 a, .login h1 a {
background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/theme-tailwind.svg);
height: 100px;
width: 320px;
background-size: 320px 100px;
background-repeat: no-repeat;
padding-bottom: 30px;
}
</style>
<?php }
add_action('login_enqueue_scripts', 'login_logo');
add_filter('login_headerurl', 'login_logo_url');
function login_logo_url($url)
{
return 'http://willbe.co';
}
*/