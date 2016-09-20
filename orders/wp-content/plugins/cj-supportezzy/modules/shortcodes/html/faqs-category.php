<?php
$errors = null;
$post_args = array(
	'post_type' => 'cjsupport_faqs',
	'post_status' => 'publish',
	'orderby' => 'menu_order',
	'order' => 'ASC',
	'tax_query' => array(
		array(
			'taxonomy' => 'cjsupport_products',
			'field'    => 'id',
			'terms'    => $category,
		),
	),
);
$product_posts = get_posts($post_args);
?>

<div id="cjsupport-faqs">
<div class="cjsupport-faqs-category">
<?php if(!empty($product_posts)): ?>
<?php foreach ($product_posts as $key => $fpost): ?>
<div class="faq-single">
	<a href="#" class="faq-title cjsupport-toggle-id" data-id="single-article-<?php echo $category; ?>-<?php echo $fpost->ID; ?>"><?php echo $fpost->post_title; ?></a>
	<div id="single-article-<?php echo $category; ?>-<?php echo $fpost->ID; ?>" class="cjsupport-toggle faq-content">
		<?php echo do_shortcode(wpautop($fpost->post_content)); ?>
	</div>
</div>
<?php endforeach; ?>
<?php endif; ?>

</div>
</div><!-- #cjsupport-faqs -->

<style type="text/css">

	#cjsupport-faqs .cjsupport-faqs-category .faq-title {
	  border-bottom: 1px solid <?php echo $border_color; ?>;
	}

</style>