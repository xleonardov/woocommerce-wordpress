<?php
function theme_load_assets()
{
  wp_enqueue_style('thememaincss', get_theme_file_uri('/build/index.css'));
  wp_enqueue_script('thememainjs', get_theme_file_uri('/assets/js/index.js'), [], '1.0', true);
}

add_action('wp_enqueue_scripts', 'theme_load_assets');


function theme_add_support()
{
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
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

/*
Admin Login
function login_logo()
{ ?>
<style type="text/css">
#login h1 a, .login h1 a {
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