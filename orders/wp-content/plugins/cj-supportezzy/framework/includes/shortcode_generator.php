<?php
/**
 * @package CSSJockey WordPress Framework
 * @author Mohit Aneja (cssjockey.com)
 * @version FW-20150208
*/
// New Shortcode Generator
require_once(sprintf('%s/shortcode-generator/tinymce_shortcode_generator.php', cjsupport_item_path('includes_dir')));


/*
Depriciated
if(is_admin() && strpos(cjsupport_current_url(), 'post.php') > 0){
	add_filter( 'cmb_meta_boxes', 'cjsupport_shortcode_generator_run' );
}
*/

function cjsupport_shortcode_generator_run( array $meta_boxes ) {
	global $shortcode_tags;
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_';

	$default_post_types = array('post', 'page');
	$custom_post_types = get_post_types();
	if(!empty($custom_post_types)){
		$custom_post_types = @array_keys($custom_post_types);
		if(is_array($custom_post_types)){
			$show_metabox_on = array_merge($custom_post_types, $default_post_types);
		}else{
			$show_metabox_on = $default_post_types;
		}
	}else{
		$show_metabox_on = $default_post_types;
	}

	$cjsupport_shortcodes['cjsupport'][] = array(
		'name' => __('Select shortcode', 'cjsupport'),
		'value' => 'select shortcode',
	);
	if(!empty($shortcode_tags)){
		$text_domain_count = strlen('cjsupport');
		foreach ($shortcode_tags as $key => $value) {
			if(!is_array($value) && substr($value, 0, $text_domain_count) == 'cjsupport'){

				$name = str_replace('cjsupport'.'_', '', $value);
				$name = str_replace('_', ' ', $name);

				$cjsupport_shortcodes['cjsupport'][] = array(
					'name' => ucwords($name),
					'value' => $value,
				);
				$shortcode_options[$key] = unserialize(cjsupport_do_shortcode( '['.$key.' return="defaults"]' ));
				//$shortcode_options[$key] = unserialize(get_option( sha1($key) ));
			}
		}
	}

	$shortcode_options_display[] = '<span id="cjsupport-shortcode-content" onclick="selectText(\'cjsupport-shortcode-content\')" class="cjsupport-shortcode-content">'.__('Select shortcode', 'cjsupport').'</span>';

	if(isset($shortcode_options) && is_array($shortcode_options)){
		foreach ($shortcode_options as $key => $value) {

			$shortcode_options_display[] = '<span style="clear:both;"></span><div id="'.$key.'" style="clear:both; overflow:hidden; height:0px;" class="cjsupport-shortcode-panel clearfix" data-stype="'.$value['stype'].'">';
			$shortcode_options_display[] = '<b style="border-bottom:1px solid #ddd; display:block; margin-bottom:5px; padding-bottom:5px;">'.ucwords(str_replace('cjsupport', '', str_replace('_', ' ', $key)).' '.__('Options', 'cjsupport')).'</b>';

				if(is_array($value)){
		  			foreach ($value as $skey => $sval) {

		  				if($skey != 'stype' && $sval[1] == 'text'){
			  				$shortcode_options_display[] = '<p>';
			  				$shortcode_options_display[] = '<label><b>'.$sval[0].'</b><br />';
			  				$shortcode_options_display[] = '<input class="'.$key.'-cjsupport-options" data-attr="'.$skey.'" name="cjsupport-'.$skey.'" type="'.$sval[1].'" value="'.$sval[2].'" />';
			  				$shortcode_options_display[] = '</label>';
			  				$shortcode_options_display[] = '</p>';
		  				}

		  				if($skey != 'stype' && $sval[1] == 'textarea'){
			  				$shortcode_options_display[] = '<p>';
			  				$shortcode_options_display[] = '<label><b>'.$sval[0].'</b><br />';
			  				$shortcode_options_display[] = '<textarea class="'.$key.'-cjsupport-options" rows="3" cols="40" data-attr="'.$skey.'" name="cjsupport-'.$skey.'">'.$sval[2].'</textarea>';
			  				$shortcode_options_display[] = '</label>';
			  				$shortcode_options_display[] = '</p>';
		  				}

		  				if($skey != 'stype' && $sval[1] == 'heading'){
			  				$shortcode_options_display[] = '<b style="clear:both; border-bottom:1px solid #ddd; display:block; margin-top:15px; margin-bottom:15px; padding-bottom:5px;">';
			  				$shortcode_options_display[] = $sval[0];
			  				$shortcode_options_display[] = '</b>';
		  				}

		  				if($skey != 'stype' && $sval[1] == 'info'){
			  				$shortcode_options_display[] = '<div style="clear:both; display:block; margin-top:15px; margin-bottom:15px;">';
			  				$shortcode_options_display[] = $sval[0];
			  				$shortcode_options_display[] = '</div>';
		  				}

		  				if($skey != 'stype' && $sval[1] == 'select' || $sval[1] == 'dropdown'){
			  				$shortcode_options_display[] = '<p>';
			  				$shortcode_options_display[] = '<label><b>'.$sval[0].'</b><br />';
			  				$shortcode_options_display[] = '<select class="'.$key.'-cjsupport-options chzn-select-no-results" data-attr="'.$skey.'" name="cjsupport-'.$skey.'" style="width:250px !important;">';
			  				$opts = '';
			  				$opts = '<option value="">'.__('Please Select ', 'cjsupport').'</option>';
			  				if(is_array($sval[2])){
			  					$shortcode_options_display[] = $opts;
				  				foreach ($sval[2] as $okey => $oval) {
				  					$ovalue = str_replace('_', ' ', $oval);
				  					$ovalue = str_replace('-', ' ', $ovalue);
				  					$shortcode_options_display[] = '<option value="'.$okey.'">'.$ovalue.'</option>';
				  				}
				  			}
			  				$shortcode_options_display[] = '</select>';
			  				$shortcode_options_display[] = '</label>';
			  				$shortcode_options_display[] = '</p>';
		  				}

		  				if($skey != 'stype' && $sval[1] == 'multiselect' || $sval[1] == 'multidropdown'){
			  				$shortcode_options_display[] = '<p>';
			  				$shortcode_options_display[] = '<label><b>'.$sval[0].'</b><br />';
			  				$shortcode_options_display[] = '<select multiple class="'.$key.'-cjsupport-options chzn-select-no-results" data-attr="'.$skey.'" name="cjsupport-'.$skey.'" style="width:250px !important;">';
			  				$opts = '';
			  				$opts = '<option value="">'.__('Please Select ', 'cjsupport').'</option>';
			  				if(is_array($sval[2])){
			  					$shortcode_options_display[] = $opts;
				  				foreach ($sval[2] as $okey => $oval) {
				  					$ovalue = str_replace('_', ' ', $oval);
				  					$ovalue = str_replace('-', ' ', $ovalue);
				  					$shortcode_options_display[] = '<option value="'.$okey.'">'.$ovalue.'</option>';
				  				}
				  			}
			  				$shortcode_options_display[] = '</select>';
			  				$shortcode_options_display[] = '</label>';
			  				$shortcode_options_display[] = '</p>';
		  				}

		  			}
		  			$shortcode_options_display[] = '<br style="clear:both; height:1px;"><br />';
		  		}

			$shortcode_options_display[] = '</div>';

		}
	}
	$shortcode_options_panel = implode('', $shortcode_options_display);
	if(isset($_GET['post'])){
		update_post_meta(@$_GET['post'], '_shortcodes', '');
	}

	if(count($cjsupport_shortcodes['cjsupport']) > 1){

		$meta_boxes['cjsupport'] = array(
			'id'         => 'cjsupport_shortcode_generator',
			'title'      => sprintf(__('Shortcode Generator (%s)', 'cjsupport'), cjsupport_item_info('item_name')),
			'pages'      => $show_metabox_on,
			'context'    => 'advanced',
			'priority'   => 'high',
			'show_names' => false, // Show field names on the left
			'fields'     => array(
				array(
					'name' => __('Choose a shortcode', 'cjsupport'),
					'desc' => $shortcode_options_panel,
					'id'   => $prefix . 'cjsupport_shortcodes',
					'type' => 'select',
					'options' => $cjsupport_shortcodes['cjsupport'],
					'std' => '',
				),
			),
		);

	}

	return $meta_boxes;

}


function cjsupport_shortcode_generator_scripts(){
	$html = <<<EOF
<style type="text/css">
	#_cjsupport_shortcodes{
		width: 20% !important;
		float: left;
	}
	.cjsupport-shortcode-content{
		display:block;
		width:76%;
		float: right;
		padding:3px 10px;
		background:#fff;
		border:1px solid #ddd;
		border-radius:4px;
	}
	.cjsupport-shortcode-panel{
		overflow:hidden;
	}

	.cjsupport-shortcode-panel p{
		width: 300px;
		height: 44px;
		margin: 0px 15px 15px 0 !important;
		float:left;
	}
	.cjsupport-shortcode-panel input[type="text"]{
		width: 220px !important;
		background:#fff;
		border:1px solid #ddd;
		border-radius:4px;
	}
	.cjsupport-shortcode-content span.code{
		font-family: Menlo, Monaco, Consolas, "Courier New", monospace;
		font-size: 12px;
		color: #cc0000;
		font-style:normal;
	}
</style>
<script type="text/javascript">
	jQuery(document).ready(function(){

		jQuery("#_cjsupport_shortcodes").val('select shortcode');

		jQuery("#_cjsupport_shortcodes").bind('change', function(){

			jQuery(".cjsupport-shortcode-panel").attr('style', 'height:auto; clear:both; padding-top:10px; overflow: inherit;')

			var sname = jQuery(this).val();
			var stype = jQuery("#"+sname).attr('data-stype');
			if(stype == 'closed'){
				send = ' content goes here (if any) [/'+sname+']';
			}else{
				send = '';
			}


			var sopts = new Array();
			var count = 0;
			jQuery('.'+sname+'-cjsupport-options').each(function(i){
				count++;
				var oname = jQuery(this).attr('name');
				var ovalue = jQuery(this).val();
				sopts[i] = ' <span class="'+oname+'">'+ovalue+'</span>';

				jQuery(this).bind('change', function(){
					var onam = jQuery(this).attr('data-attr');
					var oval = jQuery(this).val();
					if(oval == ''){
						jQuery("."+oname).html('');
					}else{
						jQuery("."+oname).html(' '+onam+'="'+oval+'"');
					}
				})

			})

			if(count == 0){
				jQuery('.cjsupport-options-heading').hide(0);
			}else{
				jQuery('.cjsupport-options-heading').show(0);
			}


			jQuery(".cjsupport-shortcode-content").html('<span class="code">['+sname+''+sopts.join('')+']'+send+'</span>');

			jQuery(".cjsupport-shortcode-panel").fadeOut(0);
			jQuery("#"+sname).fadeIn(250);

		})


		/*var config = {
		  '.chzn-select'           : {},
		  '.chzn-select-deselect'  : {allow_single_deselect:true},
		  '.chzn-select-no-single' : {disable_search_threshold:10},
		  '.chzn-select-no-results': {no_results_text:'Oops, nothing found!'},
		  '.chzn-select-width'     : {width:"95%"}
		}
		for (var selector in config) {
		  jQuery(selector).chosen(config[selector]);
		}*/

	})

	function selectText(containerid) {
	    if (document.selection) {
	        var range = document.body.createTextRange();
	        range.moveToElementText(document.getElementById(containerid));
	        range.select();
	    } else if (window.getSelection) {
	        var range = document.createRange();
	        range.selectNode(document.getElementById(containerid));
	        window.getSelection().addRange(range);
	    }
	}


</script>
EOF;
echo $html;
}


//add_action('admin_footer', 'cjsupport_shortcode_generator_scripts');