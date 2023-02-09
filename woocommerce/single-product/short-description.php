<?php
/**
 * Single product short description
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/short-description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;

$short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );

if ( ! $short_description ) {
	return;
}

?>
<div class="tracking-wide">
	<lable class="text-gray-400 uppercase text-sm pb-4 block"><?= _e( "Breve Descrição:", "wlb_theme") ?></lable>
	<?php // WPCS: XSS ok. 
	$paragraphs = explode("<br>", $short_description);
	$splitedKeywords =  preg_split("/<br>|<BR>|<br\/>|<BR\/>|<br \/>/", $paragraphs[0] );
	$attributes = array();
	foreach($splitedKeywords as $keyword){      
		if (str_contains($keyword, ':')) {
			$splited = preg_split ("/\:/", $keyword); 
			$keyValue = new \stdClass();
			$keyValue->key = $splited[0];
			$keyValue->value = $splited[1];
			array_push($attributes, $keyValue);
		} else{
		$keyValue = new \stdClass();
		$keyValue->key = '';
		$keyValue->value = $keyword;
		array_push($attributes, $keyValue);
		}
	}
	foreach($attributes as $attribute){ ?>
		<div class="text-sm">
			<?php if($attribute->key) { ?>
				<b><?= $attribute->key ?>:</b>
			<?php } ?>
			<?= $attribute->value ?></div>
		<?php
		}
	?>
</div>
