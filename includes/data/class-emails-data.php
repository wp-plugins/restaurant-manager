<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class-emails-data
 *
 * @author Ryan
 */
class emails_data {

    private $_wpdb;
    private $_charset_collate;
    private $_table_name;
    private $_table_version;

    public function __construct() {

        global $syn_restaurant_config, $wpdb;

        $this->_config = $syn_restaurant_config;
        $this->_wpdb = $wpdb;
        $this->_table_name = $wpdb->prefix . 'restaurant_manager_emails';
        $this->_table_version = '1.0.0';
        $this->_charset_collate = $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
    }

    public function create_table() {

        /*
         * We'll set the default character set and collation for this table.
         * If we don't do this, some characters could end up being converted 
         * to just ?'s when saved in our table.
         */

        $sql = "CREATE TABLE $this->_table_name (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                post_id bigint(20) NOT NULL,
                author_id bigint(20) NOT NULL,
                author tinytext NOT NULL,
                email_address varchar(100) NOT NULL,
                content text NOT NULL,
                created_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                UNIQUE KEY id (id)
              ) $this->_charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);

        add_option($this->_config . 'emails_datatable_version', $this->_table_version);
    }

    public function update_table() {

        $installed_version = get_option($this->_config . 'emails_datatable_version');

        if (version_compare($installed_version, $this->_table_version) < 0) {
            
            //Update the table here.
            
            update_option($this->_config . 'emails_datatable_version', $this->_table_version);
        }
    }

}
