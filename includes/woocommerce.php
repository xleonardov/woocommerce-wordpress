<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package pacto
 */
define('PERPAGE', 16);
/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)
 * @link https://github.com/woocommerce/woocommerce/wiki/Declaring-WooCommerce-support-in-themes
 *
 * @return void
 */
function theme_woocommerce_setup()
{
  add_theme_support(
    'woocommerce',
    array(
      'thumbnail_image_width' => 150,
      'single_image_width' => 300,
      'product_grid' => array(
        'default_rows' => 3,
        'min_rows' => 1,
        'default_columns' => 4,
        'min_columns' => 1,
        'max_columns' => 6,
      ),
    )
  );
  add_theme_support('wc-product-gallery-zoom');
  add_theme_support('wc-product-gallery-lightbox');
  add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'theme_woocommerce_setup');

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */

add_action('wp_enqueue_scripts', 'woocommerce_custom_scripts', 99);

function woocommerce_custom_scripts()
{
  //remove generator meta tag
  remove_action('wp_head', array($GLOBALS['woocommerce'], 'generator'));

  //first check that woo exists to prevent fatal errors
  if (function_exists('is_woocommerce')) {

    // if (is_woocommerce() || is_cart() || is_checkout()) {
    wp_register_script('pacto-wc-functions', get_template_directory_uri() . '/assets/js/woocommerce.js', array('wp-element', 'jquery'), '1.0', true);
    wp_localize_script(
      'pacto-wc-functions',
      'woocommerce_scritps_helper',
      array(
        'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php',
      )
    );
    wp_enqueue_script('pacto-wc-functions');
    // }
    //dequeue scripts and styles
    if (!is_woocommerce() && !is_cart() && !is_checkout()) {
      wp_dequeue_style('woocommerce_frontend_styles');
      wp_dequeue_style('woocommerce_fancybox_styles');
      wp_dequeue_style('woocommerce_chosen_styles');
      wp_dequeue_style('woocommerce_prettyPhoto_css');
      wp_dequeue_script('wc_price_slider');
      wp_dequeue_script('wc-single-product');
      wp_dequeue_script('wc-add-to-cart');
      wp_dequeue_script('wc-cart-fragments');
      wp_dequeue_script('wc-checkout');
      wp_dequeue_script('wc-add-to-cart-variation');
      wp_dequeue_script('wc-single-product');
      wp_dequeue_script('wc-cart');
      wp_dequeue_script('wc-chosen');
      wp_dequeue_script('woocommerce');
      wp_dequeue_script('prettyPhoto');
      wp_dequeue_script('prettyPhoto-init');
      wp_dequeue_script('jquery-blockui');
      wp_dequeue_script('jquery-placeholder');
      wp_dequeue_script('fancybox');
      wp_dequeue_script('jqueryui');
    }
  }

}


/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function theme_woocommerce_active_body_class($classes)
{
  $classes[] = 'woocommerce-active';

  return $classes;
}
add_filter('body_class', 'theme_woocommerce_active_body_class');

/**
 * Related Products Args.
 *
 * @param  array $args related products args.
 * @return array $args related products args.
 */
function theme_woocommerce_related_products_args($args)
{
  $defaults = array(
    'posts_per_page' => 3,
    'columns' => 3,
  );

  $args = wp_parse_args($defaults, $args);

  return $args;
}
add_filter('woocommerce_output_related_products_args', 'theme_woocommerce_related_products_args');

/**
 * Remove default WooCommerce wrapper.
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

if (!function_exists('theme_woocommerce_wrapper_before')) {
  /**
   * Before Content.
   *
   * Wraps all WooCommerce content in wrappers which match the theme markup.
   *
   * @return void
   */
  function theme_woocommerce_wrapper_before()
  {
    ?>
    <main id="primary" class="site-main w-full max-w-container mx-auto px-4">
    <?php
  }
}
add_action('woocommerce_before_main_content', 'theme_woocommerce_wrapper_before');

if (!function_exists('theme_woocommerce_wrapper_after')) {
  /**
   * After Content.
   *
   * Closes the wrapping divs.
   *
   * @return void
   */
  function theme_woocommerce_wrapper_after()
  {
    ?>
    </main><!-- #main -->
  <?php
  }
}
add_action('woocommerce_after_main_content', 'theme_woocommerce_wrapper_after');

if (!function_exists('theme_woocommerce_floating_cart_fragment')) {
  function theme_woocommerce_floating_cart_fragment($fragments)
  {
    ob_start();
    theme_woocommerce_floating_cart();
    $fragments['.cart-container'] = ob_get_clean();

    return $fragments;
  }
}
add_filter('woocommerce_add_to_cart_fragments', 'theme_woocommerce_floating_cart_fragment');

if (!function_exists('theme_woocommerce_floating_cart')) {
    function theme_woocommerce_floating_cart()
    {
        global $woocommerce;
        $icons = new Icons;
        $cart = WC()->cart->get_cart();
        ?>
    <div class="cart-container grid grid-rows-[auto_130px] md:grid-rows-[auto_160px] h-[calc(100vh_-_80px)]">
        <?php if (count($cart) > 0) { ?>
        <div class="overflow-y-auto">
            <?php foreach ($cart as $cart_item_key => $cart_item) {
                $product = $cart_item['data'];
                $product_id = $cart_item['product_id'];
                $quantity = $cart_item['quantity'];
                $subtotal = WC()->cart->get_product_subtotal($product, $cart_item['quantity']);
                $post_thumbnail_id = get_post_thumbnail_id($product_id);
                $product_thumbnail = wp_get_attachment_image_src($post_thumbnail_id, $size = 'shop-feature');
                $product_thumbnail_alt = get_post_meta($post_thumbnail_id, '_wp_attachment_image_alt', true);
                $pn = get_field('pn', $product_id);
                ?>
            <div class="cart-product grid grid-cols-[auto_124px_1fr] border-b-[1px] py-4">
              <div class="flex items-center justify-center flex-none">
                <button class="remove_item_from_cart cursor-pointer w-4 h-4" data-product_id="<?php echo $cart_item_key;
                ?>">
                  <?php echo $icons->get_icon('GrClose'); ?></button>
              </div>
              <div class="cart-product-image w-[124px]">
                <img src="<?php echo $product_thumbnail[0]; ?>" alt="<?php echo $product_thumbnail_alt; ?>">
              </div>
              <div class="cart-product-info flex flex-col justify-between">
                <div class="cart-product-info-name text-sm mt-8">
                  <a href="<?php echo get_permalink($product_id) ?>"
                    class="name font-roboto"><?php echo $product->get_name(); ?></a>
                </div>
                <div class="flex items-center gap-3 mb-8">
                  <div class="quantity"><label>x</label>
                    <?php echo $quantity ?>
                  </div>
                  <div class="price text-lg font-roboto font-bold">
                    <?php echo $subtotal ?>
                  </div>
                </div>
              </div>
            </div>
                <?php
            }
            ?>
        </div>
        <div class="">
          <h4 class="font-roboto">TOTAL(
            <?php echo $woocommerce->cart->cart_contents_count ?> item
            <?php echo $woocommerce->cart->cart_contents_count > 1 ? 's' : '' ?>)
            <span class="text-xl font-bold text-secondary pl-2">
              <?php echo WC()->cart->get_displayed_subtotal(); ?>€
            </span>
          </h4>
          <div class="text-sm font-roboto">Custo de portes + Taxas calculadas no checkout</div>
          <div class="grid grid-cols-2 gap-4 w-full bg-white py-4 lg:py-8  ">
            <a href="<?php echo wc_get_cart_url() ?>" class="text-sm btn btn-outline flex justify-center"><?php echo
                 _e("Ver carrinho", "wlb_theme") ?></a>
            <a href="<?php echo wc_get_checkout_url() ?>" class="text-sm btn btn-primary flex justify-center"><?php echo
                 _e("Finalizar compra", "wlb_theme") ?></a>
          </div>
        </div>
        <?php } else { ?>
        <p class="tracking-wide p-4 lg:p-8">
            <?php echo _e("Ainda não tem produtos no carrinho.", "wlb_theme") ?>
        </p>
        <?php } ?>
    </div>

        <?php
    }
}

add_filter('woocommerce_add_to_cart_fragments', 'theme_add_to_cart_fragment');

function theme_add_to_cart_fragment($fragments)
{
  global $woocommerce;
  $icons = new Icons;

  $fragments['.shop-cart-counter'] = '<span class="shop-cart-counter absolute top-[-10px] right-[-10px] bg-primary text-white p-[10px] text-xs rounded-full w-4 h-4 flex justify-center items-center">' . $woocommerce->cart->cart_contents_count . '</span>';

  ob_start();
  theme_woocommerce_floating_cart();

  $fragments['.cart-container'] = ob_get_clean();
  return $fragments;

}

function woocommerce_ajax_add_to_cart()
{
  $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
  $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
  $variation_id = absint($_POST['variation_id']);
  $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
  $product_status = get_post_status($product_id);
  if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {
    do_action('woocommerce_ajax_added_to_cart', $product_id);
    if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
      wc_add_to_cart_message(array($product_id => $quantity), true);
    }
    WC_AJAX::get_refreshed_fragments();
  } else {
    $data = array(
      'error' => true,
      'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
    );
    echo wp_send_json($data);
  }
  wp_die();
}


add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');


function woocommerce_ajax_remove_from_cart()
{

  $product_id = apply_filters('woocommerce_remove_from_cart_product_id', $_POST['product_id']);
  // $product_cart_id = WC()->cart->generate_cart_id($product_id);
  $cart_item_key = WC()->cart->find_product_in_cart($product_id);

  if ($cart_item_key) {
    WC()->cart->remove_cart_item($cart_item_key);
    WC_AJAX::get_refreshed_fragments();
  } else {
    $data = array(
      'error' => true,
      'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
    );
    echo wp_send_json($data);
  }
  wp_die();
}

add_action('wp_ajax_woocommerce_ajax_remove_from_cart', 'woocommerce_ajax_remove_from_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_remove_from_cart', 'woocommerce_ajax_remove_from_cart');

function woocommerce_ajax_remove_from_cart_confirmation()
{
  $cart = WC()->cart->get_cart();
  $product_id = apply_filters('woocommerce_ajax_remove_from_cart_confirmation', $_POST['product_id']);
  $product = $cart[$product_id]['data'];
  $icons = new Icons;
  ?>
  <div
    class="modal-remove-product-cart fixed top-0 left-0 w-full h-screen bg-transparent z-50 flex justify-center items-center">
    <div class="overlay w-full h-screen absolute top-0 z-30"></div>
    <div class="bg-white max-w-md w-full p-4  opacity-0 transition-all translate-y-[10px] z-[51] font-roboto">
      <div class="flex justify-end mb-4">
        <div class="modal-remove-product-close cursor-pointer ">
          <?php echo $icons->get_icon('GrClose'); ?>
        </div>
      </div>
      <div>
        <p class="mb-4 tracking-wide text-lg">
          <?php echo _e("Pretende eliminar", "theme-tailwind") ?> <b>
            <?php echo $product->get_name() ?>
          </b>
          <?php echo _e("do carrinho de compras?", "theme-tailwind") ?>
        </p>
        <span class="text-xs text-[#8d929b] tracking-wide">
          <?php echo _e("Após eliminar este produto, para o voltar a ter no carrinho, terá de o adicionar novamente.", "theme-tailwind") ?>
        </span>
      </div>
      <div class="flex space-x-4 justify-end mt-8">
        <button class="btn btn-primary">
          <?php
          echo _e("Cancelar", "theme-tailwind") ?>
        </button>
        <button class="btn btn-outline">
          <?php echo _e("Eliminar", "theme-tailwind") ?>
        </button>
      </div>
    </div>
  </div>
  <?php
  wp_die();
}

add_action('wp_ajax_woocommerce_ajax_remove_from_cart_confirmation', 'woocommerce_ajax_remove_from_cart_confirmation');
add_action('wp_ajax_nopriv_woocommerce_ajax_remove_from_cart_confirmation', 'woocommerce_ajax_remove_from_cart_confirmation');

function woocommerce_ajax_update_cart_fragments_cart()
{
  WC_AJAX::get_refreshed_fragments();
  wp_die();
}

add_action('wp_ajax_woocommerce_ajax_update_cart_fragments_cart', 'woocommerce_ajax_update_cart_fragments_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_update_cart_fragments_cart', 'woocommerce_ajax_update_cart_fragments_cart');

//FILTERS

// SEARCH
function modify_meta_sql($sql, $queries, $type, $primary_table, $primary_id_column, $context)
{
  if ($context !== null && $context->is_search()) {
    $sql['where'] = preg_replace('/AND/', 'OR', $sql['where'], 1);
  }
  return $sql;
}
add_filter('get_meta_sql', 'modify_meta_sql', 10, 6);

add_action(
  'pre_get_posts',
  function ($query) {
    if (!is_admin()) {
      if ((is_shop() || is_product_category() || is_product_tag()) && $query->is_main_query()) {
        $arguments = $_GET;
        if (count($arguments)) {
          $new_args = array();
          $new_args['tax_query'] = array("relation" => "AND");
          foreach ($arguments as $key => $argument) {
            if ($key === 'paged') {
              continue;
            }
            if ($key === 'orderby') {
              $query->set('orderby', 'date');
              $query->set('order', 'DESC');
            } else if ($key === 'page') {
              $argument_string = sanitize_text_field($argument);
              $attrs = explode("_", $argument_string);
              $query->query_vars['posts_per_page'] = (intval($attrs[0]) * PERPAGE);
            } elseif ($key === 'gamas') {
              array_push(
                $new_args,
                array(
                  'taxonomy' => sanitize_text_field($key),
                  'field' => 'slug',
                  'terms' => $argument,
                  'operator' => 'IN',
                )
              );
            } else {
              $query->query_vars['posts_per_page'] = PERPAGE;
              $argument_string = sanitize_text_field($argument);
              $attrs = explode("_", $argument_string);
              array_push(
                $new_args,
                array(
                  'taxonomy' => 'pa_' . sanitize_text_field($key),
                  'field' => 'slug',
                  'terms' => $attrs,
                  'operator' => 'IN',
                )
              );
            }
          }
          if (count($new_args) > 0) {
            array_push($query->query_vars['tax_query'], $new_args);
          }
        }
      } else if ($query->is_search && $query->is_main_query()) {
        $query->set('post_type', 'product');
        $meta_query_args = array(
          'relation' => 'OR',
          array(
            'key' => 'pn',
            'value' => $query->get('s'),
            'compare' => 'LIKE',
          ),
          array(
            'key' => 'ean',
            'value' => $query->get('s'),
            'compare' => 'LIKE',
          ),
        );
        $query->set('meta_query', $meta_query_args);
      }
    }
  }
);

function rewrite_posts_clauses($clauses, $query)
{
  global $wpdb;
  if ($query->is_search) {
    $clauses["where"] = " AND wp_posts.post_type = 'product' " . $clauses["where"];
    $clauses['orderby'] = "wp_posts.post_date DESC";
  }
  return $clauses;
}
add_filter('posts_clauses', 'rewrite_posts_clauses', 10, 2);

// END SEARCH

function woocommerce_ajax_filter_products()
{
  $taxonomy = $_POST['taxonomy'];
  $searchParams = str_replace('?', '', $_POST['searchParams']);
  $params = explode("&", $searchParams);
  $arguments = array();
  $tempArgs = array();
  $new_args = array();
  $args = array(
    'post_type' => 'product',
    'post_status' => 'publish',
    'posts_per_page' => PERPAGE,
    'orderby' => 'menu_order title',
    'order' => 'ASC',
    'tax_query' => array(),
  );

  foreach ($params as $value) {
    if (strlen($value) === 0) {
      continue;
    }
    $splited = explode("=", $value);
    $arguments[$splited[0]] = explode("_", $splited[1]);
  }

  $found_key_page = array_key_exists('page', $arguments);

  if ($found_key_page === true) {
    $args['offset'] = (intval($arguments['page'][0]) * PERPAGE) - PERPAGE;

  }
  unset($arguments['page']);

  if ($taxonomy) {
    $args['product_cat'] = $taxonomy;
  }

  $found_key = array_key_exists('orderby', $arguments);
  $tempArgs = $arguments;
  unset($tempArgs['orderby']);

  if ($found_key === false) {
    $arguments['orderby'] = ['menu_order_title'];
  }

  if (count($tempArgs) > 0) {
    $args['tax_query'] = array("relation" => "AND");
  }

  foreach ($arguments as $key => $argument) {
    if ($key === 'orderby') {
      switch ($argument[0]) {
        case "date":
          $args['orderby'] = 'date';
          $args['order'] = 'DESC';
          break;
        case "price":
          $args['meta_key'] = '_price';
          $args['orderby'] = 'meta_value_num';
          $args['order'] = 'ASC';
          break;
        case "price-desc":
          $args['meta_key'] = '_price';
          $args['orderby'] = 'meta_value_num';
          $args['order'] = 'DESC';
          break;
        case "popularity":
          $args['meta_key'] = 'total_sales';
          $args['orderby'] = 'meta_value_num';
          $args['order'] = 'DESC';
          break;
        case "popularity":
          $args['orderby'] = 'menu_order title';
          $args['order'] = 'ASC';
          break;
        default:
          $args['orderby'] = 'menu_order title';
          $args['order'] = 'ASC';
      }
    } elseif ($key === 'gamas') {
      array_push(
        $new_args,
        array(
          'taxonomy' => sanitize_text_field($key),
          'field' => 'slug',
          'terms' => $argument,
          'operator' => 'IN',
        )
      );
    } else {
      array_push(
        $new_args,
        array(
          'taxonomy' => 'pa_' . sanitize_text_field($key),
          'field' => 'slug',
          'terms' => $argument,
          'operator' => 'IN',
        )
      );
    }
  }


  if (count($new_args) > 0) {
    array_push($args['tax_query'], $new_args);
  }
  $the_query = new WP_Query($args);

  ?>
  <div id="count">
    <?php echo $the_query->found_posts ?>
  </div>
  <?php
  woocommerce_product_loop_start();
  while ($the_query->have_posts()) {
    $the_query->the_post();
    do_action('woocommerce_shop_loop');
    wc_get_template_part('content', 'product');
  }
  woocommerce_product_loop_end();
  $args = array(
    'category' => $taxonomy,
    'tax_query' => $args['tax_query'],
    'total' => $the_query->max_num_pages,
    'current' => 1
  );
  wc_get_template('loop/pagination.php', $args);
  wp_die();
}

add_action('wp_ajax_woocommerce_ajax_filter_products', 'woocommerce_ajax_filter_products');
add_action('wp_ajax_nopriv_woocommerce_ajax_filter_products', 'woocommerce_ajax_filter_products');

function woocommerce_ajax_rerender_filters()
{
  global $wpdb;
  $icons = new Icons;
  $toIgnore = ['pa_opcoes-especiais', 'pa_opcoes', 'pa_programas', 'pa_acessorios-incluidos', 'pa_vitacontrol', 'pa_3-funcoes', 'pa_4-funcoes-especiais', 'pa_funcoes-especiais', 'pa_funcoes', 'pa_auto-off', 'pa_lavagem-por-sensores'];
  $taxonomy = $_POST['taxonomy'];
  $searchParams = str_replace('?', '', $_POST['searchParams']);
  $params = explode("&", $searchParams);
  $arguments = array();
  $tempArgs = array();
  $new_args = array();

  $args = array(
    'tax_query' => array(),
  );

  foreach ($params as $value) {
    if (strlen($value) === 0) {
      continue;
    }
    $splited = explode("=", $value);
    $arguments[$splited[0]] = explode("_", $splited[1]);
  }

  $found_key = array_key_exists('orderby', $arguments);
  $tempArgs = $arguments;
  unset($tempArgs['orderby']);

  if ($found_key === false) {
    $arguments['orderby'] = ['menu_order_title'];
  }

  if (count($tempArgs) > 0) {
    $args['tax_query'] = array("relation" => "AND");
  }

  foreach ($arguments as $key => $argument) {
    if ($key === 'orderby') {
      continue;
    } else {
      array_push(
        $new_args,
        array(
          'taxonomy' => 'pa_' . sanitize_text_field($key),
          'field' => 'slug',
          'terms' => $argument,
          'operator' => 'IN',
        )
      );
    }
  }


  $pageIndex = array_key_exists('orderby', $new_args);
  if ($pageIndex) {
    unset($new_args['orderby']);
  }

  if (count($new_args) > 0) {
    array_push($args['tax_query'], $new_args);
  }

  ?>
  <div class="overflow-auto">
    <?php
    $query_args = array(
      'status' => 'publish',
      'limit' => -1,
      'orderby' => 'menu_order title',
      'order' => 'ASC',
      'tax_query' => $args['tax_query']
    );

    if (isset($taxonomy) && strlen($taxonomy) > 0) {
      $query_args['category'] = array($taxonomy);
    }

    $data = array();



    if (count($new_args) > 0) {
      foreach (wc_get_products($query_args) as $product) {
        foreach ($product->get_attributes() as $tax => $attribute) {
          if (in_array($tax, $toIgnore)) {
            continue;
          }
          $attribute_obj = get_taxonomy($tax);
          $attribute_name = $attribute_obj->labels->singular_name;
          $data[$tax]['name'] = $attribute_obj->labels->singular_name;
          $data[$tax]['slug'] = $attribute_obj->name;
          foreach ($attribute->get_terms() as $term) {
            $term_obj = new \stdClass();
            $term_obj->name = $term->name;
            $term_obj->slug = $term->slug;
            $term_obj->term_id = $term->term_id;
            $term_obj->count = 1;
            if (array_key_exists($tax, $data)) {
              if (array_key_exists('terms', $data[$tax])) {
                if (array_key_exists($term->term_id, $data[$tax]['terms'])) {
                  $term_obj->count = $data[$tax]['terms'][$term->term_id]->count + 1;
                }
              }
            }
            $data[$tax]['terms'][$term->term_id] = $term_obj;
          }
        }
      }
    } else {
      $table_name = $wpdb->prefix . 'sorefoz_attributes_categories_lookup';
      $queryString = "";

      if (isset($taxonomy) && strlen($taxonomy) > 0) {
        $queryString = 'SELECT * FROM ' . $table_name . ' WHERE tax_slug = "' . $taxonomy . '" ORDER BY attr_name ASC;';
      } else {
        $queryString = 'SELECT * FROM ' . $table_name . ' ;';
      }

      $query = $wpdb->get_results($queryString);

      foreach ($query as $row) {
        $attribute = array(
          "name" => $row->attr_name,
          "slug" => $row->attr_slug,
          "terms" => []
        );
        $data[$row->attr_slug] = $attribute;
      }

      foreach ($data as $attribute) {
        foreach ($query as $row) {
          if ($attribute["slug"] !== $row->attr_slug) {
            continue;
          }
          $term = new \stdClass();
          $term->name = $row->term_name;
          $term->slug = $row->term_slug;
          $term->count = $row->product_count;
          $data[$row->attr_slug]["terms"][$row->term_slug] = $term;
        }
      }
    }

    ksort($data);


    if (count($new_args) > 0) {
      ?>
      <div class="mb-4 p-4">
        <ul>
          <?php
          foreach ($new_args as $arg) {
            if ($arg["terms"][0] === "orderby") {
              continue;
            }
            $slug = $arg["terms"][0];
            $foundField = array_filter(
              $data[$arg["taxonomy"]]['terms'],
              function ($field) use ($slug) {
                return $field->slug === $slug;
              }
            );
            $firstKey = array_key_first($foundField);
            ?>
            <li class="flex mb-2">
              <div
                class="clear-filter flex flex-row text-white bg-secondary py-1 px-2 rounded-full items-center space-x-1 cursor-pointer"
                data-value="<?php echo $arg['terms'][0] ?>" data-attribute="<?php echo $arg["taxonomy"] ?>"
                data-taxonomy="<?php echo isset($taxonomy) ? $taxonomy : '' ?>">
                <div class="pointer-events-none">
                  <?php echo $icons->get_icon('AiOutlineCloseCircle') ?>
                </div>
                <div class="pointer-events-none text-sm">
                  <?php echo $foundField[$firstKey]->name ?>
                </div>
              </div>
            </li>
          <?php } ?>
        </ul>
        <button class="clear-all-filters text-sm text-secondary"
          data-taxonomy="<?php echo isset($taxonomy) ? $taxonomy : '' ?>">Limpar tudo (<?php echo count($new_args) ?>)</button>
      </div>
    <?php
    }
    foreach ($data as $key => $attribute) { ?>
      <div class="mb-4 p-4">
        <h4 class="font-bold uppercase mb-4 border-b">
          <?php echo $attribute['name'] ?>
        </h4>
        <ul
          class="filters-box overflow-hidden transition-all duration-200 <?php echo count($attribute["terms"]) > 5 ? 'max-h-[160px]' : '' ?>">
          <?php
          // var_dump($attribute["terms"]);
          foreach ($attribute["terms"] as $term) { ?>
            <li class="flex items-center space-x-2 mb-2">
              <div class="relative w-6 h-6">
                <input type="checkbox" name="sidefilter" id="<?php echo $attribute['slug'] . '_' . $term->slug ?>"
                  value="<?php echo $term->slug ?>" data-attribute="<?php echo $attribute['slug'] ?>"
                  data-taxonomy="<?php echo isset($taxonomy) ? $taxonomy : '' ?>"
                  class="appearance-none border border-gray-400 rounded-none cursor-pointer w-6 h-6 checked:bg-secondary filter-checkbox"
                  <?php
                  $cleanSlug = str_replace('pa_', '', $attribute['slug']);
                  if (isset($arguments[$cleanSlug])) {
                    if (in_array($term->slug, $arguments[$cleanSlug])) {
                      echo 'checked="checked"';
                    }
                  }
                  ?>>
              </div>
              <label class="text-sm" for="<?php echo $attribute['slug'] . '_' . $term->slug ?>">
                <?php echo $term->name ?> (<?php echo $term->count ?>)
              </label>
            </li>
          <?php } ?>
        </ul>
      </div>
    <?php } ?>
  </div>
  <?php
  wp_die();
}

add_action('wp_ajax_woocommerce_ajax_rerender_filters', 'woocommerce_ajax_rerender_filters');
add_action('wp_ajax_nopriv_woocommerce_ajax_rerender_filters', 'woocommerce_ajax_rerender_filters');

add_action('wp_ajax_search_products', 'search_products');
add_action('wp_ajax_nopriv_search_products', 'search_products');

function search_products()
{
  global $wpdb;
  $keyword = $_POST["keyword"];

  $response = new \stdClass();
  $searchTerm = $wpdb->get_results($wpdb->prepare("SELECT * FROM `" . $wpdb->prefix . "terms` WHERE `name` LIKE '%s' LIMIT 1", '%' . $wpdb->esc_like($keyword) . '%'));

  if (count($searchTerm) > 0) {
    $post_list = get_posts(
      array(
        'post_type' => 'product',
        'orderby' => 'ID',
        'sort_order' => 'asc',
        'posts_per_page' => 6,
        'product_cat' => $searchTerm[0]->slug
      )
    );
  } else {
    $post_list = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT 
			ID, 
			post_title, 
			post_excerpt, 
			post_content,
			sku,
			pn,
			ean 
		FROM 
			(SELECT ID, 
				p.post_title, 
				p.post_excerpt, 
				p.post_content,
				m1.meta_value as sku,
				m2.meta_value as pn,
				m3.meta_value as ean,
				m4.meta_value as stock
				FROM " . $wpdb->prefix . "posts p, " . $wpdb->prefix . "postmeta m1, " . $wpdb->prefix . "postmeta m2," . $wpdb->prefix . "postmeta m3, " . $wpdb->prefix . "postmeta m4
				WHERE p.ID = m1.post_id and p.ID = m2.post_id and p.ID = m3.post_id and p.ID = m4.post_id
				AND m1.meta_key = '_sku'
				AND m2.meta_key = 'pn'
				AND m3.meta_key = 'ean'
				AND m4.meta_key = '_stock_status' AND m4.meta_value = 'instock'
				AND p.post_type = 'product'
			) 
		AS 
			produtos
		WHERE 
			post_title 
		LIKE 
			'%s' 
		OR 
			post_content 
		LIKE 
			'%s' 
		OR 
			post_excerpt 
		LIKE 
			'%s' 
		OR 
			sku 
		LIKE 
			'%s' 
		OR 
			pn 
		LIKE 
			'%s'
		OR 
			ean 
		LIKE 
			'%s'  
		ORDER BY ID DESC LIMIT 6;", '%' . $wpdb->esc_like($keyword) . '%', '%' . $wpdb->esc_like($keyword) . '%', '%' . $wpdb->esc_like($keyword) . '%', '%' . $wpdb->esc_like($keyword) . '%', '%' . $wpdb->esc_like($keyword) . '%', '%' . $wpdb->esc_like($keyword) . '%'
      )
    );
  }

  if (count($post_list) === 0) {
    $post_list = get_posts(
      array(
        'numberposts' => 6,
        'post_type' => 'product',
        'meta_query' => array(
          'relation' => 'OR',
          array(
            'key' => 'pn',
            'value' => $wpdb->esc_like($keyword),
            'compare' => 'LIKE',
          ),
          array(
            'key' => 'ean',
            'value' => $wpdb->esc_like($keyword),
            'compare' => 'LIKE',
          ),
        ),
      )
    );
  }
  $body = '';

  if (count($post_list) > 0) {
    $body .= '<ul>';
    foreach ($post_list as $item) {
      $link = get_permalink($item->ID);
      $image = get_the_post_thumbnail_url($item->ID);
      $body .= '<li>
			<a href="' . $link . '">
				<div class="product-search-box flex flex-row mb-4 items-center ">
					<img class="max-w-[5rem] mr-4" src="' . $image . '" />
					<h3 class="text-sm uppercase tracking-wide">' . $item->post_title . '</h3>
				</div>
			</a>
			</li>';
    }
    $body .= '</ul>';

  }

  $response->body = $body;
  $response->count = count($post_list);

  echo json_encode($response);
  die;
}

add_filter('woocommerce_checkout_fields', 'checkout_fields');
function checkout_fields($fields)
{
  foreach ($fields as &$fieldset) {
    foreach ($fieldset as &$field) {
      if (isset($field['autocomplete']) && $field['autocomplete'] === "address-line2") {
        $field['input_class'][] = 'w-full px-4 py-2 tracking-wide border text-sm rounded-none h-10 mt-4 lg:mt-6 ';
      } else if (isset($field['type']) && $field['type'] === "textarea") {
        $field['input_class'][] = 'w-full px-4 py-2 tracking-wide border text-sm rounded-none h-36 resize-none';
      } else {
        $field['input_class'][] = 'w-full px-4 py-2 tracking-wide border text-sm rounded-none h-10';
      }
      $field['label_class'][] = 'text-sm font-normal tracking-wide';
    }
  }
  return $fields;
}

add_filter('woocommerce_nif_field_required', '__return_true');

add_filter('woocommerce_terms_is_checked_default', '__return_true');


// Adds a custom rule type.
add_filter(
  'acf/location/rule_types',
  function ($choices) {
    $choices[__("Other", 'acf')]['wc_prod_attr'] = 'WC Product Attribute';
    return $choices;
  }
);

// Adds custom rule values.
add_filter(
  'acf/location/rule_values/wc_prod_attr',
  function ($choices) {
    foreach (wc_get_attribute_taxonomies() as $attr) {
      $pa_name = wc_attribute_taxonomy_name($attr->attribute_name);
      $choices[$pa_name] = $attr->attribute_label;
    }
    return $choices;
  }
);

// Matching the custom rule.
add_filter(
  'acf/location/rule_match/wc_prod_attr',
  function ($match, $rule, $options) {
    if (isset($options['taxonomy'])) {
      if ('==' === $rule['operator']) {
        $match = $rule['value'] === $options['taxonomy'];
      } elseif ('!=' === $rule['operator']) {
        $match = $rule['value'] !== $options['taxonomy'];
      }
    }
    return $match;
  },
  10,
  3
);


//            ATTRIBUTES FIELDS
function custom_add_wc_attribute_type()
{
  $id = isset($_GET['edit']) ? absint($_GET['edit']) : 0;
  $value = $id ? get_option("wc_attribute_type-$id") : '';
  $value_show = $id ? get_option("wc_attribute_show-$id") : '';
  ?>
  <div class="form-field">
    <label for="type-field">Type</label>
    <select name="type" id="type-field" value="<?php echo esc_attr($value); ?>">
      <option value="square">Square</option>
      <option value="radio">Radio</option>
      <option value="color">Color</option>
    </select>
    <p class="description">Set your attribute display style</p>
  </div>

  <div class="form-field">
    <label for="type-show">Show on frontend?</label>
    <input type="checkbox" id="type-show" name="show" checked>
    <p class="description">Chose if this filter appear on frontend.</p>
  </div>
<?php
}
add_action('woocommerce_after_add_attribute_fields', 'custom_add_wc_attribute_type');


function custom_edit_wc_attribute_type()
{
  $id = isset($_GET['edit']) ? absint($_GET['edit']) : 0;
  $value = $id ? get_option("wc_attribute_type-$id") : '';
  $value_show = $id ? get_option("wc_attribute_show-$id") : '';
  ?>
  <tr class="form-field">
    <th scope="row" valign="top">
      <label for="type-field">Type</label>
    </th>
    <td>
      <select name="type" id="type-field" value="<?php echo esc_attr($value); ?>">
        <option value="square" <?php echo esc_attr($value) === "square" ? "selected" : "" ?>>Square</option>
        <option value="radio" <?php echo esc_attr($value) === "radio" ? "selected" : "" ?>>Radio</option>
        <option value="color" <?php echo esc_attr($value) === "color" ? "selected" : "" ?>>Color</option>
      </select>
      <p class="description">Set your attribute display style</p>
    </td>
  </tr>

  <tr class="form-field">
    <th scope="row" valign="top">
      <label for="type-show">Show</label>
    </th>
    <td>
      <input type="checkbox" id="type-show" name="show" <?php echo esc_attr($value_show) ? "checked" : "" ?>>
      <p class="description">Chose if this filter appear on frontend.</p>
    </td>
  </tr>
<?php
}

add_action('woocommerce_after_edit_attribute_fields', 'custom_edit_wc_attribute_type');

function my_save_wc_attribute_type($id)
{
  if (is_admin() && isset($_POST['type'])) {
    $option = "wc_attribute_type-$id";
    $option_show = "wc_attribute_show-$id";
    update_option($option, sanitize_text_field($_POST['type']));
    update_option($option_show, sanitize_text_field($_POST['show']));

  }
}
add_action('woocommerce_attribute_added', 'my_save_wc_attribute_type');
add_action('woocommerce_attribute_updated', 'my_save_wc_attribute_type');

add_action(
  'woocommerce_attribute_deleted',
  function ($id) {
    delete_option("wc_attribute_type-$id");
    delete_option("wc_attribute_show-$id");
  }
);

function get_variable_sale_percentage($product)
{
  //get variables
  $variation_min_regular_price = $product->get_variation_regular_price('min', true);
  $variation_max_regular_price = $product->get_variation_regular_price('max', true);
  $variation_min_sale_price = $product->get_variation_sale_price('min', true);
  $variation_max_sale_price = $product->get_variation_sale_price('max', true);

  //get highest and lowest percentages
  $lower_percentage = round((($variation_min_regular_price - $variation_min_sale_price) / $variation_min_regular_price) * 100);
  $higher_percentage = round((($variation_max_regular_price - $variation_max_sale_price) / $variation_max_regular_price) * 100);

  //sort array
  $percentages = array($lower_percentage, $higher_percentage);
  sort($percentages);

  if ($percentages[0] != $percentages[1] && $percentages[0]) {
    return $percentages[0] . '% - ' . $percentages[1] . '%';
  } else {
    return $percentages[1];
  }
}
add_filter('get_variable_sale_percentage', 'get_variable_sale_percentage');

remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');

function wlb_display_order_data_in_admin( $order ){ ?>
  <div class="order_data_column" style="width:100%">
      <h3><?php _e( 'Shipment Tracking', 'woocommerce' ); ?><a href="#" class="edit_address"><?php _e( 'Edit', 'woocommerce' ); ?></a></h3>
      <div class="address">
      <?php
          echo '<p><strong>' . __( 'Shipping Company' ) . ':</strong>' . get_post_meta( $order->id, '_wlb_shipping_company', true ) . '</p>'; 
          echo '<p><strong>' . __( 'Tracking Code' ) . ':</strong>' . get_post_meta( $order->id, '_wlb_tracking_code', true ) . '</p>';
          ?>
      </div>
      <div class="edit_address">
      <?php
          woocommerce_wp_select( array( // Text Field type
            'id'          => '_wlb_shipping_company',
            'label'       => __( 'Shipping Company', 'woocommerce' ),
            'options'     => array(
                ''        => __( 'Select Shipping Company', 'woocommerce' ),
                'mrw'        => __( 'MRW', 'woocommerce' ),
                'correos_express'    => __('Correos Express', 'woocommerce' ),
                'ctt' => __('CTT', 'woocommerce' ),
            ),
            'wrapper_class' => '_billing_company_field'
        ) );?>
          <?php woocommerce_wp_text_input( array( 'id' => '_wlb_tracking_code', 'label' => __( 'Tracking Code' ), 'wrapper_class' => '_billing_company_field' ) ); ?>
      </div>
      
  </div>
<?php }
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'wlb_display_order_data_in_admin' );

function wlb_save_extra_details( $post_id, $post ){
  update_post_meta( $post_id, '_wlb_shipping_company', wc_clean( $_POST[ '_wlb_shipping_company' ] ) );
  update_post_meta( $post_id, '_wlb_tracking_code', wc_clean( $_POST[ '_wlb_tracking_code' ] ) );
}
add_action( 'woocommerce_before_save_order_items', 'wlb_save_extra_details', 45, 2 );
