<?php
function theme_load_assets()
{
    wp_enqueue_style('thememaincss', get_theme_file_uri('/build/index.css'));
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=EB+Garamond&family=Roboto+Condensed:wght@300;400;700&display=swap', false);
    wp_enqueue_script('thememainjs', get_theme_file_uri('/assets/js/index.js'), [], '1.0', true);
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
            'menu-1' => esc_html__('Principal', 'theme-tailwind'),
            'footer-main' => esc_html__('Footer Principal', 'theme-tailwind'),
            'footer-secondary' => esc_html__('Footer Secundário', 'theme-tailwind'),
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
    require get_template_directory() . '/includes/woocommerce.php';
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