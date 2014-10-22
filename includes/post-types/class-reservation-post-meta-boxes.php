<?php

namespace syntaxthemes\restaurant;

/**
 * Description of class-reservation-post-meta-boxes
 *
 * @author Ryan
 */
class reservation_post_meta_boxes {

    /**
     * The post meta box constructor.
     */
    public function __construct() {

        add_action('add_meta_boxes', array($this, 'remove_meta_boxes'), 10);
        add_action('add_meta_boxes', array($this, 'rename_meta_boxes'), 20);
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'), 30);

        add_action('edit_form_after_title', array($this, 'metabox_positions'));

        add_action('init', array($this, 'init_meta_boxes'), 9999);
        add_filter('syn_restaurant_manager_meta_boxes', array($this, 'reservation_customer_details_meta_box'));
    }

    /**
     * Reorganize the metaboxes.
     * @global type $post
     * @global type $wp_meta_boxes
     */
    public function metabox_positions() {

        global $post, $wp_meta_boxes;

        do_meta_boxes(get_current_screen(), 'advanced', $post);
        unset($wp_meta_boxes[get_post_type($post)]['advanced']);
    }

    /**
     * Remove any unwanted metaboxes.
     */
    public function remove_meta_boxes() {
        
    }

    /**
     * Rename any metaboxes.
     */
    public function rename_meta_boxes() {
        
    }

    /**
     * Add enw metaboxes to the post type.
     */
    public function add_meta_boxes() {

        add_meta_box('submitdiv', __('Reservation Status', 'syn_restaurant_plugin'), array($this, 'reservation_status_meta_box'), 'syn_rest_reservation', 'side', 'default');
    }

    /**
     * Initialize the metaboxes.
     * @param type $meta_boxes
     */
    function init_meta_boxes($meta_boxes) {

        $meta_boxes = apply_filters('syn_restaurant_manager_meta_boxes', $meta_boxes);

        foreach ($meta_boxes as $meta_box) {
            new custom_meta_box($meta_box);
        }
    }

    /**
     * The customer details metabox.
     * @global type $syn_restaurant_config
     * @return boolean
     */
    public function reservation_customer_details_meta_box() {

        global $syn_restaurant_config;

        $meta_boxes = array();

        $meta_boxes[] = array(
            'id' => 'restaurant_customer_details_metabox',
            'title' => 'Customer Details',
            'pages' => array('syn_rest_reservation'), // post type
            'context' => 'advanced',
            'priority' => 'high',
            'show_names' => true, // Show field names on the left
            'fields' => array(
                array(
                    'id' => $syn_restaurant_config->plugin_prefix . 'first_name',
                    'name' => 'first_name',
                    'label' => 'First Name',
                    'desc' => __('Choose the header feature for your restaurant view.', 'syn_restaurant_plugin'),
                    'std' => '',
                    'data' => array(
                        'rule-required' => 'true',
                    ),
                    'template' => 'simple_template',
                    'type' => 'text'
                ),
                array(
                    'id' => $syn_restaurant_config->plugin_prefix . 'last_name',
                    'name' => 'last_name',
                    'label' => 'Last Name',
                    'desc' => __('Choose the header feature for your restaurant view.', 'syn_restaurant_plugin'),
                    'std' => '',
                    'data' => array(
                        'rule-required' => 'true',
                    ),
                    'template' => 'simple_template',
                    'type' => 'text'
                ),
                array(
                    'id' => $syn_restaurant_config->plugin_prefix . 'phone_number',
                    'name' => 'phone_number',
                    'label' => 'Phone Number',
                    'desc' => __('Choose the header feature for your restaurant view.', 'syn_restaurant_plugin'),
                    'std' => '',
                    'data' => array(
                        'rule-required' => 'true',
                    ),
                    'template' => 'simple_template',
                    'type' => 'text'
                ),
                array(
                    'id' => $syn_restaurant_config->plugin_prefix . 'email_address',
                    'name' => 'email_address',
                    'label' => 'Email Address',
                    'desc' => __('Choose the header feature for your restaurant view.', 'syn_restaurant_plugin'),
                    'std' => '',
                    'data' => array(
                        'rule-required' => 'true',
                        'rule-email' => 'true',
                        'msg-email' => __('Your email address which you have entered is in the incorrect format', 'syn_restaurant_plugin')
                    ),
                    'template' => 'simple_template',
                    'type' => 'text'
                ),
                array(
                    'id' => $syn_restaurant_config->plugin_prefix . 'guests_count',
                    'name' => 'guests_count',
                    'label' => 'Number of Guests',
                    'desc' => __('Choose the header feature for your restaurant view.', 'syn_restaurant_plugin'),
                    'std' => '',
                    'data' => array(
                        'rule-required' => 'true',
                    ),
                    'template' => 'simple_template',
                    'type' => 'text'
                ),
                array(
                    'id' => $syn_restaurant_config->plugin_prefix . 'reservation_date',
                    'name' => 'reservation_date',
                    'label' => 'Reservation Date',
                    'desc' => __('Choose the header feature for your restaurant view.', 'syn_restaurant_plugin'),
                    'std' => '',
                    'data' => array(
                        'rule-required' => 'true',
                    ),
                    'template' => 'simple_template',
                    'type' => 'datepicker'
                ),
                array(
                    'id' => $syn_restaurant_config->plugin_prefix . 'reservation_time',
                    'name' => 'reservation_time',
                    'label' => 'Reservation Time',
                    'desc' => __('Choose the header feature for your restaurant view.', 'syn_restaurant_plugin'),
                    'std' => '',
                    'data' => array(
                        'rule-required' => 'true',
                    ),
                    'template' => 'simple_template',
                    'type' => 'timepicker'
                )
            )
        );

        return $meta_boxes;
    }

    /**
     * The reservation status box for pending, confirmed and rejected.
     * @global type $action
     * @param \syntaxthemes\restaurant\type $post
     * @param type $metabox
     */
    public function reservation_status_meta_box($post, $metabox) {

        global $action;

        $post_type = $post->post_type;
        $post_type_object = get_post_type_object($post_type);
        $can_publish = current_user_can($post_type_object->cap->publish_posts);
        
        if($post->post_status === 'auto-draft' || $post->post_status === 'draft'){
           $post->post_status = 'pending'; 
        }
        ?>
        <div class="submitbox" id="submitpost">

            <div id="minor-publishing">

                <?php // Hidden submit button early on so that the browser chooses the right button when form is submitted with Return key   ?>
                <div style="display:none;">
                    <?php submit_button(__('Save'), 'button', 'save'); ?>
                </div>

                <div id="misc-publishing-actions">

                    <div class="misc-pub-section misc-pub-post-status"><label for="post_status"><?php _e('Status:') ?></label>
                        <span id="post-status-display">
                            <?php
                            switch ($post->post_status) {
                                case 'pending':
                                    _e('Pending Reservation');
                                    break;
                                case 'confirmed':
                                    _e('Confirmed Reservation');
                                    break;
                                case 'rejected':
                                    _e('Rejected Reservation');
                                    break;
                                default :
                                    _e('Pending Reservation');
                                    break;
                            }
                            ?>
                        </span>                   
                        <a href="#post_status" <?php if ('complete-reservation' == $post->post_status) { ?>style="display:none;" <?php } ?>class="edit-post-status hide-if-no-js"><span aria-hidden="true"><?php _e('Edit'); ?></span> <span class="screen-reader-text"><?php _e('Edit status'); ?></span></a>
                        <div id="post-status-select" class="hide-if-js">
                            <input type="hidden" name="hidden_post_status" id="hidden_post_status" value="<?php echo esc_attr(('pending' == $post->post_status ) ? 'pending' : $post->post_status); ?>" />
                            <select name='post_status' id='post_status'>
                                <option<?php selected($post->post_status, 'pending'); ?> value='pending'><?php _e('Pending Reservation') ?></option>
                                <option<?php selected($post->post_status, 'confirmed'); ?> value='confirmed'><?php _e('Confirm Reservation') ?></option>
                                <option<?php selected($post->post_status, 'rejected'); ?> value='rejected'><?php _e('Reject Reservation') ?></option>
                            </select>
                            <a href="#post_status" class="save-post-status hide-if-no-js button"><?php _e('OK'); ?></a>
                            <a href="#post_status" class="cancel-post-status hide-if-no-js button-cancel"><?php _e('Cancel'); ?></a>
                        </div>
                    </div><!-- .misc-pub-section -->

                    <?php
                    /* translators: Publish box date format, see http://php.net/date */
                    $datef = __('M j, Y @ G:i');

                    if (0 != $post->ID) {
                        if ('future' == $post->post_status) { // scheduled for publishing at a future date
                            $stamp = __('Scheduled for: <b>%1$s</b>');
                        } else if ('publish' == $post->post_status || 'private' == $post->post_status) { // already published
                            $stamp = __('Published on: <b>%1$s</b>');
                        } else if ('0000-00-00 00:00:00' == $post->post_date_gmt) { // draft, 1 or more saves, no date specified
                            $stamp = __('Publish <b>immediately</b>');
                        } else if (time() < strtotime($post->post_date_gmt . ' +0000')) { // draft, 1 or more saves, future date specified
                            $stamp = __('Schedule for: <b>%1$s</b>');
                        } else { // draft, 1 or more saves, date specified
                            $stamp = __('Publish on: <b>%1$s</b>');
                        }
                        $date = date_i18n($datef, strtotime($post->post_date));
                    } else { // draft (no saves, and thus no date specified)
                        $stamp = __('Publish <b>immediately</b>');
                        $date = date_i18n($datef, strtotime(current_time('mysql')));
                    }

                    if (!empty($args['args']['revisions_count'])) :
                        $revisions_to_keep = wp_revisions_to_keep($post);
                        ?>
                        <div class="misc-pub-section misc-pub-revisions">
                            <?php
                            if ($revisions_to_keep > 0 && $revisions_to_keep <= $args['args']['revisions_count']) {
                                echo '<span title="' . esc_attr(sprintf(__('Your site is configured to keep only the last %s revisions.'), number_format_i18n($revisions_to_keep))) . '">';
                                printf(__('Revisions: %s'), '<b>' . number_format_i18n($args['args']['revisions_count']) . '+</b>');
                                echo '</span>';
                            } else {
                                printf(__('Revisions: %s'), '<b>' . number_format_i18n($args['args']['revisions_count']) . '</b>');
                            }
                            ?>
                            <a class="hide-if-no-js" href="<?php echo esc_url(get_edit_post_link($args['args']['revision_id'])); ?>"><span aria-hidden="true"><?php _ex('Browse', 'revisions'); ?></span> <span class="screen-reader-text"><?php _e('Browse revisions'); ?></span></a>
                        </div>
                        <?php
                    endif;

                    if ($can_publish) : // Contributors don't get to choose the date of publish 
                        ?>
                        <div class="misc-pub-section curtime misc-pub-curtime">
                            <span id="timestamp">
                                <?php printf($stamp, $date); ?>
                            </span>                        
                            <div id="timestampdiv" class="hide-if-js">
                                <?php touch_time(($action == 'pending'), 1); ?>
                            </div>
                        </div>
                        <?php // /misc-pub-section  ?>

                    <?php endif; ?>

                    <?php
                    /**
                     * Fires after the post time/date setting in the Publish meta box.
                     *
                     * @since 2.9.0
                     */
                    do_action('post_submitbox_misc_actions');
                    ?>
                </div>
                <div class="clear"></div>
            </div>

            <div id="major-publishing-actions">
                <?php
                /**
                 * Fires at the beginning of the publishing actions section of the Publish meta box.
                 *
                 * @since 2.7.0
                 */
                do_action('post_submitbox_start');
                ?>
                <div id="delete-action">
                    <?php
                    if (current_user_can("delete_post", $post->ID)) {
                        if (!EMPTY_TRASH_DAYS)
                            $delete_text = __('Delete Permanently');
                        else
                            $delete_text = __('Move to Trash');
                        ?>
                        <a class="submitdelete deletion" href="<?php echo get_delete_post_link($post->ID); ?>"><?php echo $delete_text; ?></a><?php }
                    ?>
                </div>

                <div id="publishing-action">
                    <span class="spinner"></span>      
                    <?php
                    if (!in_array($post->post_status, array('pending', 'confirmed', 'rejected')) || 0 == $post->ID) {
                        if ($can_publish) {
                            ?>
                            <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Publish') ?>" />
                            <?php submit_button(__('Save Reservation'), 'primary button-large', 'pending', false, array('accesskey' => 'p')); ?>
                            <?php
                        }
                    } else {
                        ?>
                        <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Update') ?>" />
                        <input name="save" type="submit" class="button button-primary button-large" id="publish" accesskey="p" value="<?php esc_attr_e('Update Reservation') ?>" />
                    <?php } ?>

                </div>
                <div class="clear"></div>
            </div>
        </div>

        <?php
    }

    /**
     * The reservation statuses.
     * @return type
     */
    public function reservation_statuses() {

        $statuses = array(
            'draft' => _x('Draft Reservation', 'Reservation Status', 'syn_restaurant_plugin'),
            'pending' => _x('Pending Reservation', 'Reservation Status', 'syn_restaurant_plugin'),
            'confirmed' => _x('Confirm Reservation', 'Reservation Status', 'syn_restaurant_plugin'),
            'rejected' => _x('Reject Reservation', 'Reservation Status', 'syn_restaurant_plugin')
        );

        return $statuses;
    }

}

//Initialize the class.
new reservation_post_meta_boxes();
?>