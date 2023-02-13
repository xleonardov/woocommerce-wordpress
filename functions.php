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
    'rest_api_init', function () {
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
    $filename = $name . '.'. $final_extention;

    $arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
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

// function uploadImage($link, $name)
// {
//     $name = str_replace(' ', '-', $name);
//     $name = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $name));
//     $tmp = explode('/', getimagesize($link)['mime']);
//     $imagetype = end($tmp);
//     $filename = $name . '.' . $imagetype;
//
//     $uploaddir = wp_upload_dir();
//     $uploadfile = $uploaddir['path'] . '/' . $filename;
//     $contents = file_get_contents($link);
//     if ($contents) {
//         $savefile = fopen($uploadfile, 'w');
//         fwrite($savefile, $contents);
//         fclose($savefile);
//
//         $wp_filetype = wp_check_filetype(basename($filename), null);
//         $attachment = array(
//         'post_mime_type' => $wp_filetype['type'],
//         'post_title' => $filename,
//         'post_content' => '',
//         'post_status' => 'inherit'
//         );
//
//         $attach_id = wp_insert_attachment($attachment, $uploadfile);
//         $imagenew = get_post($attach_id);
//         $fullsizepath = get_attached_file($imagenew->ID);
//         $attach_data = wp_generate_attachment_metadata($attach_id, $fullsizepath);
//         wp_update_attachment_metadata($attach_id, $attach_data);
//
//         return $attach_id;
//     } else {
//         return null;
//     }
// }

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


function backdot_migrate()
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

    return true;
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

// add_action('backdot_products_sync', 'backdot_migrate');

// wp_schedule_single_event(time() + 60, 'backdot_products_sync');

add_filter('wc_product_has_unique_sku', '__return_false', PHP_INT_MAX);
