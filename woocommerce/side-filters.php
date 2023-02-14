<?php
$icons = new Icons();
global $wpdb;
$toIgnore = ['pa_opcoes-especiais', 'pa_opcoes', 'pa_programas', 'pa_acessorios-incluidos', 'pa_vitacontrol', 'pa_3-funcoes', 'pa_4-funcoes-especiais', 'pa_funcoes-especiais', 'pa_funcoes', 'pa_auto-off', 'pa_lavagem-por-sensores'];
?>
<div class="bg-white rounded-none overflow-y-auto grid content-start gap-4 max-h-[3008px] h-full" id="filters-box">
	<div class="overflow-auto">
		<?php
		$query_args = array(
			'status' => 'publish',
			'limit' => -1,
			'orderby' => 'menu_order title',
			'order' => 'ASC',
			'tax_query' => $args['tax_query']
		);
		$data = array();

		$new_args = $_GET;
		$pageIndex = array_key_exists('page', $new_args);
		if ($pageIndex) {
			unset($new_args['page']);
		}
		$pageIndex = array_key_exists('orderby', $new_args);
		if ($pageIndex) {
			unset($new_args['orderby']);
		}

		if (count($new_args) > 0) {
			foreach (wc_get_products($query_args) as $product) {
				foreach ($product->get_attributes() as $tax => $attribute) {
					if (in_array($tax, $toIgnore))
						continue;
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
			if (isset($args['category']) && isset($args['category']->slug)) {
				$queryString = 'SELECT * FROM ' . $table_name . ' WHERE tax_slug = "' . $args['category']->slug . '" ORDER BY attr_name ASC;';
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
					if ($attribute["slug"] !== $row->attr_slug)
						continue;
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
					foreach ($new_args as $key => $arg) {
						if ($key === "orderby")
							continue;
						$slug = $arg;
						$foundField = array_filter($data['pa_' . $key]['terms'], function ($field) use ($slug) {
							return $field->slug === $slug;
						});
						$firstKey = array_key_first($foundField);
						?>
						<li class="flex mb-2">
							<div
								class="clear-filter flex flex-row text-white bg-secondary py-1 px-2 rounded-full items-center space-x-1 cursor-pointer "
								data-value="<?= $arg ?>" data-attribute="<?= $data['pa_' . $key]["slug"] ?>"
								data-taxonomy="<?= isset($args['category']->slug) ? $args['category']->slug : '' ?>">
								<div class="pointer-events-none">
									<?= $icons->get_icon('AiOutlineCloseCircle') ?>
								</div>
								<div class="pointer-events-none text-sm">
									<?= $foundField[$firstKey]->name ?>
								</div>
							</div>
						</li>
					<?php } ?>
				</ul>
				<button class="clear-all-filters text-sm text-secondary"
					data-taxonomy="<?= isset($args['category']->slug) ? $args['category']->slug : '' ?>">Limpar tudo (<?= count($new_args) ?>)</button>
			</div>
		<?php
		}
		foreach ($data as $key => $attribute) {
			?>
			<div class="mb-4 p-4">
				<h4 class="font-bold uppercase mb-4 border-b">
					<?= $attribute["name"] ?>
				</h4>
				<ul
					class="filters-box overflow-hidden transition-all duration-200 ease-in-out <?= count($attribute["terms"]) > 5 ? 'max-h-[160px]' : '' ?>">
					<?php foreach ($attribute["terms"] as $term) { ?>
						<li class="flex items-center space-x-2 mb-2">
							<div class="relative w-6 h-6">
								<input type="checkbox" name="sidefilter" id="<?= $attribute["slug"] . '_' . $term->slug ?>"
									value="<?= $term->slug ?>" data-attribute="<?= $attribute["slug"] ?>"
									data-taxonomy="<?= property_exists($args['category'], 'slug') ? $args['category']->slug : '' ?>"
									class="appearance-none border border-gray-400 rounded-none cursor-pointer w-6 h-6 checked:bg-secondary filter-checkbox"
									<?php
									$cleanSlug = str_replace('pa_', '', $attribute["slug"]);

									if (isset($_GET[$cleanSlug])) {
										$terms = explode("_", $_GET[$cleanSlug]);
										if (in_array($term->slug, $terms)) {
											echo 'checked="checked"';
										}
									}
									?>>
							</div>
							<label class="text-sm" for="<?= $attribute["slug"] . '_' . $term->slug ?>">
								<?= $term->name ?> (<?= $term->count ?>)
							</label>
						</li>
					<?php } ?>
				</ul>
			</div>
		<?php }
		?>
	</div>
</div>