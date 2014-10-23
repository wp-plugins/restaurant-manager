/* 
 * SyntaxThemes Controls Backend
 * 
 * @author Ryan Haworth
 */
(function($, window, document, undefined) {

    $.SynthControls = function(options) {

        this.initialize();
    };

    $.SynthControls.prototype = {
        initialize: function() {

            this.font_awesome_icon_selector_initialize();
            this.color_picker_initialize();
            this.color_selector_initialize();
            this.media_upload_initialize();
            this.media_gallery_upload_initialize();
            this.image_radio_list_initialize();
            this.background_selector_initialize();
            this.extender_initialize();
            this.padding_initialize();
        },
        font_awesome_icon_selector_initialize: function() {

            var selected = jQuery('.synth-icon-field select').val();

            if (selected === 'yes') {
                jQuery('.synth-icon-selector').removeClass('hide').addClass('show');
            }
            else {
                jQuery('.synth-icon-selector').removeClass('show').addClass('hide');
            }

            jQuery(document.body).on('change', '.synth-icon-field select', function() {

                var selection = jQuery(this).val();

                if (selection === 'yes') {
                    jQuery('.synth-icon-selector').removeClass('hide').addClass('show');
                }
                else {
                    jQuery('.synth-icon-selector').removeClass('show').addClass('hide');
                }
            });

            jQuery(document.body).on('click', '.synth-icon-selector span', function() {

                var icon_selector = jQuery(this).closest('.synth-icon-selector');
                jQuery(this).parent().find('span').removeClass('active');
                jQuery(this).addClass('active');

                var icon = jQuery(this).data('icon');
                icon_selector.find('input[type=hidden]').val(icon);

            });
        },
        color_picker_initialize: function() {
            /**
             * Initialize color picker
             */
            if (typeof jQuery.wp === 'object' && typeof jQuery.wp.wpColorPicker === 'function') {

                jQuery('input:text.synth-colorpicker').wpColorPicker();

            } else {
                jQuery('input:text.synth-colorpicker').each(function(i) {

                    jQuery(this).after('<div id="picker-' + i + '" style="z-index: 1000; background: #EEE; border: 1px solid #CCC; position: absolute; display: block;"></div>');
                    jQuery('#picker-' + i).hide().farbtastic(jQuery(this));

                }).focus(function() {
                    jQuery(this).next().show();
                }).blur(function() {
                    jQuery(this).next().hide();
                });
            }
        },
        color_selector_initialize: function() {

            jQuery('.synth-color-selector .synth-colorpicker').attr('data-visibility', 'hidden').closest('.control-field').hide();

            jQuery(document.body).on('change', '.synth-color-selector select', function() {

                var selection = jQuery(this).val();

                if (selection === 'custom_color') {

                    jQuery('.synth-color-selector .synth-colorpicker').attr('data-visibility', 'visible').closest('.control-field').show();
                }
                else {
                    jQuery('.synth-color-selector .synth-colorpicker').attr('data-visibility', 'hidden').closest('.control-field').hide();
                }
            });
        },
        media_upload_initialize: function() {

            /**
             * File and image upload handling
             */
            // Uploading files
            var file_frame;

            jQuery(document.body).on('click', '.upload-image-button', function(e) {

                var container = jQuery(this).parent('.synth-media-upload');

                // If the media frame already exists, reopen it.
                if (file_frame) {
                    file_frame.open();
                    return;
                }

                // Create the media frame.
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: jQuery(this).data('uploader-title'),
                    button: {
                        text: jQuery(this).data('uploader-button-text')
                    },
                    multiple: false  // Set to true to allow multiple files to be selected
                });

                // When an image is selected, run a callback.
                file_frame.on('select', function() {
                    // We set multiple to false so only get one image from the uploader
                    var attachment = file_frame.state().get('selection').first().toJSON();

                    // Do something with attachment.id and/or attachment.url here
                    container.find('.upload-image-id').val(attachment.id);
                    container.find('.upload-image-text').val(attachment.url);

                    if (attachment.id) {
                        container.find('.upload-image-text').attr('data-visibility', 'hidden');
                    }

                    //add the image to the image control view
                    container.find('.upload-image-view').show();
                    container.find('.upload-image-view').attr('src', attachment.url);

                    // Set the file frame to null to reset.
                    file_frame = null;
                });

                // Finally, open the modal
                file_frame.open();

                e.preventDefault();
            });

            jQuery(document.body).on('click', '.upload-image-remove', function(e) {

                var container = jQuery(this).parent('.synth-media-upload');

                container.find('.upload-image-text').val("");

                //remove the image from the control view
                container.find('.upload-image-view').hide();
                container.find('.upload-image-view').attr('src', 'null');

                e.preventDefault();
            });
        },
        media_gallery_upload_initialize: function() {

            /**
             * File and image upload handling
             */
            // Uploading files
            var file_frame;

            jQuery(document.body).on('click', '.upload-gallery-button', function(e) {

                var container = jQuery(this).parent('.synth-media-upload');
                var ids = container.find('.upload-gallery-ids').val();
                var selection = synth_load_media_images(ids);

                // If the media frame already exists, reopen it.
                if (file_frame) {
                    file_frame.open();
                    return;
                }

                // Create the media frame.
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: jQuery(this).data('uploader-title'),
                    frame: 'post',
                    state: 'gallery-edit',
                    button: {
                        text: jQuery(this).data('uploader-button-text')
                    },
                    multiple: true, // Set to true to allow multiple files to be selected
                    selection: selection
                });

                // When a gallery has been created, run a callback.
                file_frame.on('update', function() {

                    var controller = file_frame.states.get('gallery-edit');
                    var library = controller.get('library');
                    var ids = library.pluck('id');
                    var urls = library.pluck('url');

                    var gallery = container.find('.synth-media-gallery');
                    var control_ids = container.find('.upload-gallery-ids');
                    var gallery_html = build_synth_gallery_view(urls);
                    var gallery_ids = ids.join(',');
                    gallery.html(gallery_html);
                    control_ids.val(gallery_ids);

                    // Set the file frame to null to reset.
                    file_frame = null;
                });

                // Finally, open the modal
                file_frame.open();

                e.preventDefault();
            });

            function synth_load_media_images(images) {
                if (images) {
                    var shortcode = new wp.shortcode({
                        tag: 'gallery',
                        attrs: {ids: images},
                        type: 'single'
                    });

                    var attachments = wp.media.gallery.attachments(shortcode);

                    var selection = new wp.media.model.Selection(attachments.models, {
                        props: attachments.props.toJSON(),
                        multiple: true
                    });

                    selection.gallery = attachments.gallery;

                    selection.more().done(function() {
                        // Break ties with the query.
                        selection.props.set({query: false});
                        selection.unmirror();
                        selection.props.unset('orderby');
                    });

                    return selection;
                }
                return false;
            }

            function build_synth_gallery_view(urls) {

                var html = '';

                for (var index = 0; index < urls.length; ++index) {
                    html += '<img src="' + urls[index] + '"/>';
                }
                return html;
            }

            jQuery(document.body).on('click', '.upload-image-remove', function(e) {

                var container = jQuery(this).parent('.synth-media-upload');

                container.find('.upload-image-text').val("");

                //remove the image from the control view
                container.find('.upload-image-view').hide();
                container.find('.upload-image-view').attr('src', 'null');

                e.preventDefault();
            });
        },
        nested_shortcode_control_initialize: function() {

            jQuery('.shortcode-item-add').click(function(e) {

                var html = jQuery('.synth-nested-shortcode-builder ul li:first').clone();

                jQuery('.synth-nested-shortcode-builder ul').append(html);
                e.preventDefault();
            });

            jQuery(document.body).on('click', '.shortcode-item-delete', function(e) {

                jQuery(this).closest('li').remove();
                e.preventDefault();
            });
        },
        image_radio_list_initialize: function() {

            jQuery(document.body).on('click', '.synth-image-radio-list-inline img', function() {

                jQuery('.synth-image-radio-list-inline img').each(function() {

                    jQuery(this).removeClass('selected');
                });

                jQuery(this).addClass('selected');
            });
        },
        background_selector_initialize: function() {

            jQuery(document.body).on('click', '.synth-background-selector-list-inline div', function() {

                jQuery('.synth-background-selector-list-inline div').each(function() {

                    jQuery(this).removeClass('selected');
                });

                jQuery(this).addClass('selected');
            });
        },
        extender_initialize: function() {

            jQuery('.synth-extender').focusout(function() {
                var extender = jQuery(this);
                extender_refresh(extender);
            });

            jQuery('.add-control').unbind('click').bind('click', function(e) {

                var extender = jQuery(this).parent('.synth-extender');
                var row = extender.find('ul .synth-extender-row:first').clone();

                extender.find('ul').append(row);
                extender_refresh(extender);
                e.preventDefault();
                e.stopPropagation();
            });

            jQuery(document.body).on('click', '.remove-control', function(e) {

                var extender = jQuery(this).closest('.synth-extender');
                jQuery(this).closest('.synth-extender-row').remove();

                extender_refresh(extender);
                e.preventDefault();
            });

            function extender_refresh(extender) {

                var index = 1;
                var control_count = 0;
                var extender_name = extender.data('name');

                extender.find('ul li').each(function() {

                    jQuery(this).find('input[type="text"],select').each(function() {

                        var data_id = jQuery(this).data('id');
                        this.name = extender_name + '_' + data_id + '_' + index;
                    });
                    index++;
                });

                control_count = index - 1;
                extender_build_object(extender, control_count);
            }

            function extender_build_object(extender, control_count) {

                var extender_id = extender.data('id');
                var extender_name = extender.data('name');

                var extender_list = {};
                var extender_items = {};
                var controls = {};
                var row_index = 0;
                var control_index = 0;

                extender.find('ul li').each(function() {

                    jQuery(this).find(':input, select').not('#' + extender_id).each(function() {

                        controls[control_index] = {
                            type: jQuery(this).getType(),
                            name: this.name,
                            value: jQuery(this).val()
                        };
                        control_index++;
                    });

                    extender_items[row_index] = controls;
                    row_index++;
                    controls = {};
                    control_index = 0;
                });

                extender_list = {
                    id: extender_id,
                    name: extender_name,
                    control_count: control_count,
                    extender_items: extender_items
                };

                //Formatted json
                //JSON.stringify(extender_list, null, "\t");
                extender.find('textarea').html(JSON.stringify(extender_list));
            }

            jQuery('.synth-extender').each(function() {

                var extender = jQuery(this);
                extender_refresh(extender);
            });
        },
        table_builder_initialize: function() {

            initialize();

            function initialize() {

                table_cell_click();
                table_cell_focus_out();
                table_select_change();
                jQuery('#insert_row').click(add_table_row);
                jQuery('#insert_column').click(add_table_column);
                jQuery(document.body).on('click', '.delete-row', delete_table_row);
                jQuery(document.body).on('click', '.delete-column', delete_table_column);
                jQuery(document.body).on('change', '.row-selector', table_row_select_change);
                jQuery(document.body).on('click', '.synth-shortcode-launcher .launch-button', table_launch_shortcode);
            }

            function table_cell_click() {

                jQuery(document.body).on('click', '.synth-table-cell', function() {

                    jQuery(this).closest('.synth-table').find('.synth-cell-content').show();
                    jQuery(this).closest('.synth-table').find('.text-content').hide();
                    jQuery(this).find('.synth-cell-content').hide();
                    jQuery(this).find('.text-content').show();
                    jQuery(this).find('.synth-shortcode-launcher').hide();
                });
            }

            function table_cell_focus_out() {

                jQuery(document.body).on('focusout', '.synth-table-cell', function() {

                    var table = jQuery(this).closest('.synth-table');
                    var content = jQuery(this).find('textarea').val();
                    jQuery(this).find('.synth-cell-content').html(content);
                    jQuery(this).find('.synth-cell-content').show();
                    jQuery(this).find('.text-content').hide();

                    build_table_row_shortcode(table);
                });
            }

            function add_table_row() {

                var table_builder = jQuery(this).closest('.synth-table-builder-control');
                var row_template = table_builder.find('.row-template').html();
                var cell_template = table_builder.find('.cell-template').html();

                var table = table_builder.find('.synth-table');
                var cells_html = '';
                var column_count = jQuery('#column_count').val();
                var row_count = jQuery('#row_count').val();

                for (var i = 0; i < column_count; i++) {

                    cells_html += cell_template;
                }

                var row_html = row_template.replace('{{table_cells}}', cells_html);
                table.find('.table-footer').before(row_html);

                row_count++;
                jQuery('#row_count').val(row_count);
            }

            function add_table_column() {

                var table_builder = jQuery(this).closest('.synth-table-builder-control');
                var header_cell_template = table_builder.find('.header-cell-template').html();
                var footer_cell_template = table_builder.find('.footer-cell-template').html();
                var cell_template = table_builder.find('.cell-template').html();

                var table = table_builder.find('.synth-table');
                var column_count = jQuery('#column_count').val();

                if (column_count < 6) {

                    column_count++;
                    jQuery('#column_count').val(column_count);

                    table.find('.synth-table-row').each(function() {

                        var is_content_cell = true;

                        if (jQuery(this).hasClass('table-header')) {

                            jQuery(this).find('.synth-table-cell.col-last').before(header_cell_template);
                            is_content_cell = false;
                        }

                        if (jQuery(this).hasClass('table-footer')) {

                            var footer_cell = footer_cell_template.replace('{{column_count}}', column_count);
                            jQuery(this).find('.synth-table-cell.col-last').before(footer_cell);
                            is_content_cell = false;
                        }

                        if (is_content_cell) {

                            jQuery(this).find('.synth-table-cell.col-last').before(cell_template);
                        }
                    });
                }

            }

            function delete_table_row(e) {

                jQuery(this).closest('.synth-table-row').remove();
                var row_count = jQuery('#row_count').val();
                row_count--;
                jQuery('#row_count').val(row_count);

                e.preventDefault();
            }

            function delete_table_column(e) {

                var table = jQuery(this).closest('.synth-table-builder-control').find('.synth-table');
                var column = jQuery(this).data('column');

                table.find('.synth-table-row').each(function() {
                    var index = column + 1;
                    jQuery(this).children().eq(index).remove();
                });

                var column_count = jQuery('#column_count').val();
                column_count--;
                jQuery('#column_count').val(column_count);

                e.preventDefault();
            }

            function table_select_change() {

                jQuery(document.body).on('change', '.synth-table select', function() {

                    var table = jQuery(this).closest('.synth-table');
                    build_table_row_shortcode(table);
                });
            }

            function table_row_select_change(e) {

                var row = jQuery(this).closest('.synth-table-row');
                var element = jQuery(this);
                var selected = element.val();

                if (selected === 'button-row') {

                    row.find('.synth-table-cell').each(function() {
                        jQuery(this).find('.synth-cell-content').hide();
                        jQuery(this).find('.text-content').hide();
                        jQuery(this).find('.synth-shortcode-launcher').show();
                    });
                }
                else {
                    row.find('.synth-table-cell').each(function() {
                        jQuery(this).find('.synth-cell-content').hide();
                        jQuery(this).find('.text-content').show();
                        jQuery(this).find('.synth-shortcode-launcher').hide();
                    });
                }
            }

            function build_table_row_shortcode(table) {

                var row_sc = '';
                var cells_sc = '';

                table.find('.synth-table-row').each(function() {

                    var is_content_row = true;

                    if (jQuery(this).hasClass('table-header')) {

                        is_content_row = false;
                    }

                    if (jQuery(this).hasClass('table-footer')) {

                        is_content_row = false;
                    }

                    if (is_content_row) {

                        var row_style = '';
                        var cells_sc = '';

                        jQuery(this).find('.synth-table-cell').each(function() {

                            var table_cell = jQuery(this);

                            if (table_cell.hasClass('col-one')) {
                                row_style = table_cell.find('select').val();
                            }

                            if (table_cell.hasClass('content')) {

                                var column_style = get_column_style(table, table_cell);
                                var cell_content = table_cell.find('.synth-cell-content').html();
                                var shortcode = table_cell.find('.shortcode-content').html();
                                cells_sc += '\n[syn_tcell style="' + column_style + '"]\n' + cell_content + shortcode + '\n[/syn_tcell]';
                            }

                        });

                        row_sc += '\n[syn_trow style="' + row_style + '"]' + cells_sc + '\n[/syn_trow]\n';
                    }

                });

                table.closest('.synth-table-builder-control').find('.table-shortcodes').text(row_sc);
            }

            function get_column_style(table, element) {

                var index = element.index();
                var column_style = table.find('.table-header .synth-table-cell').eq(index).find('select').val();
                return column_style;
            }

            function table_launch_shortcode(event) {

                var obj = this;

                var params = {
                    close_callback: function(shortcode) {

                        var table = jQuery(this).closest('.synth-table');
                        //jQuery.synth_controls.table_builder_initialize.build_table_row_shortcode(table);
                    }
                };

                var launchShortcode = new jQuery.synthShortcodeLauncher(jQuery(this), params);

                event.stopPropagation();
            }
        },
        tabs_builder_initialize: function() {

            initialize();

            function initialize() {

                jQuery('#insert_tab').click(add_tab);
                jQuery(document.body).on('hover', '.activate-modal', activate_tab);
                jQuery(document.body).on('click', '.activate-modal .tt-remove-control', remove_tab);
                jQuery(document.body).off('click', '.activate-modal', modal_tab_item);
                jQuery(document.body).on('click', '.activate-modal', modal_tab_item);
            }

            function add_tab() {

                var tab_builder = jQuery(this).closest('.synth-tabs-builder-control').find('.synth-tab-builder');
                var tabs_list = tab_builder.find('.synth-tab-header').find('ul');
                var index = tabs_list.children().length;
                index++;

                var tab_html = '<li class="activate-modal" data-tab="tab-' + index + '" draggable="true">' +
                        '<div class="tab-item">' +
                        '<span class="tt-handle"><span></span></span>' +
                        '<i class="tt-icon fa fa-question-circle fa-2x"></i>' +
                        '<span class="tt-text">Tab Name</span>' +
                        '<a class="tt-remove-control" href="javascript:void(0)"></a>' +
                        '</div>' +
                        '<textarea name="syn_tab_control_content"></textarea>' +
                        '</li>';

                var tab_content = '<div class="synth-tab-content" data-tab="tab-' + index + '">' +
                        '<textarea></textarea>' +
                        '</div>';

                tab_builder.find('.synth-tab-header').find('ul').append(tab_html);
                tab_builder.find('.synth-tab-panel').append(tab_content);
            }

            function activate_tab() {

                var tab_builder = jQuery(this).closest('.synth-tabs-builder-control').find('.synth-tab-builder');
                var tab_header = jQuery(this).closest('li');
                var index = tab_header.index();

                var is_active_class = false;

                if (tab_header.hasClass('active')) {
                    is_active_class = true;
                }

                tab_builder.find('.synth-tab-header ul li').removeClass('active');
                tab_builder.find('.synth-tab-panel .synth-tab-content').removeClass('active');

                tab_header.addClass('active');
                tab_builder.find('.synth-tab-panel .synth-tab-content').eq(index).addClass('active');
            }

            function remove_tab(e) {

                var tab_builder = jQuery(this).closest('.synth-tabs-builder-control').find('.synth-tab-builder');
                var tab_header = jQuery(this).closest('li');
                var index = tab_header.index();
                var is_active_class = false;

                if (tab_header.hasClass('active')) {
                    is_active_class = true;
                }

                tab_header.remove();
                tab_builder.find('.synth-tab-panel .synth-tab-content').eq(index).remove();

                tab_builder.find('.synth-tab-header').find('ul li').each(function() {

                    var index = jQuery(this).index();
                    var tab_item = jQuery(this);
                    var tab_content = tab_builder.find('.synth-tab-panel .synth-tab-content').eq(index);

                    var tab_index = index + 1;
                    tab_item.attr('data-tab', 'tab-' + tab_index);
                    tab_content.attr('data-tab', 'tab-' + tab_index);

                    if (index === 0 && is_active_class) {
                        tab_item.addClass('active');
                        tab_content.addClass('active');
                    }
                });

                e.stopPropagation();
            }

            function modal_tab_item(event) {

                var obj_item = jQuery(this);
                var config = synth_taurus_scodes.globals['synth_taurus_shortcodes_button'].config;
                var parent_shortcode_name = jQuery(this).closest('ul').data('parent-shortcode');
                var parent_shortcode = config[parent_shortcode_name];
                var nested_shortcode = config[parent_shortcode.nested_shortcode];
                var template = obj_item.find('textarea').html();

                var params = {
                    scope: this,
                    modal_title: nested_shortcode.heading,
                    modal_style: 'style="z-index: 10003; margin: 50px;"',
                    modal_button: nested_shortcode.modal_button,
                    modal_bg_style: 'style="z-index: 10002"',
                    modal_template: template,
                    ajax_hook: nested_shortcode.name,
                    load_callback: nested_shortcode.modal_support,
                    save_callback: function(values) {

                        var params = [];
                        var shortcode;

                        params = jQuery.synth_core.extract_shortcode_params(nested_shortcode.name, values);

                        var tab_header = obj_item;
                        var index = tab_header.index();
                        var tab_builder = tab_header.closest('.synth-tabs-builder-control').find('.synth-tab-builder');
                        var tab_content = tab_builder.find('.synth-tab-panel .synth-tab-content').eq(index);
                        tab_content.find('textarea').html(params.content);

                        //get the text part for the visual label. 
                        tab_header.find('.tt-text').html(params.title);
                        tab_header.find('.tt-icon').replaceWith('<i class="tt-icon fa ' + params.icon + ' fa-2x"></i>');

                        shortcode = jQuery.synth_core.create_shortcode_tag(config[parent_shortcode_name].nested_shortcode, params);
                        tab_header.find('textarea').html(shortcode);
                    }
                };

                new jQuery.synthModal(params);
                event.preventDefault();

            }
        },
        shortcode_launcher_initialize: function() {

            initialize();

            function initialize() {

                jQuery(document.body).on('click', '.synth-shortcode-launcher .launch-button', launch_shortcode);
            }

            function launch_shortcode() {

                var obj = jQuery(this);
                obj.next('.shortcode-content').focus();
                var shortcode_name = obj.parent().data('shortcode');
                var config = synth_taurus_scodes.globals['synth_taurus_shortcodes_button'].config;
                var shortcode = config[shortcode_name];

                var params = {
                    scope: this,
                    modal_title: shortcode.heading,
                    modal_style: 'style="z-index: 10003; margin: 50px;"',
                    modal_button: shortcode.modal_button,
                    modal_bg_style: 'style="z-index: 10002"',
                    modal_template: '',
                    ajax_hook: shortcode.name,
                    load_callback: shortcode.modal_support,
                    save_callback: function(values) {

                        var params = [];
                        var textarea = obj.next('.shortcode-content');

                        params = jQuery.synth_core.extract_shortcode_params(shortcode.name, values);
                        shortcode = jQuery.synth_core.create_shortcode_tag(shortcode.name, params);
                        textarea.html(shortcode);
                    }
                };

                new jQuery.synthModal(params);
                event.preventDefault();
            }
        },
        padding_initialize: function() {

            initialize();

            function initialize() {

                jQuery(document.body).on('change', '.synth-padding .padding-top', build_padding);
                jQuery(document.body).on('change', '.synth-padding .padding-right', build_padding);
                jQuery(document.body).on('change', '.synth-padding .padding-bottom', build_padding);
                jQuery(document.body).on('change', '.synth-padding .padding-left', build_padding);
            }

            function build_padding() {

                var $padding_control = jQuery(this).parent();
                var top = $padding_control.find('.padding-top').val();
                var right = $padding_control.find('.padding-right').val();
                var bottom = $padding_control.find('.padding-bottom').val();
                var left = $padding_control.find('.padding-left').val();
                var padding = top + ' ' + right + ' ' + bottom + ' ' + left;

                $padding_control.find('.padding-control').val(padding);
            }
        }
    };
})(jQuery, window, document);

jQuery.fn.getType = function() {
    return this[0].tagName === "INPUT" ? jQuery(this[0]).attr("type").toLowerCase() : this[0].tagName.toLowerCase();
};

jQuery(document).ready(function()
{
    jQuery.synth_controls = new jQuery.SynthControls();
});


