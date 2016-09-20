<?php
$errors = null;
$term_args = array(
	'hide_empty' => true,
	'orderby' => 'name',
    'order' => 'ASC',
);
$products = get_terms('cjsupport_products', $term_args);
?>

<div id="cjsupport-faqs">
	<div id="products">
		<?php
		if(!is_wp_error($products) && !empty($products)):
			foreach ($products as $key => $product):
		?>
		<a href="#" data-id="product-<?php echo $product->term_id; ?>" class="product-title"><?php echo $product->name; ?><i class="fa fa-angle-right"></i></a>
		<?php endforeach; ?>
		<?php endif; ?>
	</div><!-- #products -->
	<?php
	if(!is_wp_error($products) && !empty($products)):
		foreach ($products as $key => $product):
	?>
	<div id="product-<?php echo $product->term_id; ?>" class="faqs-panel">
		<div class="faq-header">
			<a class="faq-back" href="#"><i class="fa fa-times-circle"></i></a>
			<span class="faq-header-title"><?php echo $product->name; ?></span>
		</div>
		<div class="faq-body">
			<?php
				$post_args = array(
					'post_type' => 'cjsupport_faqs',
					'post_status' => 'publish',
					'tax_query' => array(
						array(
							'taxonomy' => 'cjsupport_products',
							'field'    => 'slug',
							'terms'    => $product->slug,
						),
					),
				);
				$product_posts = get_posts($post_args);
				if(!empty($product_posts)):
				foreach ($product_posts as $key => $post):
			?>
				<div class="faq-single">
					<a href="#" class="faq-title cjsupport-toggle-id" data-id="article-<?php echo $product->term_id; ?>-<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></a>
					<div id="article-<?php echo $product->term_id; ?>-<?php echo $post->ID; ?>" class="cjsupport-toggle faq-content">
						<?php echo do_shortcode(wpautop($post->post_content)); ?>
					</div>
				</div>
			<?php endforeach; ?>
			<?php else: ?>
				<?php _e('Noting found.', 'cjsupport'); ?>
			<?php endif; ?>
		</div>
	</div>
	<?php endforeach; ?>
	<?php endif; ?>

</div><!-- #cjsupport-faqs -->

<style type="text/css">
	#cjsupport-faqs #products .product-title {
	  border-bottom: 1px solid <?php echo $border_color; ?>;
	}
	#cjsupport-faqs .faq-header {
	  border-top: 0px solid <?php echo $border_color; ?>;
	}
	#cjsupport-faqs .faq-body {
	  border-top: 1px solid <?php echo $border_color; ?>;
	}
	#cjsupport-faqs .faq-body .faq-title {
	  border-bottom: 1px solid <?php echo $border_color; ?>;
	}
</style>