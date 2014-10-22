<?php

/**
 * The admin menu for the restaurant plugin is created here.
 * @global type $syn_restaurant_config
 */
function syn_restaurant_manager_admin_menu() {

    global $syn_restaurant_config;

    if (current_user_can('manage_options')) {

        add_menu_page('Restaurant', 'Restaurant', 'manage_options', 'syn_restaurant_manager', null, null);

        //create submenu items
        add_submenu_page('syn_restaurant_manager', __('Settings', 'syn_restaurant_plugin'), __('Settings', 'syn_restaurant_plugin'), 'manage_options', 'syn_restaurant_manager_settings', 'syn_restaurant_manager_settings_page');
    }
}

add_action('admin_menu', 'syn_restaurant_manager_admin_menu');

/**
 * The settings page is created here. A tabbed navigation control has also been created 
 * for these pages.
 */
function syn_restaurant_manager_settings_page() {

    $tab = (isset($_REQUEST['tab']) && $_REQUEST['tab'] !== null) ? $_REQUEST['tab'] : 'general';
    ?>

    <div class="wrap">
        <div class="nav-tab-container">
            <h2 class="nav-tab-wrapper">
                <a <?php echo ($tab === 'general' ? 'class="nav-tab nav-tab-active"' : 'class="nav-tab"') ?> href="admin.php?page=syn_restaurant_manager_settings&amp;tab=general"><?php _e('General', 'syn_restaurant_plugin') ?></a>
                <a <?php echo ($tab === 'reservation_schedule' ? 'class="nav-tab nav-tab-active"' : 'class="nav-tab"') ?> href="admin.php?page=syn_restaurant_manager_settings&amp;tab=reservation_schedule"><?php _e('Reservation Schedule', 'syn_restaurant_plugin') ?></a>
                <a <?php echo ($tab === 'notifications' ? 'class="nav-tab nav-tab-active"' : 'class="nav-tab"') ?> href="admin.php?page=syn_restaurant_manager_settings&amp;tab=notifications"><?php _e('Notifications', 'syn_restaurant_plugin') ?></a>
            </h2>  
            <?php if ($tab === 'general') { ?>
                <div class="nav_tab_content">             
                    <?php syntaxthemes_general_page() ?>
                </div> 
            <?php } ?>
            <?php if ($tab === 'reservation_schedule') { ?>
                <div class="nav_tab_content">
                    <?php syntaxthemes_reservation_schedule_page() ?>
                </div>
            <?php } ?>
            <?php if ($tab === 'notifications') { ?>
                <div class="nav_tab_content">
                    <?php syntaxthemes_notifications_page() ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php
}

/**
 * The general settings page.
 * @global type $syn_restaurant_config
 */
function syntaxthemes_general_page() {

    global $syn_restaurant_config;

    do_action('syn_restaurant_manager_process_form');

    $session = new syntaxthemes\restaurant\syn_session();

    $group_size = get_option($syn_restaurant_config->plugin_prefix . 'group_size', '');
    $reservation_success_message = get_option($syn_restaurant_config->plugin_prefix . 'reservation_success_message', '');
    $restaurant_telephone = get_option($syn_restaurant_config->plugin_prefix . 'restaurant_telephone', '');

    if (empty($reservation_success_message)) {
        $reservation_success_message = __('Thank you, We have successfully received your booking request.  Your booking is awaiting to be confirmed with us.  We will send you updates to the email address provided.');
    }
    ?>
    <h3>General Settings</h3>
    <form id="syn_restaurant_manager_general_form" action="<?php $session->current_page_url(true) ?>" method="POST">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">Maximum Group Size</th>
                    <td>
                        <input class="regular-text" id="group_size" name="group_size" type="text" value="<?php echo $group_size ?>" placeholder="<?php _e('No limit', 'syn_restaurant_plugin') ?>">
                        <p class="description">Set your maximum allowed size for your group bookings.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Reservation Success Message</th><td>
                        <textarea class="large-text" id="reservation_success_message" name="reservation_success_message" rows="6"><?php echo $reservation_success_message ?></textarea>
                        <p class="description">Enter the message which is displayed when a reservation is made.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Restaurant Telephone Number</th>
                    <td>
                        <input class="regular-text" id="restaurant_telephone" name="restaurant_telephone" type="text" value="<?php echo $restaurant_telephone ?>" />
                        <p class="description">Set your restaurant telephone contact number.</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <?php wp_nonce_field('save_general_settings'); ?>
            <input type="hidden" name="form_action" value="save_general_settings">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'syn_restaurant_plugin') ?>">
        </p>
    </form>
    <?php
}

/**
 * The reservation schedule settings page.
 * @global type $syn_restaurant_config
 */
function syntaxthemes_reservation_schedule_page() {

    global $syn_restaurant_config;

    do_action('syn_restaurant_manager_process_form');

    $session = new syntaxthemes\restaurant\syn_session();
    ?>
    <h3>Reservation Schedule Settings</h3>
    <form id="syn_restaurant_manager_schedule_form" action="<?php $session->current_page_url(true) ?>" method="POST">
        <table class="form-table">
            <tbody>
                <tr id="scheduler">
                    <th scope="row"><?php _e('Schedule', 'syn_restaurant_plugin') ?></th>
                    <td>
                        <div id="schedule_container">
                            <?php
                            syn_restaurant_manager_get_scheduler();
                            ?>
                        </div>
                        <a id="add_schedule_button" class="button" href="javascript:void(0)">Add New Schedule</a>
                        <p class="description">Set your days and times for your schedule.  You can have multiple schedules.</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <script id="schedule_template" type="text/template">
    <?php
    $parameters = array(
        'days_text' => '',
        'time_text' => 'Schedule your days and times',
        'monday' => '',
        'tuesday' => '',
        'wednesday' => '',
        'thursday' => '',
        'friday' => '',
        'saturday' => '',
        'sunday' => '',
        'start_time' => '',
        'end_time' => '',
        'content_open' => ''
    );
    syn_restaurant_manager_schedule_template($parameters);
    ?>
        </script>
        <p class="submit">
            <?php wp_nonce_field('save_schedule_settings'); ?>
            <input type="hidden" name="form_action" value="save_schedule_settings">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'syn_restaurant_plugin') ?>">
        </p>
    </form>
    <?php
}

/**
 * The notifications settings page.
 * @global type $syn_restaurant_config
 */
function syntaxthemes_notifications_page() {

    global $syn_restaurant_config;

    do_action('syn_restaurant_manager_process_form');

    $session = new syntaxthemes\restaurant\syn_session();

    $reply_to_name = get_option($syn_restaurant_config->plugin_prefix . 'reply_to_name', '');
    $reply_to_email = get_option($syn_restaurant_config->plugin_prefix . 'reply_to_email', '');
    $admin_email_subject = get_option($syn_restaurant_config->plugin_prefix . 'admin_email_subject', '');
    $admin_email = get_option($syn_restaurant_config->plugin_prefix . 'admin_email', '');
    $reservation_email_subject = get_option($syn_restaurant_config->plugin_prefix . 'reservation_email_subject', '');
    $reservation_email = get_option($syn_restaurant_config->plugin_prefix . 'reservation_email', '');
    $reservation_confirmed_email_subject = get_option($syn_restaurant_config->plugin_prefix . 'reservation_confirmed_email_subject', '');
    $reservation_confirmed_email = get_option($syn_restaurant_config->plugin_prefix . 'reservation_confirmed_email', '');
    $reservation_rejected_email_subject = get_option($syn_restaurant_config->plugin_prefix . 'reservation_rejected_email_subject', '');
    $reservation_rejected_email = get_option($syn_restaurant_config->plugin_prefix . 'reservation_rejected_email', '');
    ?>
    <h3>Notification Settings</h3>
    <form id="syn_restaurant_manager_notifications_form" action="<?php $session->current_page_url(true) ?>" method="POST">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">Reply To Name</th>
                    <td>
                        <input id="reply_to_name" class="regular-text" name="reply_to_name" type="text" placeholder="<?php bloginfo('name') ?>" value="<?php echo $reply_to_name ?>" />
                        <p class="description">Set the reply to email name.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Reply To Email</th>
                    <td>
                        <input id="reply_to_email" class="regular-text" name="reply_to_email" type="text" placeholder="<?php bloginfo('admin_email') ?>" value="<?php echo $reply_to_email ?>" />
                        <p class="description">Set the reply to email address.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Administrator Email Subject</th>
                    <td>
                        <input id="admin_email_subject" class="regular-text" name="admin_email_subject" type="text" placeholder="<?php _e('You have a booking request', 'syn_restaurant_plugin') ?>" value="<?php echo $admin_email_subject ?>" />
                        <p class="description">Set the administrator email subject.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Administrator Email</th>
                    <td>
                        <?php
                        $admin_email_settings = array(
                            'textarea_name' => 'admin_email',
                            'textarea_rows' => 10,
                            'media_buttons' => false,
                            'teeny' => true,
                            'wpautop' => true
                        );

                        wp_editor($admin_email, 'admin_email', $admin_email_settings);
                        ?>
                        <p class="description">Create the email which your administrator will receive.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">New Reservation Email Subject</th>
                    <td>
                        <input id="new_reservation_email_subject" class="regular-text" name="reservation_email_subject" type="text" placeholder="<?php _e('You have a booking request', 'syn_restaurant_plugin') ?>" value="<?php echo $reservation_email_subject ?>" />
                        <p class="description">Set the new reservation email subject.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">New Reservation Email</th>
                    <td>
                        <?php
                        $reservation_email_settings = array(
                            'textarea_name' => 'reservation_email',
                            'textarea_rows' => 10,
                            'media_buttons' => false,
                            'teeny' => true,
                            'wpautop' => true
                        );

                        wp_editor($reservation_email, 'new_reservation_email', $reservation_email_settings);
                        ?>
                        <p class="description">Create the email which your customer will receive.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Reservation Confirmed Email Subject</th>
                    <td>
                        <input id="reservation_confirmed_email_subject" class="regular-text" name="reservation_confirmed_email_subject" type="text" placeholder="<?php _e('Your reservation request has been confirmed', 'syn_restaurant_plugin') ?>" value="<?php echo $reservation_confirmed_email_subject ?>" />
                        <p class="description">Set the new reservation email subject.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Reservation Confirmed Email</th>
                    <td>
                        <?php
                        $reservation_confirmed_email_settings = array(
                            'textarea_name' => 'reservation_confirmed_email',
                            'textarea_rows' => 10,
                            'media_buttons' => false,
                            'teeny' => true,
                            'wpautop' => true
                        );

                        wp_editor($reservation_confirmed_email, 'reservation_confirmed_email', $reservation_confirmed_email_settings);
                        ?>
                        <p class="description">Create the email which your customer will receive.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Reservation Rejected Email Subject</th>
                    <td>
                        <input id="reservation_rejected_email_subject" class="regular-text" name="reservation_rejected_email_subject" type="text" placeholder="<?php _e('Unfortunately we cannot make your reservation', 'syn_restaurant_plugin') ?>" value="<?php echo $reservation_rejected_email_subject ?>" />
                        <p class="description">Set the email subject for reservation rejections.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Reservation Rejected Email</th>
                    <td>
                        <?php
                        $reservation_rejected_email_settings = array(
                            'textarea_name' => 'reservation_rejected_email',
                            'textarea_rows' => 10,
                            'media_buttons' => false,
                            'teeny' => true,
                            'wpautop' => true
                        );

                        wp_editor($reservation_rejected_email, 'reservation_rejected_email', $reservation_rejected_email_settings);
                        ?>
                        <p class="description">Create the email which your customer will receive.</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <?php wp_nonce_field('save_notification_settings'); ?>
            <input type="hidden" name="form_action" value="save_notification_settings">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'syn_restaurant_plugin') ?>">
        </p>
    </form>
    <?php
}
?>