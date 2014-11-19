<?php

namespace syntaxthemes\restaurant;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class-reservation-status
 *
 * @author Ryan
 */
class reservation_status {

    private $_config;

    public function __construct() {

        global $syn_restaurant_config;
        $this->_config = $syn_restaurant_config;

        add_action('save_post', array($this, 'process_status'), 40);
    }

    public function process_status($post_id) {

        global $post_type;

        $session = new session();

        if ('POST' !== strtoupper($session->server_var('REQUEST_METHOD'))) {
            return false;
        }

        if ($post_type !== 'syn_rest_reservation') {
            return false;
        }

        $event_log = new event_log();

        $old_post_status = $session->post_var('original_post_status');

        $post = get_post($post_id);
        $post_status = $post->post_status;

        $date_format = get_option('date_format');
        $time_format = get_option('time_format');
        $restaurant_telephone = get_option($this->_config->plugin_prefix . 'restaurant_telephone', '');

        $first_name = get_post_meta($post_id, 'first_name', true);
        $last_name = get_post_meta($post_id, 'last_name', true);
        $telephone = get_post_meta($post_id, 'phone_number', true);
        $email_address = get_post_meta($post_id, 'email_address', true);
        $guests_count = get_post_meta($post_id, 'guests_count', true);
        $reservation_date_meta = get_post_meta($post_id, 'reservation_date', true);
        $reservation_time_meta = get_post_meta($post_id, 'reservation_time', true);

        $site_name = get_bloginfo('name');
        $site_link = site_url();
        $current_time = date("{$date_format} - {$time_format}");
        $reservation_date = date("{$date_format}", strtotime($reservation_date_meta));
        $reservation_time = date("{$time_format}", strtotime($reservation_time_meta));

        $replace = array(
            $site_name,
            $first_name,
            $last_name,
            $telephone,
            $email_address,
            $guests_count,
            $reservation_date,
            $reservation_time,
            $current_time,
            $site_link,
            $restaurant_telephone
        );

        //check if the post status has changed
        if ($post_status !== $old_post_status) {
            $result = syntaxthemes_process_notification_email($post_status, $email_address, $replace);
            $event_log->status_event($post_id, $old_post_status, $post_status, $result);
            
            //echo "<div class=\"updated\"><p>The status has been changed</p></div>";
        }       
        
    }

}

new reservation_status();
?>