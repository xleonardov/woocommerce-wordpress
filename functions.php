<?php
require get_template_directory() . '/includes/nav-menu-tree.php';
require get_template_directory() . '/includes/icons.php';
require get_template_directory() . '/includes/theme-setup.php';
// require get_template_directory() . '/includes/theme-blocks.php';
// require get_template_directory() . '/blocks/register-blocks.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . "wp-config.php";
require_once ABSPATH . "wp-includes/wp-db.php";
add_filter('wpgs_show_featured_image_in_gallery', '__return_false', 20);
add_action(
    'rest_api_init',
    function () {
        register_rest_route(
            'produtos',
            '/backdot-sync',
            array(
                'methods' => 'post',
                'callback' => 'backdot_migrate_rest',
                'permission_callback' => '__return_true',
            )
        );
        register_rest_route(
            'produtos',
            '/sync-related',
            array(
                'methods' => 'post',
                'callback' => 'set_upsells_and_cross_sells',
                'permission_callback' => '__return_true',
            )
        );
        register_rest_route(
            'produtos',
            '/categories-sync',
            array(
                'methods' => 'post',
                'callback' => 'sync_categories',
                'permission_callback' => '__return_true',
            )
        );
        register_rest_route(
            'produtos',
            '/create-attributes',
            array(
                'methods' => 'post',
                'callback' => 'create_attributes',
                'permission_callback' => '__return_true',
            )
        );
    }
);



function get_products()
{
    $url = 'https://pacto-backdot-backend-bybbp.ondigitalocean.app/products/store/find?_limit=-1&_sort=created_at:ASC';

    $headers = array(
        "Content-Type" => "application/json; charset=utf-8"
    );
    $pload = array(
        'method' => 'GET',
        'timeout' => 30,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => $headers,
        'cookies' => array()
    );
    $response = wp_remote_post($url, $pload);
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo "Something went wrong: $error_message";
        return null;
    } else {
        return json_decode($response['body']);
    }
}

function get_categories_pacto()
{
    $url = 'https://pacto-backdot-backend-bybbp.ondigitalocean.app/categories/mount';

    $headers = array(
        "Content-Type" => "application/json; charset=utf-8"
    );
    $pload = array(
        'method' => 'GET',
        'timeout' => 30,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => $headers,
        'cookies' => array()
    );
    $response = wp_remote_post($url, $pload);
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo "Something went wrong: $error_message";
        return null;
    } else {
        return json_decode($response['body']);
    }
}

function mount_categories($term, $finalResults)
{
    $category = get_term_by('id', $term, 'product_cat');
    if ($category) {
        if ($category->parent) {
            $finalResults = mount_categories($category->parent, $finalResults);
        }
        array_push($finalResults, $category->term_id);
        return $finalResults;
    }
}

function uploadImage($link, $name)
{
    $name = str_replace(' ', '-', $name);
    $name = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $name));
    // $tmp = explode('/', getimagesize($link)['mime']);
    // $imagetype = end($tmp);
    // $filename = $name . '.' . $imagetype;
    $temp_extention = strrev($link);
    $explode_extention = explode(".", $temp_extention);
    $final_extention = strrev($explode_extention[0]);
    $filename = $name . '.' . $final_extention;

    $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),
    );
    $contents = file_get_contents($link, false, stream_context_create($arrContextOptions));

    $uploaddir = wp_upload_dir();
    $uploadfile = $uploaddir['path'] . '/' . $filename;
    if ($contents) {
        $wp_filetype = wp_check_filetype(basename($filename), null);
        $savefile = fopen($uploadfile, 'w');
        fwrite($savefile, $contents);
        fclose($savefile);

        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => $filename,
            'post_content' => '',
            'post_status' => 'inherit'
        );

        $attach_id = wp_insert_attachment($attachment, $uploadfile);
        $imagenew = get_post($attach_id);
        $fullsizepath = get_attached_file($imagenew->ID);
        $attach_data = wp_generate_attachment_metadata($attach_id, $fullsizepath);
        wp_update_attachment_metadata($attach_id, $attach_data);

        return $attach_id;
    } else {
        return null;
    }
}

function backdot_migrate_rest($request)
{
    global $wpdb;
    $products = get_products();
    foreach ($products as $product) {

        $featuredImage = "";
        $product_gallery = [];
        if ($product->featured_image && strlen(trim($product->featured_image->url)) > 3) {
            $featuredImage = uploadImage($product->featured_image->url, $product->name);
        }
        if ($product->gallery && count($product->gallery) > 0) {
            foreach ($product->gallery as $key => $gallery) {
                array_push($product_gallery, uploadImage($gallery->url, $product->name . ' ' . $key));
            }
        }


        $attributes = array();
        if (count($product->selected_attributes) > 0) {
            foreach ($product->selected_attributes as $selected_attribute) {
                if (isset($selected_attribute->config) && count($selected_attribute->config->terms) > 0) {
                    foreach ($selected_attribute->config->terms as $term) {
                        $keyValue = new \stdClass();
                        $keyValue->key = $selected_attribute->name;
                        $keyValue->value = $term->name;
                        $keyValue->slug = sanitize_title($selected_attribute->name);
                        $keyValue->is_variation = 1;
                        array_push($attributes, $keyValue);
                    }
                }
            }
        }
        if (count($product->colors) > 0) {
            foreach ($product->colors as $color) {
                $keyValue = new \stdClass();
                $keyValue->key = "Cor";
                $keyValue->value = $color->name;
                $keyValue->slug = sanitize_title("Cor");
                $keyValue->is_variation = 0;
                array_push($attributes, $keyValue);
            }
        }

        $categories = array();

        if (count($product->categories) > 0) {
            foreach ($product->categories as $cat) {
                $category = get_term_by('slug', str_replace('-pt', '', $cat->slug), 'product_cat');
                if ($category) {
                    array_push($categories, $category->term_id);
                }

            }
        }
        $gamas = array();
        if (count($product->tags) > 0) {
            foreach ($product->tags as $tag) {
                $gama = term_exists($tag->name, 'gamas');
                if (!$gama) {
                    $gama = wp_insert_term($tag->name, 'gamas');
                }
                array_push($gamas, $gama);
            }
        }

        $newProductId = 0;
        if (count($product->sellable_items) > 1) {
            $newProduct = new WC_Product_Variable();

            $newProduct->set_name($product->name);
            $newProduct->set_description($product->short_description);
            $newProduct->set_short_description($product->description);
            if ($featuredImage) {
                $newProduct->set_image_id($featuredImage);
            }
            if (count($product_gallery) > 0) {
                $newProduct->set_gallery_image_ids($product_gallery);
            }

            $newProduct->set_category_ids($categories);
            $newProduct->save();
            $newProduct->set_sku($product->sku);
            $newProductId = $newProduct->get_id();
            update_post_meta($newProductId, '_sku', $product->sku);

            insert_product_attributes($newProductId, $attributes);

            foreach ($product->sellable_items as $sl) {
                $variation = new WC_Product_Variation();
                $variation->set_parent_id($newProductId);
                if (count($sl->config) > 0) {
                    $term = get_term_by('name', $sl->config[0]->attribute_value, 'pa_' . $sl->config[0]->attribute_slug);
                    if (isset($term->slug) && $term->slug) {
                        $variation->set_attributes(array('pa_' . $sl->config[0]->attribute_slug => $term->slug));
                    }
                }
                $variation->set_image_id($featuredImage);
                $variation->set_manage_stock(true);
                $variation->set_stock_quantity($sl->stock);
                $variation->set_backorders('yes'); // 'no', 'notify'
                $variation->set_low_stock_amount(2);
                $variation->set_weight($sl->weight);
                $variation->set_length($sl->depth);
                $variation->set_width($sl->width);
                $variation->set_height($sl->height);
                $variation->set_regular_price($sl->price_with_taxes);
                $variation->set_sale_price($sl->sale_price_with_taxes);
                $variation->save();
                $variId = $variation->get_id();
                update_post_meta($variId, 'wcwp_wholesale', $sl->btb_price);
            }
        } else {
            $newProduct = new WC_Product_Simple();
            $newProduct->set_name($product->name);
            $newProduct->set_description($product->short_description);
            $newProduct->set_short_description($product->description);
            if ($featuredImage) {
                $newProduct->set_image_id($featuredImage);
            }
            if (count($product_gallery) > 0) {
                $newProduct->set_gallery_image_ids($product_gallery);
            }
            $newProduct->set_category_ids($categories);
            $newProduct->set_sku($product->sku);
            $newProduct->set_regular_price($product->sellable_items[0]->price_with_taxes);
            $newProduct->set_sale_price($product->sellable_items[0]->sale_price_with_taxes);
            $newProduct->set_stock_quantity($product->sellable_items[0]->stock);
            $newProduct->set_sku($product->sku);
            $newProduct->set_weight($product->sellable_items[0]->weight);
            $newProduct->set_length($product->sellable_items[0]->depth);
            $newProduct->set_width($product->sellable_items[0]->width);
            $newProduct->set_height($product->sellable_items[0]->height);
            $newProduct->save();
            $newProductId = $newProduct->get_id();
            update_post_meta($newProductId, 'wcwp_wholesale', $product->sellable_items[0]->btb_price);
            update_post_meta($newProductId, '_sku', $product->sku);
            insert_product_attributes($newProductId, $attributes);

        }



        if (isset($product->composition_and_care) && count($product->composition_and_care) > 0) {
            $care = array();
            foreach ($product->composition_and_care as $composition) {
                array_push($care, $composition->text);
            }
            update_field('care', $care, $newProductId);
        }

        if (isset($product->collection_gallery) && count($product->collection_gallery) > 0) {
            $galleryCollection = array();
            foreach ($product->collection_gallery as $photo) {
                $img = uploadImage($photo->url, $photo->hash);
                array_push($galleryCollection, $img);
            }
            update_field('collection_gallery', $galleryCollection, $newProductId);
        }




        if (count($gamas) > 0) {
            foreach ($gamas as $gama) {
                if ($gama) {
                    wp_set_object_terms($newProductId, array(intval($gama['term_id'])), 'gamas');
                }
            }
        }
    }

    $response = new WP_REST_Response("Done! 👍");
    $response->set_status(200);

    return rest_ensure_response($response);
}

function insert_product_attributes($product_id, $attributes)
{
    $product_attributes_data = array();
    foreach ($attributes as $attribute) {
        $values = array();
        $createdAttributes = array_keys(wc_get_attribute_taxonomy_labels());
        if (!in_array($attribute->slug, $createdAttributes)) {
            $taxonomyName = wc_attribute_taxonomy_name($attribute->key);
            if ($attribute->key) {
                unregister_taxonomy($taxonomyName);
                $args = array(
                    'name' => $attribute->key,
                    'slug' => $attribute->slug,
                    'orderby' => 'name',
                );
                $attr_id = wc_create_attribute($args);

                register_taxonomy(
                    $taxonomyName,
                    apply_filters('woocommerce_taxonomy_objects_' . $taxonomyName, array('product')),
                    apply_filters(
                        'woocommerce_taxonomy_args_' . $taxonomyName,
                        array(
                            'labels' => array(
                                'name' => $attribute->slug,
                            ),
                            'hierarchical' => false,
                            'show_ui' => false,
                            'query_var' => true,
                            'rewrite' => false,
                        )
                    )
                );
                $result = term_exists($attribute->value, 'pa_' . $attribute->slug);
                if (!$result) {
                    $result = wp_insert_term($attribute->value, 'pa_' . $attribute->slug);
                }
            }
        } else {
            wp_insert_term($attribute->value, 'pa_' . $attribute->slug);
        }

        $values[] = $attribute->value;
        wp_set_object_terms($product_id, $values, 'pa_' . $attribute->slug, true);

        $product_attributes_data['pa_' . $attribute->slug] = array(
            'name' => 'pa_' . $attribute->slug,
            'value' => $attribute->value,
            'is_visible' => 1,
            'is_variation' => $attribute->is_variation,
            'is_taxonomy' => 1
        );
    }
    update_post_meta($product_id, '_product_attributes', $product_attributes_data);
}

function sync_categories($response)
{
    $categories = get_categories_pacto();

    foreach ($categories->mountedCats as $cat) {
        $createdCat = wp_insert_term($cat->name, 'product_cat', array('slug' => str_replace('-pt', '', $cat->slug)));
        if (isset($cat->childs) && count($cat->childs) > 0) {
            foreach ($cat->childs as $child) {
                wp_insert_term(
                    $child->name,
                    'product_cat',
                    array(
                        'slug' => str_replace('-pt', '', $child->slug),
                        'parent' => $createdCat['term_id'],
                    )
                );
            }
        }
    }

    $response = new WP_REST_Response($categories->mountedCats);
    $response->set_status(200);

    return rest_ensure_response($response);

}

function create_attributes($response)
{
    global $wpdb;
    $products = get_products();
    $attributes = array();

    foreach ($products as $product) {
        if (count($product->selected_attributes) > 0) {
            foreach ($product->selected_attributes as $selected_attribute) {
                if (isset($selected_attribute->config) && count($selected_attribute->config->terms) > 0) {
                    foreach ($selected_attribute->config->terms as $term) {
                        $keyValue = new \stdClass();
                        $keyValue->key = $selected_attribute->name;
                        $keyValue->value = $term->name;
                        $keyValue->slug = sanitize_title($selected_attribute->name);
                        $keyValue->is_variation = 1;
                        array_push($attributes, $keyValue);
                    }
                }
            }
        }
    }
    foreach ($attributes as $attribute) {
        $createdAttributes = array_keys(wc_get_attribute_taxonomy_labels());
        if (!in_array($attribute->slug, $createdAttributes)) {
            $taxonomyName = wc_attribute_taxonomy_name($attribute->key);
            if ($attribute->key) {
                unregister_taxonomy($taxonomyName);
                $args = array(
                    'name' => $attribute->key,
                    'slug' => $attribute->slug,
                    'orderby' => 'name',
                );
                $attr_id = wc_create_attribute($args);

                register_taxonomy(
                    $taxonomyName,
                    apply_filters('woocommerce_taxonomy_objects_' . $taxonomyName, array('product')),
                    apply_filters(
                        'woocommerce_taxonomy_args_' . $taxonomyName,
                        array(
                            'labels' => array(
                                'name' => $attribute->slug,
                            ),
                            'hierarchical' => false,
                            'show_ui' => false,
                            'query_var' => true,
                            'rewrite' => false,
                        )
                    )
                );
                $result = term_exists($attribute->value, 'pa_' . $attribute->slug);
                if (!$result) {
                    $result = wp_insert_term($attribute->value, 'pa_' . $attribute->slug);
                }
            }
        } else {
            wp_insert_term($attribute->value, 'pa_' . $attribute->slug);
        }
    }

    $response = new WP_REST_Response("done");
    $response->set_status(200);

    return rest_ensure_response($response);

}

function set_upsells_and_cross_sells($response)
{
    global $wpdb;
    $products = get_products();

    foreach ($products as $product) {
        $prd_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $product->sku));
        if ($prd_id) {
            $product_id = intval($prd_id);
        } else {
            $product_id = 0;
        }

        if ($product_id !== 0) {
            $upsells = array();
            if (count($product->color_variants) > 0) {
                foreach ($product->color_variants as $variant) {
                    $prd_vari_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $variant->sku));
                    if ($prd_vari_id) {
                        $vari_id = intval($prd_vari_id);
                    } else {
                        $vari_id = 0;
                    }
                    if ($vari_id !== 0) {
                        array_push($upsells, $vari_id);
                    }
                }
            }

            $cross_sells = array();

            if (count($product->related_products) > 0) {
                foreach ($product->related_products as $variant) {
                    $prd_vari_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $variant->sku));
                    if ($prd_vari_id) {
                        $vari_id = intval($prd_vari_id);
                    } else {
                        $vari_id = 0;
                    }
                    if ($vari_id !== 0) {
                        array_push($cross_sells, $vari_id);
                    }
                }
            }
            update_post_meta($product_id, '_upsell_ids', $upsells);
            update_post_meta($product_id, '_crosssell_ids', $cross_sells);
        } else {
            continue;
        }

    }

    $response = new WP_REST_Response("Done! 👍");
    $response->set_status(200);

    return rest_ensure_response($response);
}

function getRequestHeaders()
{
    $headers = array();
    foreach ($_SERVER as $key => $value) {
        if (substr($key, 0, 5) <> 'HTTP_') {
            continue;
        }
        $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
        $headers[$header] = $value;
    }
    return $headers;
}

add_action('rest_api_init', 'email_route');
function email_route()
{
    register_rest_route(
        'personalizados',
        'send-email',
        array(
            'methods' => 'POST',
            'callback' => 'sendEmailPersonalizados'
        )
    );
}

function sendEmailPersonalizados($request)
{
    $accessToken = 'Bearer webaniceday';
    $auth = getRequestHeaders();
    if ($auth['Authorization']) {
        if ($auth['Authorization'] == $accessToken) {
            $data = $request->get_params();
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $body = '<head>
                <link rel="preconnect" href="https://fonts.googleapis.com" />
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
                <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet" />
              </head>
              
              <!--[if mso]>
              <style type=”text/css”>
              .body-text {
              font-family: Arial, sans-serif;
              }
              </style>
              <![endif]-->
              
              
                <div style="direction: ltr">
                  <div style="direction: ltr">
                    <div style="direction: ltr; margin: 0px auto; max-width: 640px; width: 100%;">
                      <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
                        style="direction: ltr; width: 100%" width="100%">
                        <tbody style="direction: ltr">
                          <tr style="direction: ltr">
                            <td style="direction: ltr; font-size: 0px; padding: 0 4%; text-align: center; vertical-align: top;"
                              align="center" valign="top">
                              <div
                                style="font-size: 13px; text-align: left; direction: ltr; display: inline-block; vertical-align: top;width: 100%;">
                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"
                                  style="direction: ltr; max-width: 640px;">
                                  <tbody style="direction: ltr">
                                    <tr style="direction: ltr">
                                      <td style="direction: ltr; vertical-align: top; padding: 0" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                                          style="direction: ltr; max-width: 640px;" width="100%">
                                          <tbody>
                                            <tr style="direction: ltr">
                                              <td align="center" style="padding-top: 20px; padding-bottom: 0px;">
                                                <div style="text-align:right; width: 32px; height: 59px;">
                                                  <img height="auto"
                                                    src="https://i.imgur.com/Xr8r4Yq.png"
                                                    style="direction: ltr; border: 0; display: block; outline: none; text-decoration: none; height: auto; width: 100%;"
                                                    width="100" class="CToWUd" />
                                                </div>
                                              </td>
                                            </tr>
                                            <tr style="direction: ltr">
                                              <td align="center" style="
                                                direction: ltr;
                                                font-size: 0px;
                                                padding: 0;
                                                padding-top: 0px;
                                                padding-bottom: 0px;
                                                word-break: break-word;
                                                padding-left: 0px;
                                                padding-right: 0px;
                                              ">
                                                <div style="
                                                  width: 100%;
                                                  direction: ltr;
                                                  border-collapse: collapse;
                                                  border-spacing: 0px;
                                                  padding-left: 0px;
                                                  padding-right: 0px;
                                                ">
                                                  <div style="
                                                    width: 100%;
                                                    direction: ltr;
                                                    border-collapse: collapse;
                                                    border-spacing: 0px;
                                                    padding-left: 0px;
                                                    padding-top: 0px;
                                                    padding-bottom: 26px;
                                                    padding-right: 0px;
                                                    border-radius: 4px;
                                                  ">
                                                    <table align="start" border="0" cellpadding="0" cellspacing="0"
                                                      role="presentation" style="
                                                      direction: ltr;
                                                      border-collapse: collapse;
                                                      border-spacing: 0px;
                                                    ">
                                                      <tbody style="direction: ltr">
                                                        <tr style="direction: ltr">
                                                          <td style="direction: ltr">
                                                            <div style="
                                                              direction: ltr;
                                                              font-weight: 500;
                                                              font-family: \'Inter\', Arial,
                                                                sans-serif;
                                                              text-align: start;
                                                              color: #333333;
                                                              font-size: 18px;
                                                              font-weight: 500;
                                                              line-height: 20px;
                                                              margin-top: 0px;
                                                              padding-bottom: 16px;
                                                              text-align: center;
                                                            ">
                                                              Novo pedido de personalização.
                                                            </div>
                                                          </td>
                                                        </tr>
                                                      </tbody>
                                                    </table>
                                                  </div>
                                                </div>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td style="padding-top: 26px;padding-bottom: 42px;">
                                              <div
                                              style="direction: ltr; font-weight: 400; font-family: \'Inter\', Arial, sans-serif; text-align: left; color: #000; font-size: 10px; line-height: 12px; margin-top: 0px;">
                                              Nome:
                                            </div>
                                            <div
                                              style="direction: ltr; font-weight: 400;font-family: \'Inter\', Arial, sans-serif;text-align: left;color: #111827;font-size: 16px;line-height: 28px;margin-top: 0px; padding-bottom:8px">
                                              ' . $data['nome'] . '
                                            </div>
              
                                            <div
                                              style="direction: ltr; font-weight: 400; font-family: \'Inter\', Arial, sans-serif; text-align: left; color: #000; font-size: 10px; line-height: 12px; margin-top: 0px;">
                                              Email:
                                            </div>
                                            <div
                                              style="direction: ltr; font-weight: 400;font-family: \'Inter\', Arial, sans-serif;text-align: left;color: #111827;font-size: 16px;line-height: 28px;margin-top: 0px; padding-bottom:8px">
                                              ' . $data['email'] . '
                                            </div>';
            if ($data['observacoes']) {
                $body .= ' <div
                                                style="direction: ltr; font-weight: 400; font-family: \'Inter\', Arial, sans-serif; text-align: left; color: #000; font-size: 10px; line-height: 12px; margin-top: 0px;">
                                                Observações:
                                              </div>
                                              <div
                                                style="direction: ltr; font-weight: 400;font-family: \'Inter\', Arial, sans-serif;text-align: left;color: #111827;font-size: 16px;line-height: 28px;margin-top: 0px; padding-bottom:8px">
                                                ' . $data['observacoes'] . '
                                              </div>';
            }

            $body .= '
                                              <div
                                              style="direction: ltr; font-weight: 400; font-family: \'Inter\', Arial, sans-serif; text-align: left; color: #000; font-size: 12px; line-height: 12px; margin-top: 0px;">
                                              Artigos:
                                            </div>
                                            
                                            <table style="border-collapse: separate; border-spacing: 0px 8px; padding-top:16px; overflow:auto;direction: ltr; max-width: 640px;" width="100%">
                                            <thead>
                                            <tr>
                                              <th style="text-align:left; font-family: \'Inter\', Arial, sans-serif; font-weight: 400; padding-left:8px; padding-right: 8px; font-size: 12px;">
                                                Modalidade
                                              </th>
                                              <th style="text-align:left; font-family: \'Inter\', Arial, sans-serif; font-weight: 400; padding-left:8px; padding-right: 8px; font-size: 12px;">
                                                Categoria
                                              </th>
                                              <th style="text-align:left; font-family: \'Inter\', Arial, sans-serif; font-weight: 400; padding-left:8px; padding-right: 8px; font-size: 12px;">
                                                Género
                                              </th>
                                              <th style="text-align:left; font-family: \'Inter\', Arial, sans-serif; font-weight: 400; padding-left:8px; padding-right: 8px; font-size: 12px;">
                                                Produto
                                              </th>
                                              <th style="text-align:left; font-family: \'Inter\', Arial, sans-serif; font-weight: 400; padding-left:8px; padding-right: 8px; font-size: 12px;">
                                                Quantidade
                                              </th>
                                            </tr>
                                            </thead>
          <tbody>';
            foreach ($data['items'] as $key => $item) {
                $body .= '
                        <tr>
                        <td style="background-color:#000; text-align:left; font-family: \'Inter\', Arial, sans-serif; font-weight: 400; padding:8px; font-size: 12px; color:#fff; border: 1px solid #000;  ">';
                if ($item['modalidade']) {
                    $body .= $item['modalidade']['nome'];
                } else {
                    $body .= '-';
                }
                $body .= '
                        </td>

                        <td style=" text-align:left; font-family: \'Inter\', Arial, sans-serif; font-weight: 700; padding:8px; font-size: 12px; color:#000; border-top: 1px solid #BFBFBF;  border-bottom: 1px solid #BFBFBF;">
                        ';
                if ($item['gama']) {
                    $body .= $item['gama']['nome'];
                } else {
                    $body .= '-';
                }
                $body .= '
                        </td>

                        <td style=" text-align:left; font-family: \'Inter\', Arial, sans-serif; font-weight: 400; padding:8px; font-size: 12px; color:#000; border-top: 1px solid #BFBFBF;  border-bottom: 1px solid #BFBFBF;  ">
                        ';

                if ($item['tipo']) {
                    $body .= $item['tipo'];
                } else {
                    $body .= '-';
                }

                $body .= '
                        </td>

                        <td style=" text-align:left; font-family: \'Inter\', Arial, sans-serif; font-weight: 400; padding:8px; font-size: 12px; color:#000; border-top: 1px solid #BFBFBF;  border-bottom: 1px solid #BFBFBF;  ">
                        ';
                if ($item['produto']) {
                    $body .= $item['produto']['nome'];
                } else {
                    $body .= '-';
                }
                $body .= '
                        </td>

                        <td style=" text-align:left; font-family: \'Inter\', Arial, sans-serif; font-weight: 400; padding:8px; font-size: 12px; color:#000; border-top: 1px solid #BFBFBF;  border-bottom: 1px solid #BFBFBF; border-right: 1px solid #BFBFBF;  ">
                        ';
                if ($item['produto']) {
                    $body .= $item['produto']['quantidade'];
                } else {
                    $body .= '-';
                }


                $body .= '
                        </td>
                        </tr>
                        ';
            }

            $body .= '</tbody>
          </table>

          </td>
        </tr>

        <tr style="direction: ltr">
          <td align="center" style="
            direction: ltr;
            font-size: 0px;
            padding: 0;
            padding-top: 10px;
            padding-bottom: 10px;
            word-break: break-word;
          ">
            <div>
              <a href="https://pacto.cc" target="_blank" style="text-decoration: none">
                <div style="text-align:right; width: 96px; height: 15px;">
                <img height="auto"
                  src="https://i.imgur.com/D4FXC5T.png"
                  style="direction: ltr; border: 0; display: block; outline: none; text-decoration: none; height: auto; width: 100%;"
                  width="100" class="CToWUd" />
                </div>
              </a>
            </div>
          </td>
        </tr>
        <tr style="direction: ltr">
          <td align="center" style="padding-top: 20px; padding-bottom: 20px;">
            <div style="
            direction: ltr;
            font-weight: 400;
            font-family: \'Inter\', Arial,
              sans-serif;
            text-align: center;
            color: #333333;
            font-size: 8px;
            line-height: 12px;
            margin-top: 20px;
            padding-top: 24px;
          ">
              Developed by <a href="https://willbe.co"
                style="text-decoration: underline; color: #333333;">Willbe
                Collective</a>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>';

            wp_mail('carvalho@willbecollective.pt', 'Pacto.cc - Produtos Personalizados - ' . $data['nome'], $body, $headers);

            $response = new WP_REST_Response('Email enviado 👍');
            $response->set_status(200);
        } else {
            $response = new WP_REST_Response('Forbiden');
            $response->set_status(403);
        }
    } else {
        $response = new WP_REST_Response('No token provided');
        $response->set_status(403);
    }
    return rest_ensure_response($response);
}

function register_personalizados()
{
    wp_register_script('app-personalizados', get_stylesheet_directory_uri() . '/assets/personalizados/js/main.276b05ae.js', array(), null, true);
    wp_register_style('style-personalizados', get_stylesheet_directory_uri() . '/assets/personalizados/css/main.fb2facf8.css', array(), null, '');
}

add_action('wp_enqueue_scripts', 'register_personalizados');

function willbe_price_personalizados_shortcode()
{
    $body = '<noscript>You need to enable JavaScript to run this app.</noscript><div id="root"></div>';
    return $body;
}

add_shortcode('personalizados', 'willbe_price_personalizados_shortcode');


add_filter('wc_product_has_unique_sku', '__return_false', PHP_INT_MAX);

add_action('wp_ajax_nopriv_subscribe_to_egoi_newsletter', 'subscribe_to_egoi_newsletter');
add_action('wp_ajax_subscribe_to_egoi_newsletter', 'subscribe_to_egoi_newsletter');

function subscribe_to_egoi_newsletter()
{
    $list_id = 1;
    $api_key = "";

    $data = json_decode(stripslashes($_POST['data']));
    $data = json_encode($data);
    $curl = curl_init();

    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => "https://api.egoiapp.com/lists/" . $list_id . "/contacts",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "Apikey: " . $api_key,
                "Content-Type: application/json"
            ),
        )
    );
    $response = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    echo $status;
    die;
}