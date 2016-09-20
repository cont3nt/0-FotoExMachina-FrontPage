/**
 * @package CSSJockey WordPress Framework
 * @author Mohit Aneja (cssjockey.com)
 * @version FW-20150208
*/
jQuery(document).ready(function($) {

    // Chosen
    var config = {
        '.chzn-select': {},
        '.chzn-select-deselect': {
            allow_single_deselect: true
        },
        '.chzn-select-no-single': {
            disable_search_threshold: 5
        },
        '.chzn-select-no-results': {
            allow_single_deselect: true,
            no_results_text: 'Oops, nothing found!'
        },
        '.chzn-select-width': {
            width: "95%"
        }
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

    $(".toggle-id").on('click', function() {
        var id = $(this).attr('href');
        $(id).toggle(0);
        return false;
    });

    // Quick search
    //$('input#settings-search-box').quicksearch('table.enable-search tr.searchable');

    $('.notification-close').on('click', function() {
        var el = $(this);
        $(el.attr('href')).fadeOut(250);
        $.post(
            ajaxurl, {
                'action': 'cjsupport_close_notification',
                'id': el.attr('data-id')
            },
            function(response) {

            }
        );
        return false;
    });

});

// Shortcode generator Scripts..
jQuery(document).ready(function($) {

    $('.close-cj-shortcode-generator-panel').on('click', function() {
        $('#cj-shortcode-generator-panel, #cj-shortcode-generator-backdrop').fadeOut();
        return false;
    });

    $('#cj-sg-select-product').on('change', function() {
        $('.cj-sg-loading-icon').fadeIn(0);
        $('#cj-sg-dynamic-content').fadeOut(0);
        var select_product = $(this);
        $('#cj-sg-select-shortcode').hide(0);
        var data = {
            'action': 'cjsupport_fetch_shortcodes',
            'slug': select_product.val()
        };
        $.post(ajaxurl, data, function(response) {
            if (response !== 'none') {
                $('#cj-sg-select-shortcode').html(response);
                $('#cj-sg-select-shortcode').fadeIn(0);
                $('#cj-sg-dynamic-content').fadeIn(0);
                $('.cj-sg-loading-icon').fadeOut(0);
            }
        });
    });

    $('#cj-sg-select-shortcode').on('change', function() {
        $('.cj-sg-loading-icon').fadeIn(0);
        $('#cj-sg-dynamic-content').fadeOut(0);
        var select_shortcode = $(this);
        var no_shortcode = $(this).attr('data-no-shortcode');
        if (select_shortcode.val() == 0) {
            $('#cj-sg-dynamic-content').html('<div class="alert alert-warning">' + no_shortcode + '</div>');
        } else {
            var data = {
                'action': 'cjsupport_fetch_shortcode_options',
                'shortcode': select_shortcode.val()
            };
            $.post(ajaxurl, data, function(response) {
                if (response !== 'none') {
                    $('#cj-sg-dynamic-content').fadeIn(0);
                    $('#cj-sg-dynamic-content').html(response);
                    $('.cj-sg-loading-icon').fadeOut(0);
                    setTimeout(function(){
                        cj_sb_loadAdminJsDynamically();
                    }, 100);
                }
            });
        }
    });

    function cj_sb_loadAdminJsDynamically() {

        // Color
        jQuery(".color-picker").spectrum({
            color: jQuery(this).attr('data-color'),
            showInput: true,
            className: "full-spectrum",
            showInitial: true,
            showPalette: true,
            showSelectionPalette: true,
            maxPaletteSize: 10,
            preferredFormat: "hex",
            localStorageKey: "spectrum.demo",
            allowEmpty: true,
            move: function(color) {

            },
            show: function() {

            },
            beforeShow: function() {

            },
            hide: function() {

            },
            change: function(color) {
                if (color == null) {
                    jQuery(this).parent().find('.color-hex code').html('transparent');
                } else {
                    var colorhex = color.toHexString();
                    jQuery(this).parent().find('.color-hex code').html(colorhex);
                }
            },
            palette: [
                ["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)",
                    "rgb(204, 204, 204)", "rgb(217, 217, 217)", "rgb(255, 255, 255)"
                ],
                ["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
                    "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"
                ],
                ["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)",
                    "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)",
                    "rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)",
                    "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)",
                    "rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)",
                    "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
                    "rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
                    "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
                    "rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)",
                    "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"
                ]
            ]
        });

        jQuery('.color-transparent').on('click', function() {
            if (jQuery(this).attr('checked') != undefined) {
                jQuery(this).closest('.cj-panel').find('input[type="text"]').attr('data-color', 'transparent');
            }
        });


        var file_frame;
        $('.cj-background-image').live('click', function(event) {

            var el = $(this);
            var form_id = $(el).attr('data-form-id');

            event.preventDefault();

            // If the media frame already exists, reopen it.
            if (file_frame) {
                file_frame.open();
                return;
            }

            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                title: $(this).data('uploader_title'),
                button: {
                    text: $(this).data('uploader_button_text'),
                },
                multiple: false // Set to true to allow multiple files to be selected
            });

            // When an image is selected, run a callback.
            file_frame.on('select', function() {
                // We set multiple to false so only get one image from the uploader
                attachment = file_frame.state().get('selection').first().toJSON();
                var container = $(el).parent().find('.uploaded-file');
                var option_id = $(container).attr('data-id');
                var shtml = '';
                var shtml = '<input type="hidden" name="' + option_id + '[image]" value="' + attachment.url + '" />';
                $(container).html(shtml);
                $(el).parent().parent().find('.cj-bg-info').html('<p class="red">Files uploaded, please save settings.</p>');

                //$("#" + form_id).trigger('click');

                // Do something with attachment.id and/or attachment.url here
            });

            // Finally, open the modal
            file_frame.open();
        });


        jQuery(".cj-remove-background-file").click(function() {
            jQuery(this).parent().parent().remove();
            return false;
        });

    }

});