<?php

namespace syntaxthemes\restaurant;

/**
 * Description of the plugin
 *
 * @author Ryan
 */
class plugin extends plugin_base {

    /**
     * The main plugin constructor
     * 
     * @global type $syn_restaurant_config
     */
    public function __construct() {

        global $syn_restaurant_config;
        parent::__construct($syn_restaurant_config);

        add_action('init', array($this, 'add_actions_and_filters'));
        add_action('init', array($this, 'register_script_files'));
    }

    /**
     * When the plugin activates install any database tables which are required.
     */
    public function install_database_tables() {
        
    }

    /**
     * When the plugin activates add any default options which are required
     * by the plugin.
     */
    public function install_options() {

        //General Settings
        $group_size = 15;
        $reservation_success_message = "Thank you, We have successfully received your booking request.  Your booking is awaiting to be confirmed with us.  We will send you updates to the email address provided.";

        add_option($this->_config->plugin_prefix . 'group_size', $group_size);
        add_option($this->_config->plugin_prefix . 'reservation_success_message', $reservation_success_message);

        //Notifications Settings
        $admin_email = syn_restaurant_manager_default_admin_email();
        $reservation_email = syn_restaurant_manager_default_reservation_email();
        $reservation_confirmed_email = syn_restaurant_manager_default_reservation_confirmed_email();
        $reservation_rejected_email = syn_restaurant_manager_default_reservation_rejected_email();

        add_option($this->_config->plugin_prefix . 'admin_email', $admin_email);
        add_option($this->_config->plugin_prefix . 'reservation_email', $reservation_email);
        add_option($this->_config->plugin_prefix . 'reservation_confirmed_email', $reservation_confirmed_email);
        add_option($this->_config->plugin_prefix . 'reservation_rejected_email', $reservation_rejected_email);
    }

    /**
     * When the plugin is updated to a newer version the code here
     * will make any ammendments which are required.
     */
    public function upgrade() {
        
    }

    /**
     * Any actions or filters required by the plugin will go here.
     */
    public function add_actions_and_filters() {

        /*
         * Translations can be added to the /languages/ directory.
         */
        load_plugin_textdomain('syn_restaurant_plugin', false, $this->_config->plugin_path . '/languages/');

        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'frontend_scripts'));
    }

    /**
     * Create and manage the admin menu here.
     */
    public function add_admin_menu() {
        
    }

    /**
     * Register the plugin script files here.
     */
    public function register_script_files() {

        wp_register_script('jquery-validation', plugins_url('framework/js/validation/jquery.validate.min.js', __FILE__), array('jquery'), null, true);
        wp_register_style('restaurant-manager-admin-style', plugins_url('/assets/css/admin-style.css', __FILE__));
        wp_register_script('jquery-timepicker-script', plugins_url('/assets/js/jquery-ui-timepicker.js', __FILE__), array('jquery'), false, true);

        wp_register_script('jquery-pickdate-picker-script', plugins_url('/assets/js/pickdate/picker.js', __FILE__), array('jquery'), false, true);
        wp_register_script('jquery-pickdate-date-script', plugins_url('/assets/js/pickdate/picker.date.js', __FILE__), array('jquery'), false, true);
        wp_register_script('jquery-pickdate-time-script', plugins_url('/assets/js/pickdate/picker.time.js', __FILE__), array('jquery'), false, true);
        wp_register_script('jquery-pickdate-legacy-script', plugins_url('/assets/js/pickdate/legacy.js', __FILE__), array('jquery'), false, true);



        wp_register_style('restaurant-menus-admin-style', plugins_url('/assets/css/admin-style.css', __FILE__));
        wp_register_style('restaurant-menus-style', plugins_url('/assets/css/style.css', __FILE__));

        wp_register_script('restaurant-menus-script', plugins_url('/assets/js/min/restaurant-menus-script.min.js', __FILE__), array('jquery'), false, true);
    }

    /**
     * Register the admin scripts and styles here.
     * 
     * @global type $wp_scripts
     * @param type $hook
     */
    public function admin_scripts($hook) {

        global $wp_scripts;

        wp_enqueue_script('jquery-validation');
        wp_enqueue_script('synth-validation-script', plugins_url('framework/js/synth-validation.js', __FILE__), array('jquery'), false, true);

        //wp_enqueue_script('jquery-validation');
        wp_enqueue_script('synth-restaurant-manager-admin-script', plugins_url('framework/js/min/synth-admin.min.js', __FILE__), array('jquery', 'jquery-ui-core', 'jquery-ui-slider', 'jquery-ui-datepicker', 'jquery-timepicker-script'), false, true);
        wp_enqueue_script('synth-restaurant-admin-script', plugins_url('assets/js/min/synth-restaurant-admin.min.js', __FILE__), array('jquery-ui-core', 'jquery-ui-slider', 'jquery-ui-datepicker', 'jquery-timepicker-script'), false, true);
        //wp_enqueue_script('synth-core-script', plugins_url('framework/js/synth-core.js', __FILE__), array('jquery'), false, true);
        //wp_enqueue_script('synth-controls-script', plugins_url('framework/js/synth-controls.js', __FILE__), array('jquery'), false, true);
        //wp_enqueue_script('synth-shortcodes-script', plugins_url('framework/js/synth-shortcodes.js', __FILE__), array('jquery'), false, true);

        wp_enqueue_style('restaurant-manager-admin-style');
        //wp_enqueue_script('syntaxthemes-controls-script', plugins_url('/assets/js/synth-controls.js', __FILE__), array('jquery', 'jquery-ui-core', 'jquery-ui-slider', 'jquery-ui-datepicker', 'jquery-timepicker-script'), false, true);
        // get the jquery ui object
        $queryui = $wp_scripts->query('jquery-ui-core');

        // load the jquery ui theme
        $url = "http://ajax.googleapis.com/ajax/libs/jqueryui/" . $queryui->ver . "/themes/smoothness/jquery-ui.css";
        wp_enqueue_style('jquery-ui-smoothness', $url, false, null);



        global $post_type;

        if ($hook === 'post.php' || $hook === 'post-new.php') {

            wp_enqueue_script('restaurant-menus-script');
        }

        if ($post_type === 'syn_rest_meal' || $hook === 'syn_rest_meal_page_syn_restaurant_menus_settings') {
            wp_enqueue_style('restaurant-menus-admin-style');
        }

        wp_enqueue_style('restaurant-menus-admin-style');
    }

    /**
     * Enqueue any scripts or styles required for the frontend website here.
     */
    public function frontend_scripts() {

        global $wp_scripts;

        wp_enqueue_style('restaurant-manager-style', plugins_url('/assets/css/style.css', __FILE__));

        //wp_enqueue_script('syntaxthemes-controls-script', plugins_url('/assets/js/synth-controls.js', __FILE__), array('jquery', 'jquery-ui-core', 'jquery-ui-slider', 'jquery-ui-datepicker', 'jquery-timepicker-script'), false, true);
        // get the jquery ui object
        $queryui = $wp_scripts->query('jquery-ui-core');
        // load the jquery ui theme
        $url = "http://ajax.googleapis.com/ajax/libs/jqueryui/" . $queryui->ver . "/themes/smoothness/jquery-ui.css";
        wp_enqueue_style('jquery-ui-smoothness', $url, false, null);
        
        
        wp_enqueue_style('restaurant-menus-style');
    }

}

?>