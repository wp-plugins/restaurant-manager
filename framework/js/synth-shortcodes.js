/* 
 * SyntaxThemes Shortcodes Backend
 * 
 * @author Ryan Haworth
 */
(function($, window, document, undefined) {

    $.SynthShortcodes = function(options) {


    };
    $.SynthShortcodes.prototype = {
        backend_initialize: function() {

        },
        frontend_initialize: function() {

        },
        backend_modal_loaded: function() {

            jQuery.synth_controls.color_selector_initialize();
            this.on_dropcap_advanced_initialize();
            this.on_synth_gallery_on_load();
            this.on_synth_icon_box_on_load();
            this.on_synth_background_on_load();
            this.on_synth_contact_form_load();
            this.on_synth_slider_load();
            this.on_synth_carousel_load();
            this.on_synth_progress_bar_load();
            this.on_synth_blog_load();
        },
        on_dropcap_advanced_initialize: function() {

            var selected = jQuery('#syn_dropcap_adv_glyph').val();
            on_dropcap_advanced_update_view(selected);
            on_dropcap_advanced_style_changed();

            function on_dropcap_advanced_style_changed() {

                jQuery(document.body).on('change', '#syn_dropcap_adv_glyph', function() {

                    var selected = jQuery(this).val();
                    on_dropcap_advanced_update_view(selected);
                });
            }

            function on_dropcap_advanced_update_view(selected) {

                if (selected === 'character') {
                    jQuery('#syn_dropcap_adv_icon').attr('data-visibility', 'hidden').closest('.control-field').hide();
                    jQuery('#syn_dropcap_adv_character').attr('data-visibility', 'visible').closest('.control-field').show();
                }

                if (selected === 'icon') {
                    jQuery('#syn_dropcap_adv_icon').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_dropcap_adv_character').attr('data-visibility', 'hidden').closest('.control-field').hide();
                }
            }

        },
        on_synth_gallery_on_load: function() {

            var selected = jQuery('#syn_gallery_layout').val();
            on_synth_gallery_update_view(selected);
            on_synth_gallery_layout_changed();

            function on_synth_gallery_layout_changed() {

                jQuery(document.body).on('change', '#syn_gallery_layout', function() {

                    var selected = jQuery(this).val();
                    on_synth_gallery_update_view(selected);
                });
            }

            function on_synth_gallery_update_view(selected) {

                jQuery('#syn_gallery_columns').attr('data-visibility', 'hidden').closest('.control-field').hide();
                jQuery('#syn_gallery_viewport_size').attr('data-visibility', 'hidden').closest('.control-field').hide();
                jQuery('#syn_gallery_thumbnail_size').attr('data-visibility', 'hidden').closest('.control-field').hide();

                if (selected === 'thumbnails') {
                    jQuery('#syn_gallery_columns').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_gallery_thumbnail_size').attr('data-visibility', 'visible').closest('.control-field').show();
                }
                if (selected === 'viewport') {
                    jQuery('#syn_gallery_columns').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_gallery_viewport_size').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_gallery_thumbnail_size').attr('data-visibility', 'visible').closest('.control-field').show();
                }
            }
        },
        on_synth_icon_box_on_load: function() {

            var selected = jQuery('#syn_icon_box_style').val();
            on_synth_icon_box_update_view(selected);
            on_synth_icon_box_layout_changed();
            //on_synth_icon_box_theming_changed();

            function on_synth_icon_box_layout_changed() {

                jQuery(document.body).on('change', '#syn_icon_box_style', function() {

                    var selected = jQuery(this).val();
                    on_synth_icon_box_update_view(selected);
                });
            }

            function on_synth_icon_box_theming_changed() {

                jQuery(document.body).on('change', '#syn_icon_box_theming', function() {

                    var selected = jQuery(this).val();
                    on_synth_icon_themeing_update_colors(selected);
                });
            }

            function on_synth_icon_themeing_update_colors(selected) {

                var box_style = jQuery('#syn_icon_box_style').val();

                jQuery('#syn_icon_box_border_color').attr('data-visibility', 'hidden').closest('.control-field').hide();
                jQuery('#syn_icon_box_color').attr('data-visibility', 'hidden').closest('.control-field').hide();
                jQuery('#syn_icon_box_title_color').attr('data-visibility', 'hidden').closest('.control-field').hide();
                jQuery('#syn_icon_box_icon_color').attr('data-visibility', 'hidden').closest('.control-field').hide();

                if (selected === 'custom_color' && box_style === 'simplico') {

                    jQuery('#syn_icon_box_title_color').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_icon_color').attr('data-visibility', 'visible').closest('.control-field').show();
                }
                if (selected === 'custom_color' && box_style === 'classic') {

                    jQuery('#syn_icon_box_title_color').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_icon_color').attr('data-visibility', 'visible').closest('.control-field').show();
                }
                if (selected === 'custom_color' && box_style === 'obsidian') {

                    jQuery('#syn_icon_box_title_color').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_icon_color').attr('data-visibility', 'visible').closest('.control-field').show();
                }
                if (selected === 'custom_color' && box_style === 'cuboidal') {

                    jQuery('#syn_icon_box_title_color').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_icon_color').attr('data-visibility', 'visible').closest('.control-field').show();
                }
                if (selected === 'custom_color' && box_style === 'iconobox') {

                    jQuery('#syn_icon_box_border_color').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_color').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_title_color').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_icon_color').attr('data-visibility', 'visible').closest('.control-field').show();
                }
                if (selected === 'custom_color' && box_style === 'inset') {

                    jQuery('#syn_icon_box_border_color').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_color').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_title_color').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_icon_color').attr('data-visibility', 'visible').closest('.control-field').show();
                }
                if (selected === 'custom_color' && box_style === 'inset-bold') {

                    jQuery('#syn_icon_box_border_color').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_color').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_title_color').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_icon_color').attr('data-visibility', 'visible').closest('.control-field').show();
                }
            }

            function on_synth_icon_box_update_view(selected) {

                jQuery('#syn_icon_box_alignment').attr('data-visibility', 'hidden').closest('.control-field').hide();
                jQuery('#syn_icon_box_heading').attr('data-visibility', 'hidden').closest('.control-field').hide();
                jQuery('#syn_icon_box_icon_selector').attr('data-visibility', 'hidden').closest('.control-field').hide();
                jQuery('#syn_icon_box_icon_size').attr('data-visibility', 'hidden').closest('.control-field').hide();
                jQuery('#syn_icon_box_icon_size option[value=xlarge]').hide();
                jQuery('#syn_icon_box_alignment option[value=center]').hide();
                jQuery('#syn_icon_box_border_thickness').attr('data-visibility', 'hidden').closest('.control-field').hide();
                jQuery('#syn_icon_box_color_selector, #syn_icon_box_theming').attr('data-visibility', 'hidden').closest('.control-field').hide();

                jQuery('#syn_icon_box_link').attr('data-visibility', 'visible').closest('.control-field').show();
                jQuery('#syn_icon_box_target').attr('data-visibility', 'visible').closest('.control-field').show();

                if (selected === 'simplico') {

                    jQuery('#syn_icon_box_heading').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_icon_selector').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_icon_size').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_alignment').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_alignment option[value=center]').show();
                }
                if (selected === 'classic') {

                    jQuery('#syn_icon_box_heading').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_icon_selector').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_icon_size').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_alignment').attr('data-visibility', 'visible').closest('.control-field').show();
                }
                if (selected === 'obsidian') {

                    jQuery('#syn_icon_box_heading').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_icon_selector').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_icon_size').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_alignment').attr('data-visibility', 'visible').closest('.control-field').show();
                }
                if (selected === 'cuboidal') {

                    jQuery('#syn_icon_box_heading').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_icon_selector').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_icon_size').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_alignment').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_alignment option[value=center]').show();
                    jQuery('#syn_icon_box_icon_size option[value=xlarge]').show();
                }
                if (selected === 'iconobox') {

                    jQuery('#syn_icon_box_heading').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_icon_selector').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_icon_size').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_alignment').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_border_thickness').attr('data-visibility', 'visible').closest('.control-field').show();

                    jQuery('#syn_icon_box_alignment option[value=center]').show();
                }
                if (selected === 'inset') {

                    jQuery('#syn_icon_box_heading').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_icon_selector').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_icon_size').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_alignment').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_border_thickness').attr('data-visibility', 'visible').closest('.control-field').show();

                    jQuery('#syn_icon_box_alignment option[value=center]').hide();
                    jQuery('#syn_icon_box_icon_size option[value=xlarge]').show();
                }
                if (selected === 'inset-bold') {

                    jQuery('#syn_icon_box_heading').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_icon_selector').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_icon_size').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_alignment').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_icon_box_border_thickness').attr('data-visibility', 'visible').closest('.control-field').show();

                    jQuery('#syn_icon_box_alignment option[value=center]').hide();
                    jQuery('#syn_icon_box_icon_size option[value=xlarge]').show();
                    jQuery('#syn_icon_box_theming option[value=custom_color]').hide();
                }
            }
        },
        on_synth_background_on_load: function() {

            var selected_type = jQuery('#syn_background_type').val();
            on_synth_background_update_view(selected_type);
            on_synth_background_type_changed();

            function on_synth_background_type_changed() {

                jQuery(document.body).on('change', '#syn_background_type', function() {

                    var selected = jQuery(this).val();
                    on_synth_background_update_view(selected);
                });
            }

            function on_synth_background_update_view(selected) {

                jQuery('#syn_background_color').attr('data-visibility', 'hidden').closest('.control-field').hide();
                jQuery('#syn_background_image_upload').attr('data-visibility', 'hidden').closest('.control-field').hide();
                jQuery('#syn_background_overlay').attr('data-visibility', 'hidden').closest('.control-field').hide();
                jQuery('#syn_background_video').attr('data-visibility', 'hidden').closest('.control-field').hide();

                if (selected === 'color') {

                    jQuery('#syn_background_color').attr('data-visibility', 'visible').closest('.control-field').show();
                }
                if (selected === 'image') {

                    jQuery('#syn_background_image_upload').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_background_overlay').attr('data-visibility', 'visible').closest('.control-field').show();
                }
                if (selected === 'parallax') {

                    jQuery('#syn_background_image_upload').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_background_overlay').attr('data-visibility', 'visible').closest('.control-field').show();
                }
                if (selected === 'video') {

                    jQuery('#syn_background_video').attr('data-visibility', 'visible').closest('.control-field').show();
                    jQuery('#syn_background_overlay').attr('data-visibility', 'visible').closest('.control-field').show();
                }
            }
        },
        on_synth_bulletlist_item_on_load: function() {

            var selected_type = jQuery('#syn_bulletlist_item_show_title').val();
            on_synth_bulletlist_item_update_view(selected_type);
            on_synth_bulletlist_item_show_title_changed();

            function on_synth_bulletlist_item_show_title_changed() {

                jQuery(document.body).on('change', '#syn_bulletlist_item_show_title', function() {
                    var selected = jQuery(this).val();
                    on_synth_bulletlist_item_update_view(selected);
                });
            }

            function on_synth_bulletlist_item_update_view(selected) {

                jQuery('#syn_bulletlist_item_title').attr('data-visibility', 'hidden').closest('.control-field').hide();

                if (selected === 'true') {

                    jQuery('#syn_bulletlist_item_title').attr('data-visibility', 'visible').closest('.control-field').show();
                }
                if (selected === 'false') {

                    jQuery('#syn_bulletlist_item_title').attr('data-visibility', 'hidden').closest('.control-field').hide();
                }
            }
        },
        on_synth_contact_form_load: function() {

            var selected = jQuery('#syn_contact_form_item_type').val();
            on_synth_contact_form_item_update_view(selected);
            on_synth_contact_form_item_type_changed();

            function on_synth_contact_form_item_type_changed() {

                jQuery(document.body).on('change', '#syn_contact_form_item_type', function() {
                    var selected = jQuery(this).val();
                    on_synth_contact_form_item_update_view(selected);
                });
            }

            function on_synth_contact_form_item_update_view(selected) {

                jQuery('#syn_contact_form_item_placeholder').attr('data-visibility', 'hidden').closest('.control-field').hide();

                if (selected === 'text' || selected === 'email' || selected === 'textarea') {

                    jQuery('#syn_contact_form_item_placeholder').attr('data-visibility', 'visible').closest('.control-field').show();
                }
            }
        },
        on_synth_slider_load: function() {

            var selected = jQuery('#syn_slider_source').val();
            on_synth_slider_update_view(selected);
            on_synth_slider_data_source_changed();

            function on_synth_slider_data_source_changed() {

                jQuery(document.body).on('change', '#syn_slider_source', function() {
                    var selected = jQuery(this).val();
                    on_synth_slider_update_view(selected);
                });
            }

            function on_synth_slider_update_view(selected) {

                jQuery('#syn_slider_ids_gallery_upload').attr('data-visibility', 'hidden').closest('.control-field').hide();
                jQuery('#syn_slider_builder_nested_shortcode_builder').attr('data-visibility', 'hidden').closest('.control-field').hide();
                jQuery('#syn_slider_taxonomy').attr('data-visibility', 'hidden').closest('.control-field').hide();

                if (selected === 'media') {

                    jQuery('#syn_slider_ids_gallery_upload').attr('data-visibility', 'visible').closest('.control-field').show();
                }
                if (selected === 'taxonomy') {

                    jQuery('#syn_slider_taxonomy').attr('data-visibility', 'visible').closest('.control-field').show();
                }
                if (selected === 'custom') {
                    jQuery('#syn_slider_builder_nested_shortcode_builder').attr('data-visibility', 'visible').closest('.control-field').show();
                }
            }
        },
        on_synth_carousel_load: function() {

            var selected = jQuery('#syn_carousel_source').val();
            on_synth_carousel_update_view(selected);
            on_synth_carousel_data_source_changed();

            function on_synth_carousel_data_source_changed() {

                jQuery(document.body).on('change', '#syn_carousel_source', function() {
                    var selected = jQuery(this).val();
                    on_synth_carousel_update_view(selected);
                });
            }

            function on_synth_carousel_update_view(selected) {

                jQuery('#syn_carousel_ids_gallery_upload').attr('data-visibility', 'hidden').closest('.control-field').hide();
                jQuery('#syn_carousel_builder_nested_shortcode_builder').attr('data-visibility', 'hidden').closest('.control-field').hide();
                jQuery('#syn_carousel_term_id').attr('data-visibility', 'hidden').closest('.control-field').hide();

                if (selected === 'media') {

                    jQuery('#syn_carousel_ids_gallery_upload').attr('data-visibility', 'visible').closest('.control-field').show();
                }
                if (selected === 'taxonomy') {

                    jQuery('#syn_carousel_term_id').attr('data-visibility', 'visible').closest('.control-field').show();
                }
                if (selected === 'custom') {
                    jQuery('#syn_carousel_builder_nested_shortcode_builder').attr('data-visibility', 'visible').closest('.control-field').show();
                }
            }
        },
        on_synth_progress_bar_load: function() {

            var selected = jQuery('#syn_progress_bar_style').val();
            on_progress_bar_update_view(selected);
            on_progress_bar_type_changed();

            function on_progress_bar_type_changed() {

                jQuery(document.body).on('change', '#syn_progress_bar_style', function() {

                    var selected = jQuery(this).val();
                    on_progress_bar_update_view(selected);
                });
            }

            function on_progress_bar_update_view(selected) {

                jQuery('#syn_progress_bar_tooltip').attr('data-visibility', 'hidden').closest('.control-field').hide();

                if (selected === 'default_tooltip' || selected === 'rounded_tooltip') {

                    jQuery('#syn_progress_bar_tooltip').attr('data-visibility', 'visible').closest('.control-field').show();
                }
            }
        },
        on_synth_blog_load: function() {

            var selected = jQuery('#syn_blog_layout').val();
            on_blog_layout_update_view(selected);
            on_blog_layout_changed();

            function on_blog_layout_changed() {

                jQuery(document.body).on('change', '#syn_blog_layout', function() {

                    var selected = jQuery(this).val();
                    on_blog_layout_update_view(selected);
                });
            }

            function on_blog_layout_update_view(selected) {

                jQuery('#syn_blog_grid_columns').attr('data-visibility', 'hidden').closest('.control-field').hide();

                if (selected === 'grid') {

                    jQuery('#syn_blog_grid_columns').attr('data-visibility', 'visible').closest('.control-field').show();
                }
            }
        }
    };
})(jQuery, window, document);
jQuery(document).ready(function()
{
    jQuery.synth_shortcodes = new jQuery.SynthShortcodes();
});


