(function() {
    tinymce.create('tinymce.plugins.cjsupport', {
        init: function(ed, url) {
            ed.addButton('cj-tinymce-button', {
                title: 'CSSJockey Shortcodes',
                cmd: 'cj_button',
                image: url + '/cj-icon.png'
            });
            ed.addCommand('cj_button', function() {
                jQuery('#cj-open-sg').trigger('click');
                //jQuery.getScript( "test.js", function( data, textStatus, jqxhr ) {...});
                jQuery('#cj-sg-select-product').val(0);
                jQuery('#cj-sg-select-shortcode').val(0).hide(0);
                jQuery('#cj-sg-select-shortcode').hide();
                jQuery('#cj-sg-dynamic-content').html('');
                jQuery('#cj-shortcode-generator-panel, #cj-shortcode-generator-backdrop').fadeIn();

                jQuery(document).unbind('submit').on('submit', '#cj-shortcode-settings-form', function(e) {
                    data = {
                        'action': 'cjsupport_prepare_shortcode_tag',
                        'stype': jQuery(this).attr('data-shortcode-stype'),
                        'shortcode': jQuery(this).attr('data-shortcode-name'),
                        'form_data': jQuery(this).serialize()
                    };
                    jQuery.post(ajaxurl, data, function(response) {
                        jQuery('#cj-sg-select-product').val(0);
                        jQuery('#cj-sg-select-shortcode').val(0).hide(0);
                        jQuery('#cj-sg-select-shortcode').hide();
                        jQuery('#cj-sg-dynamic-content').html('');
                        jQuery('#cj-shortcode-generator-panel, #cj-shortcode-generator-backdrop').fadeOut();
                        ed.execCommand('mceInsertContent', false, response);
                        return false;
                    });
                    return false;
                });
            });
        },
    });
    // Register plugin
    tinymce.PluginManager.add('cjsupport', tinymce.plugins.cjsupport);
})();