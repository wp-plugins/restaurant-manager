<?php

namespace syntaxthemes\restaurant;

/**
 * Description of class-email-notification
 *
 * @author Ryan Haworth
 */
class email_notifications extends syn_email {

    /**
     * This is a general email processing function.
     * @param type $reply_name
     * @param type $reply_to
     * @param type $to
     * @param type $subject
     * @param type $replace
     * @param type $body
     * @return type
     */
    public function process_email($to, $subject, $message) {

        $reply_to_name = get_option($this->_config->plugin_prefix . 'reply_to_name');
        $reply_to_email = get_option($this->_config->plugin_prefix . 'reply_to_email');

        $this->add_from($reply_to_name, $reply_to_email);

        $this->_to = $to;
        $this->_subject = $subject;

        $this->_headers = array(
            'Content-Type: text/html'
        );

        $this->_message = stripslashes($message);

        return $this->send_mail();
    }

    /**
     * When a reservation booking request has been made this function creates
     * the admin email.
     * @param type $to
     * @param type $replace
     * @return type
     */
    public function admin_booking_notification($to, $replace) {

        $reply_to_name = get_option($this->_config->plugin_prefix . 'reply_to_name');
        $reply_to_email = get_option($this->_config->plugin_prefix . 'reply_to_email');

        $this->add_from($reply_to_name, $reply_to_email);

        $this->_to = $to;
        $this->_subject = get_option($this->_config->plugin_prefix . 'admin_email_subject');
        $body = get_option($this->_config->plugin_prefix . 'admin_email');

        $this->_headers[] = 'Content-Type: text/html';

        $search = array(
            '%site_name%',
            '%first_name%',
            '%last_name%',
            '%telephone%',
            '%email_address%',
            '%guests_count%',
            '%reservation_date%',
            '%reservation_time%',
            '%current_time%',
            '%site_link%'
        );

        $message = str_replace($search, $replace, $body);
        $this->_message = stripslashes($message);

        return $this->send_mail();
    }

    /**
     * When a booking has been made a pending reservation email is sent to
     * the customer.
     * @param type $to
     * @param type $replace
     * @return type
     */
    public function customer_pending_booking_notification($to, $replace) {

        $reply_to_name = get_option($this->_config->plugin_prefix . 'reply_to_name');
        $reply_to_email = get_option($this->_config->plugin_prefix . 'reply_to_email');

        $this->add_from($reply_to_name, $reply_to_email);

        $this->_to = $to;
        $this->_subject = get_option($this->_config->plugin_prefix . 'reservation_email_subject');
        $body = get_option($this->_config->plugin_prefix . 'reservation_email');

        $this->_headers[] = 'Content-Type: text/html';

        $search = array(
            '%site_name%',
            '%first_name%',
            '%last_name%',
            '%telephone%',
            '%email_address%',
            '%guests_count%',
            '%reservation_date%',
            '%reservation_time%',
            '%current_time%',
            '%site_link%'
        );

        $message = str_replace($search, $replace, $body);
        $this->_message = stripslashes($message);

        return $this->send_mail();
    }

    /**
     * When the restaurant confirms the booking this email is sent to the
     * customer.
     * @param type $to
     * @param type $replace
     * @return type
     */
    public function customer_confirmed_booking_notification($to, $replace) {

        $reply_to_name = get_option($this->_config->plugin_prefix . 'reply_to_name');
        $reply_to_email = get_option($this->_config->plugin_prefix . 'reply_to_email');

        $this->add_from($reply_to_name, $reply_to_email);

        $this->_to = $to;
        $this->_subject = get_option($this->_config->plugin_prefix . 'reservation_confirmed_email_subject');
        $body = get_option($this->_config->plugin_prefix . 'reservation_confirmed_email');

        $this->_headers[] = 'Content-Type: text/html';

        $search = array(
            '%site_name%',
            '%first_name%',
            '%last_name%',
            '%telephone%',
            '%email_address%',
            '%guests_count%',
            '%reservation_date%',
            '%reservation_time%',
            '%current_time%',
            '%site_link%',
            '%restaurant_telephone%'
        );

        $message = str_replace($search, $replace, $body);
        $this->_message = stripslashes($message);

        return $this->send_mail();
    }

    /**
     * When the restaurant rejects a booking this email is sent to the
     * customer.
     * @param type $to
     * @param type $replace
     * @return type
     */
    public function customer_rejected_booking_notification($to, $replace) {

        $reply_to_name = get_option($this->_config->plugin_prefix . 'reply_to_name');
        $reply_to_email = get_option($this->_config->plugin_prefix . 'reply_to_email');

        $this->add_from($reply_to_name, $reply_to_email);

        $this->_to = $to;
        $this->_subject = get_option($this->_config->plugin_prefix . 'reservation_rejected_email_subject');
        $body = get_option($this->_config->plugin_prefix . 'reservation_rejected_email');

        $this->_headers[] = 'Content-Type: text/html';

        $search = array(
            '%site_name%',
            '%first_name%',
            '%last_name%',
            '%telephone%',
            '%email_address%',
            '%guests_count%',
            '%reservation_date%',
            '%reservation_time%',
            '%current_time%',
            '%site_link%',
            '%restaurant_telephone%'
        );

        $message = str_replace($search, $replace, $body);
        $this->_message = stripslashes($message);

        return $this->send_mail();
    }

}
