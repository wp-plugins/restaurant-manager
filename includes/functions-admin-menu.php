<?php

/**
 * The admin menu for the restaurant plugin is created here.
 * @global type $syn_restaurant_config
 */
function syn_restaurant_manager_admin_menu() {

    global $syn_restaurant_config;

    if (current_user_can('manage_options')) {

        $parent_menu_slug = 'edit.php?post_type=syn_rest_meal';

        //Settings for the custom admin
        $page_title = 'Restaurant';
        $menu_title = 'Restaurant';
        $capability = 'manage_options';
        $menu_slug = $parent_menu_slug;
        $function = null; // Callback function which displays the page content.
        $icon_url = 'dashicons-syntaxstudio';
        $position = 27.15496;

        //Add custom admin menu
        add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);

        $submenu_pages = array(
            // Avoid duplicate pages. Add submenu page with same slug as parent slug.            
//            array(
//                'parent_slug' => $parent_menu_slug,
//                'page_title' => 'Dashboard',
//                'menu_title' => 'Dashboard',
//                'capability' => 'manage_options',
//                'menu_slug' => $parent_menu_slug,
//                'function' => 'syn_restaurant_manager_add_ons_page', // Uses the same callback function as parent menu. 
//            ),
            // Post Type :: View Meals
            array(
                'parent_slug' => $parent_menu_slug,
                'page_title' => 'All Meals',
                'menu_title' => 'Meals',
                'capability' => 'manage_options',
                'menu_slug' => 'edit.php?post_type=syn_rest_meal',
                'function' => null, // Uses the same callback function as parent menu. 
            ),
            // Post Type :: Add New Meal
            array(
                'parent_slug' => $parent_menu_slug,
                'page_title' => 'Add New Meal',
                'menu_title' => 'Add Meal',
                'capability' => 'manage_options',
                'menu_slug' => 'post-new.php?post_type=syn_rest_meal',
                'function' => null, // Doesn't need a callback function.
            ),
            array(
                'parent_slug' => $parent_menu_slug,
                'page_title' => 'Menus',
                'menu_title' => 'Menus',
                'capability' => 'manage_options',
                'menu_slug' => 'edit-tags.php?taxonomy=syn_rest_menu&post_type=syn_rest_meal',
                'function' => null, // Uses the same callback function as parent menu. 
            ),
            array(
                'parent_slug' => $parent_menu_slug,
                'page_title' => 'Courses',
                'menu_title' => 'Courses',
                'capability' => 'manage_options',
                'menu_slug' => 'edit-tags.php?taxonomy=syn_rest_course&post_type=syn_rest_meal',
                'function' => null, // Uses the same callback function as parent menu. 
            ),
            array(
                'parent_slug' => $parent_menu_slug,
                'page_title' => 'Diets',
                'menu_title' => 'Diets',
                'capability' => 'manage_options',
                'menu_slug' => 'edit-tags.php?taxonomy=syn_rest_diet&post_type=syn_rest_meal',
                'function' => null, // Uses the same callback function as parent menu. 
            ),
            array(
                'parent_slug' => $parent_menu_slug,
                'page_title' => 'Cuisines',
                'menu_title' => 'Cuisines',
                'capability' => 'manage_options',
                'menu_slug' => 'edit-tags.php?taxonomy=syn_rest_cuisine&post_type=syn_rest_meal',
                'function' => null, // Uses the same callback function as parent menu. 
            ),
            array(
                'parent_slug' => $parent_menu_slug,
                'page_title' => 'All Reservations',
                'menu_title' => 'Reservations',
                'capability' => 'manage_options',
                'menu_slug' => 'edit.php?post_type=syn_rest_reservation',
                'function' => null, // Uses the same callback function as parent menu. 
            ),
            array(
                'parent_slug' => $parent_menu_slug,
                'page_title' => 'Settings',
                'menu_title' => 'Settings',
                'capability' => 'manage_options',
                'menu_slug' => 'syn_restaurant_manager_settings',
                'function' => 'syn_restaurant_manager_settings_page', // Uses the same callback function as parent menu. 
            ),
            array(
                'parent_slug' => $parent_menu_slug,
                'page_title' => 'Add-ons',
                'menu_title' => 'Add-ons',
                'capability' => 'manage_options',
                'menu_slug' => 'syn_restaurant_manager_add_ons',
                'function' => 'syn_restaurant_manager_add_ons_page', // Uses the same callback function as parent menu. 
            ),
        );

        // Add each submenu item to custom admin menu.
        foreach ($submenu_pages as $submenu) {

            add_submenu_page($submenu['parent_slug'], $submenu['page_title'], $submenu['menu_title'], $submenu['capability'], $submenu['menu_slug'], $submenu['function']);
        }
    }
}

add_action('admin_menu', 'syn_restaurant_manager_admin_menu');

if (!function_exists('syn_restaurant_manager_set_current_menu')) {

    function syn_restaurant_manager_set_current_menu($parent_file) {

        global $submenu_file, $current_screen, $pagenow;

        // Set the submenu as active/current while anywhere in your Custom Post Type (nwcm_news)
        if ($current_screen->post_type == 'syn_rest_meal') {

            if ($pagenow == 'post.php') {
                $submenu_file = 'edit.php?post_type=' . $current_screen->post_type;
            }

            if ($pagenow == 'edit-tags.php') {
                $submenu_file = 'edit-tags.php?taxonomy=' . $current_screen->taxonomy . '&post_type=' . $current_screen->post_type;
            }

            //$parent_file = 'syn_restaurant_manager';
        }

        return $parent_file;
    }

    add_filter('parent_file', 'syn_restaurant_manager_set_current_menu');
}

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
                <?php
                apply_filters('syn_restaurant_manager_add_setting_tab_navigation', $tab);
                ?>
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
            <?php
            apply_filters('syn_restaurant_manager_add_setting_tab', $tab);
            ?>
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

    $session = new syntaxthemes\restaurant\session();

    $group_size = get_option($syn_restaurant_config->plugin_prefix . 'group_size', '');
    $reservation_success_message = get_option($syn_restaurant_config->plugin_prefix . 'reservation_success_message', '');
    $restaurant_telephone = get_option($syn_restaurant_config->plugin_prefix . 'restaurant_telephone', '');
    $currency_symbol = get_option($syn_restaurant_config->plugin_prefix . 'currency_symbol', '£');
    $country_code = get_option($syn_restaurant_config->plugin_prefix . 'country_code', null);

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
                <tr>
                    <th scope="row">
                        <label for="country_code"><?php _e('Country Code', 'syn_restaurant_sms_plugin') ?></label>
                    </th>
                    <td>
                        <?php
                        $country_codes = syn_restaurant_manager_country_codes();
                        ?>
                        <select id="country_code" name="country_code">
                            <?php foreach ($country_codes as $code => $country) { ?>
                                <option value="<?php echo $code ?>" <?php selected($country_code, $code, true) ?>><?php echo $country ?></option>
                            <?php } ?>
                        </select>
                        <p class="description">Set your country code for your restaurant location.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"> 
                        <label for="currency_symbol"><?php _e('Currency Symbol', 'syn_restaurant_plugin') ?></label>
                    </th>
                    <td>
                        <input id="currency_symbol" class="regular-text" name="currency_symbol" type="text" value="<?php echo $currency_symbol ?>" /> 
                        <p class="description"><?php _e('Set the currency symbol for your menu prices.', 'syn_restaurant_plugin') ?></p>
                    </td>
                </tr>
    <!--                <tr>
                    <th scope="row"> 
                        <label for="date_format"><?php _e('Date Format', 'syn_restaurant_plugin') ?></label>
                    </th>
                    <td>
                        <select id="date_format" name="date_format">
                            <option value="F j, Y"<?php selected($date_format, 'F j, Y', true) ?>><?php echo date('F j, Y') ?></option>
                            <option value="j F, Y"<?php selected($date_format, 'j F, Y', true) ?>><?php echo date('j F, Y') ?></option>
                            <option value="m/d/Y"<?php selected($date_format, 'm/d/Y', true) ?>><?php echo date('m/d/Y') ?></option>
                            <option value="d/m/Y"<?php selected($date_format, 'd/m/Y', true) ?>><?php echo date('d/m/Y') ?></option>
                        </select>
                        <p class="description"><?php _e('Set the date format for dates displayed in your emails, bookings and reservation table.', 'syn_restaurant_plugin') ?></p>
                    </td>
                </tr>-->
    <!--                <tr>
                    <th scope="row"> 
                        <label for="time_format"><?php _e('Time Format', 'syn_restaurant_plugin') ?></label>
                    </th>
                    <td>
                        <select id="time_format" name="time_format">
                            <option value="g:i a"<?php selected($time_format, 'g:i a', true) ?>><?php echo date('g:i a') ?></option>
                            <option value="g:i A"<?php selected($time_format, 'g:i A', true) ?>><?php echo date('g:i A') ?></option>
                            <option value="G:i a"<?php selected($time_format, 'G:i a', true) ?>><?php echo date('G:i a') ?></option>
                            <option value="G:i A"<?php selected($time_format, 'G:i A', true) ?>><?php echo date('G:i A') ?></option>
                            <option value="h:i a"<?php selected($time_format, 'h:i a', true) ?>><?php echo date('h:i a') ?></option>
                            <option value="h:i A"<?php selected($time_format, 'h:i A', true) ?>><?php echo date('h:i A') ?></option>
                            <option value="H:i a"<?php selected($time_format, 'H:i a', true) ?>><?php echo date('H:i a') ?></option>
                            <option value="H:i A"<?php selected($time_format, 'H:i A', true) ?>><?php echo date('H:i A') ?></option>                            
                            <option value="H:i"<?php selected($time_format, 'H:i', true) ?>><?php echo date('H:i') ?></option>
                        </select>
                        <p class="description"><?php _e('Set the time format for dates displayed in your emails, bookings and reservation table.', 'syn_restaurant_plugin') ?></p>
                    </td>
                </tr>-->
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

    $session = new syntaxthemes\restaurant\session();
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
        'content_open' => ' add-schedule'
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

    $session = new syntaxthemes\restaurant\session();

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

/**
 * The restaurant manager addons page.
 */
function syn_restaurant_manager_add_ons_page() {
    ?>
    <div class="wrap">
        <h2>Add-ons</h2>
        <div id="syn_restaurant_manager_addons">
            <ul class="addon-list">
                <li class="addon-item">
                    <div class="addon-block">                
                        <h3 class="addon-title">Taurus Restaurant Theme</h3>
                        <div class="addon-content">
                            <img class="addon-image" src="http://www.syntaxthemes.co.uk/wp-content/uploads/2014/11/taurus-theme-thumbnail.jpg" />
                            <p>
                                Taurus Theme has been designed to work beautifully with our Restaurant Manager plugin.  It has styled the reservation form nicely with the
                                theme layout.  Check out the <a href="http://www.syntaxthemes.co.uk/exhibitions?theme=taurus-theme" target="_blank">demo</a>. 
                            </p>
                        </div> 
                        <div class="addon-footer">
                            <a class="read-more-link" href="https://creativemarket.com/syntaxthemes/109450-Taurus-Restaurant-Responsive-Theme" alt="Read more about this plugin" target="_blank"><?php _e('Read More', '') ?></a>
                            <a class="buy-now button-primary" href="https://creativemarket.com/syntaxthemes/109450-Taurus-Restaurant-Responsive-Theme" alt="Buy this plugin" target="_blank"><?php _e('Buy Now $59', '') ?></a>
                        </div>
                    </div>
                </li>
                <li class="addon-item">                                          
                </li>
                <li class="addon-item">                                         
                </li>
            </ul>
        </div>
        <div id="syn_restaurant_manager_addons">
            <ul class="addon-list">
                <li class="addon-item">
                    <div class="addon-block">                
                        <h3 class="addon-title">Restaurant MailChimp Subscribe User</h3>
                        <div class="addon-content">
                            <img class="addon-image" src="http://www.syntaxthemes.co.uk/wp-content/uploads/2014/11/mailchimp-logo.png" />
                            <p>
                                This MailChimp plugin allows you to quickly and easily add a signup checkbox to your reservation form. 
                                This allows your customers to signup to your services by allowing their email to be added to your MailChimp list. 
                                Its is very easy to use, just install and on the settings tab enter your MailChimp account API Key and choose your email list.
                            </p>
                        </div> 
                        <div class="addon-footer">
                            <a class="read-more-link" href="http://www.syntaxthemes.co.uk/shop/plugins/restaurant-mailchimp-subscribe/" alt="Read more about this plugin" target="_blank"><?php _e('Read More', '') ?></a>
                            <a class="buy-now button-primary" href="http://www.syntaxthemes.co.uk/shop/plugins/restaurant-mailchimp-subscribe/" alt="Buy this plugin" target="_blank"><?php _e('Buy Now $19.99', '') ?></a>
                        </div>
                    </div>
                </li>
                <li class="addon-item">
                    <div class="addon-block">                
                        <h3 class="addon-title">Customer Email Support</h3>
                        <div class="addon-content">
                            <img class="addon-image" src="http://www.syntaxthemes.co.uk/wp-content/uploads/2014/11/restaurant-emails-logo-450x450.jpg" />
                            <p>
                                Now you can email your customers directly from their saved reservation booking record.  All emails sent are
                                recorded on the booking so you can keep track of communication sent from your restaurant. Improve your customer
                                relations without the need for external email clients.
                            </p>
                        </div> 
                        <div class="addon-footer">
                            <a class="read-more-link" href="http://www.syntaxthemes.co.uk/shop/plugins/restaurant-customer-emailer/" alt="Read more about this plugin" target="_blank"><?php _e('Read More', '') ?></a>
                            <a class="buy-now button-primary" href="http://www.syntaxthemes.co.uk/shop/plugins/restaurant-customer-emailer/" alt="Buy this plugin" target="_blank"><?php _e('Buy Now $19.99', '') ?></a>
                        </div>
                    </div>                       
                </li>
                <li class="addon-item">
                    <div class="addon-block">                
                        <h3 class="addon-title">Customer SMS Support</h3>
                        <div class="addon-content">
                            <img class="addon-image" src="http://www.syntaxthemes.co.uk/wp-content/uploads/2014/11/restaurant-sms-logo-450x450.jpg" />
                            <p>
                                Now you can send SMS directly to your customer from your reservation screen. All SMS messages are recorded in your reservation screen
                                so that you can see what has been sent to your customers. You will need a Nexmo SMS account this is free to set up and you will
                                receive some free credit to try.  Check out <a href="https://www.nexmo.com/" title="Nexmo SMS" target="_blank">Nexmo SMS</a>
                            </p>
                        </div> 
                        <div class="addon-footer">
                            <a class="read-more-link" href="http://www.syntaxthemes.co.uk/shop/plugins/customer-sms-support/" alt="Read more about this plugin" target="_blank"><?php _e('Read More', '') ?></a>
                            <a class="buy-now button-primary" href="http://www.syntaxthemes.co.uk/shop/plugins/customer-sms-support/" alt="Buy this plugin" target="_blank"><?php _e('Buy Now $19.99', '') ?></a>
                        </div>
                    </div>                       
                </li>
            </ul>
        </div>
    </div>
    <?php
}
?>