<?php

/**
 * This is the default template for the admin notification email.
 * @return string
 */
function syn_restaurant_manager_default_admin_email() {

    $template = '<p>
                    Dear Administrator
                </p>
                <p>
                    A new booking request has been made at %site_name%
                </p>
                <p>
                    <strong>Customer Details:</strong>
                    %first_name% %last_name%
                    For: %guests_count% people
                </p>
                <p>    
                    <strong>Contact Details:</strong>
                    Telephone: %telephone%
                    Email: %email_address%
                </p>
                <p>
                    <strong>Reservation Details:</strong>
                    Date: %reservation_date% at %reservation_time%
                </p>
                <p>
                    Thank you.
                </p>
                <p>
                    <em>Reservation created at %current_time% sent by %site_link%<em>
                </p>';

    return $template;
}

/**
 * This is the default reservation email for a new booking.
 * @return string
 */
function syn_restaurant_manager_default_reservation_email() {

    $template = '<p>
                    Dear %first_name% %last_name%
                </p>
                <p>
                    You have requested a table reservation with %site_name%.  Please await your confirmation email from us to confirm that your table has been reserved.
                </p>
                <p>
                    <strong>Customer Details:</strong>
                    %first_name% %last_name%
                    For: %guests_count% people
                </p>
                <p>
                    <strong>Contact Details:</strong>
                    Telephone: %telephone%
                    Email: %email_address%
                </p>
                <p>
                    <strong>Reservation Details:</strong>
                    Date: %reservation_date% at %reservation_time%
                </p>
                <p>
                    Thank you.
                </p>
                <p>
                    <em>Reservation created at %current_time% sent by %site_link%<em>
                </p>';

    return $template;
}

/**
 * The default email when a reservation has been confirmed.
 * @return string
 */
function syn_restaurant_manager_default_reservation_confirmed_email() {

    $template = '<p>
                    Dear %first_name% %last_name%
                </p>
                <p>
                    This is your confirmation email for %site_name%.  We look forward to seeing you soon.  Please contact us if you need to on %restaurant_telephone%
                </p>
                <p>
                    <strong>Customer Details:</strong>
                    %first_name% %last_name%
                    For: %guests_count% people
                </p>
                <p>
                    <strong>Contact Details:</strong>
                    Telephone: %telephone%
                    Email: %email_address%
                </p>
                <p>
                    <strong>Reservation Details:</strong>
                    Date: %reservation_date% at %reservation_time%
                </p>
                <p>
                 Thank you.
                </p>
                <p>
                   Reservation created at %current_time% sent by %site_link%
                </p>';

    return $template;
}

/**
 * The default email when a reservation has been rejected.
 * @return string
 */
function syn_restaurant_manager_default_reservation_rejected_email() {

    $template = '<p>
                    Dear %first_name% %last_name%
                </p>
                <p>
                    We unfortunately cannot make your reservation at this time.  We are very sorry about this please call us on %restaurant_telephone% to make a new booking or another arrangement.
                </p>
                <p>
                    <strong>Customer Details:</strong>
                    %first_name% %last_name%
                    For: %guests_count% people
                </p>
                <p>
                    <strong>Contact Details:</strong>
                    Telephone: %telephone%
                    Email: %email_address%
                </p>
                <p>
                    <strong>Reservation Details:</strong>
                    Date: %reservation_date% at %reservation_time%
                </p>
                <p>
                    Thank you.
                </p>
                <p>
                    Reservation created at %current_time% sent by %site_link%
                </p>';

    return $template;
}

/**
 * This function will get the schedular objects and template.  The schedular
 * object will map to the template and produce the scheduler view.
 * @global type $syn_restaurant_config
 */
function syn_restaurant_manager_get_scheduler() {

    global $syn_restaurant_config;

    $scheduler = get_option($syn_restaurant_config->plugin_prefix . 'scheduler', null);

    if (!empty($scheduler)) {

        foreach ($scheduler as $key => $schedule) {

            $monday = (isset($schedule['weekday']['monday'])) ? $schedule['weekday']['monday'] : false;
            $tuesday = (isset($schedule['weekday']['tuesday'])) ? $schedule['weekday']['tuesday'] : false;
            $wednesday = (isset($schedule['weekday']['wednesday'])) ? $schedule['weekday']['wednesday'] : false;
            $thursday = (isset($schedule['weekday']['thursday'])) ? $schedule['weekday']['thursday'] : false;
            $friday = (isset($schedule['weekday']['friday'])) ? $schedule['weekday']['friday'] : false;
            $saturday = (isset($schedule['weekday']['saturday'])) ? $schedule['weekday']['saturday'] : false;
            $sunday = (isset($schedule['weekday']['sunday'])) ? $schedule['weekday']['sunday'] : false;

            $starttime = (isset($schedule['timeslot']['starttime'])) ? $schedule['timeslot']['starttime'] : false;
            $endtime = (isset($schedule['timeslot']['endtime'])) ? $schedule['timeslot']['endtime'] : false;

            $monday_text = (isset($schedule['weekday']['monday'])) ? __('Mon') : null;
            $tuesday_text = (isset($schedule['weekday']['tuesday'])) ? __('Tue') : null;
            $wednesday_text = (isset($schedule['weekday']['wednesday'])) ? __('Wed') : null;
            $thursday_text = (isset($schedule['weekday']['thursday'])) ? __('Thu') : null;
            $friday_text = (isset($schedule['weekday']['friday'])) ? __('Fri') : null;
            $saturday_text = (isset($schedule['weekday']['saturday'])) ? __('Sat') : null;
            $sunday_text = (isset($schedule['weekday']['sunday'])) ? __('Sun') : null;

            $days_obj = array($monday_text, $tuesday_text, $wednesday_text, $thursday_text, $friday_text, $saturday_text, $sunday_text);
            $days_text = implode(', ', array_filter($days_obj));
            $days_text = (!empty($days_text)) ? '<i class="rman-calendar"></i>' . $days_text : null;
            $time_text = date('H:i A', strtotime($starttime)) . ' - ' . date('H:i A', strtotime($endtime));

            $parameters = array(
                'days_text' => $days_text,
                'time_text' => $time_text,
                'monday' => $monday,
                'tuesday' => $tuesday,
                'wednesday' => $wednesday,
                'thursday' => $thursday,
                'friday' => $friday,
                'saturday' => $saturday,
                'sunday' => $sunday,
                'start_time' => $starttime,
                'end_time' => $endtime,
                'content_open' => ' closed'
            );
            syn_restaurant_manager_schedule_template($parameters, $key);
            ?>
            <?php
        }
    }
}

/**
 * The schedule template.
 * @param type $parameters
 * @param type $key
 */
function syn_restaurant_manager_schedule_template($parameters = array(), $key = '%index%') {
    ?>
    <div class="scheduler<?php echo $parameters['content_open'] ?>">
        <div class="schedule-header">
            <span class="schedule-days"><?php echo $parameters['days_text'] ?></span>
            <span class="schedule-time"><i class="rman-clock-o"></i><?php echo $parameters['time_text'] ?></span>
            <a class="delete-schedule-button" href="javascrip:void(0)"></a> 
            <a class="toggle-schedule-button" href="javascrip:void(0)"></a>
        </div>
        <div class="schedule-content">
            <div class="weekdays">
                <label>Days of the week</label>
                <ul>
                    <li>
                        <input type="checkbox" name="scheduler[<?php echo $key ?>][weekday][monday]" value="true" <?php checked($parameters['monday'], 'true', true) ?> /><label>Monday</label>
                    </li>
                    <li>
                        <input type="checkbox" name="scheduler[<?php echo $key ?>][weekday][tuesday]" value="true" <?php checked($parameters['tuesday'], 'true', true) ?> /><label>Tuesday</label>
                    </li>
                    <li>
                        <input type="checkbox" name="scheduler[<?php echo $key ?>][weekday][wednesday]" value="true" <?php checked($parameters['wednesday'], 'true', true) ?> /><label>Wednesday</label>
                    </li>
                    <li>
                        <input type="checkbox" name="scheduler[<?php echo $key ?>][weekday][thursday]" value="true" <?php checked($parameters['thursday'], 'true', true) ?> /><label>Thursday</label>
                    </li>
                    <li>
                        <input type="checkbox" name="scheduler[<?php echo $key ?>][weekday][friday]" value="true" <?php checked($parameters['friday'], 'true', true) ?> /><label>Friday</label>
                    </li>
                    <li>
                        <input type="checkbox" name="scheduler[<?php echo $key ?>][weekday][saturday]" value="true" <?php checked($parameters['saturday'], 'true', true) ?> /><label>Saturday</label>
                    </li>
                    <li>
                        <input type="checkbox" name="scheduler[<?php echo $key ?>][weekday][sunday]" value="true" <?php checked($parameters['sunday'], 'true', true) ?> /><label>Sunday</label>
                    </li>
                </ul>
            </div>
            <div class="timeslot">
                <label>Open Times</label>
                <div>
                    <input class="syn-scheduler-timepicker" type="text" name="scheduler[<?php echo $key ?>][timeslot][starttime]" value="<?php echo $parameters['start_time'] ?>" readonly/>
                    <input class="syn-scheduler-timepicker" type="text" name="scheduler[<?php echo $key ?>][timeslot][endtime]" value="<?php echo $parameters['end_time'] ?>" readonly/>
                </div>
            </div>
        </div>                
    </div>
    <?php
}

/**
 * Process the notifcation email when the reservation status changes.
 * @param type $status
 */
function syntaxthemes_process_notification_email($status, $email_address, $replace) {

    $syn_email = new \syntaxthemes\restaurant\syn_email_notifications();
    $result = false;

    switch ($status) {
        case 'pending':

            $admin_email = get_bloginfo('admin_email');
            $admin_sent = $syn_email->admin_booking_notification($admin_email, $replace);

            if ($admin_sent) {
                $result = $syn_email->customer_pending_booking_notification($email_address, $replace);
            }
            break;
        case 'confirmed': $result = $syn_email->customer_confirmed_booking_notification($email_address, $replace);
            break;
        case 'rejected': $result = $syn_email->customer_rejected_booking_notification($email_address, $replace);
            break;
        default: null;
            break;
    }

    return $result;
}






//menu functions

function syn_restaurant_menu_the_content_filter($content) {

    // array of custom shortcodes requiring the fix 
    $block = join("|", array("syn_restaurant_menu"));

    // opening tag
    $rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/", "[$2$3]", $content);

    // closing tag
    $rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/", "[/$2]", $rep);

    return $rep;
}

add_filter("the_content", "syn_restaurant_menu_the_content_filter");

function syn_restaurant_menu_portfolio_featured_image($post_id) {

    $post_thumbnail_id = get_post_thumbnail_id($post_id);

    if ($post_thumbnail_id) {
        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail');

        return $post_thumbnail_img[0];
    }
}

function syn_restaurant_menus_add_shortcodes($shortcode_classes) {

    $shortcodes = array(
        //'syntaxthemes\restaurant\menus\syn_restaurant_menu'
    );

    $shortcode_classes = array_merge($shortcode_classes, $shortcodes);

    return $shortcode_classes;
}

add_filter('syn_restaurant_manager_add_shortcodes', 'syn_restaurant_menus_add_shortcodes', 10, 1);

if (!function_exists('syn_restaurant_menus_get_all_terms_options')) {

    function syn_restaurant_menus_get_all_terms_options($taxonomies = array(), $args = array()) {

        $options = array();

        $defaults = array(
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => true,
            'exclude' => array(),
            'exclude_tree' => array(),
            'include' => array(),
            'number' => '',
            'fields' => 'all',
            'slug' => '',
            'parent' => '',
            'hierarchical' => true,
            'child_of' => 0,
            'get' => '',
            'name__like' => '',
            'description__like' => '',
            'pad_counts' => false,
            'offset' => '',
            'search' => '',
            'cache_domain' => 'core'
        );

        $merged_args = array_merge($defaults, $args);

        $terms = get_terms($taxonomies, $merged_args);

        $options[] = array('text' => __('All Categories', 'synth_taurus_theme'), 'value' => '');

        foreach ($terms as $term) {
            $options[] = array('text' => $term->name, 'value' => $term->term_id);
        }

        return $options;
    }

}

function syn_restaurant_menus_get_spice_rating($spice_rating) {

    $rating = '';

    switch ($spice_rating) {
        case 0: $rating = '';
            break;
        case 1:
            $rating = '<span class="rating-star rman-flame"></span>';
            break;
        case 2:
            $rating = '<span class="rating-star rman-flame"></span><span class="rating-star rman-flame"></span>';
            break;
        case 3:
            $rating = '<span class="rating-star rman-flame"></span><span class="rating-star rman-flame"></span><span class="rating-star rman-flame"></span>';
            break;
        default:
            $rating = '';
            break;
    }

    return $rating;
}

if (!function_exists('syn_restaurant_menus_get_all_meal_options')) {

    function syn_restaurant_menus_get_all_meal_options() {

        global $syn_restaurant_config;

        $options = array();

        $args = array(
            'post_type' => 'syn_rest_meal',
            'posts_per_page' => '-1',
            'post_status' => 'publish'
        );

        $query = new \WP_Query($args);

        while ($query->have_posts()) {
            global $post;
            $query->the_post();

            $post_id = get_the_ID();

            $currency_symbol = get_option($syn_restaurant_config->plugin_prefix . 'currency_symbol', '£');
            $full_price = get_post_meta($post_id, 'full_price', true);

            $text = '<span class="meal-title">' . $post->post_title . '</span><span class="meal-price">' . $currency_symbol . $full_price . '</span>';

            $options[] = array('text' => $text, 'value' => $post->ID);
        }

        wp_reset_query();

        return $options;
    }

}

if (!function_exists('syn_restaurant_menus_get_meal_options')) {

    function syn_restaurant_menus_get_meal_options() {

        global $syn_restaurant_config;

        $session = new \syntaxthemes\restaurant\session();

        $menu_id = $session->post_var('menu_id');
        $course_id = $session->post_var('course_id');

        $relation = (empty($menu_id) || empty($course_id)) ? 'OR' : 'AND';

        if (!empty($menu_id) || !empty($course_id)) {
            $args = array(
                'post_type' => 'syn_rest_meal',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'tax_query' => array(
                    'relation' => $relation,
                    array(
                        'taxonomy' => 'syn_menu_type',
                        'field' => 'id',
                        'terms' => $menu_id
                    ),
                    array(
                        'taxonomy' => 'syn_menu_course',
                        'field' => 'id',
                        'terms' => $course_id
                    )
                )
            );
        } else {
            $args = array(
                'post_type' => 'syn_rest_meal',
                'post_status' => 'publish',
                'posts_per_page' => -1,
            );
        }

        $query = new \WP_Query($args);

        $html = '';

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                global $post;

                $post_id = get_the_ID();

                $currency_symbol = get_option($syn_restaurant_config->plugin_prefix . 'currency_symbol', '£');
                $full_price = get_post_meta($post_id, 'full_price', true);

                $html .= "<li><input type=\"checkbox\" name=\"syn_restaurant_menu_ids\" value=\"{$post->ID}\">&nbsp;<label><span class=\"meal-title\">{$post->post_title}</span><span class=\"meal-price\">{$currency_symbol}{$full_price}</span></label></li>";
            }
        }

        $xml_response = new WP_Ajax_Response();

        $response = array(
            'id' => 1,
            'what' => 'syn_restaurant_menu_ids',
            'action' => 'update_meal_items',
            'data' => $html
        );
        $xml_response->add($response);
        $xml_response->send();

        die();
    }

    add_action('wp_ajax_restaurant_menus_get_meal_options', 'syn_restaurant_menus_get_meal_options');
}
?>