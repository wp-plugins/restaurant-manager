<?php

namespace syntaxthemes\restaurant;

/**
 * Description of class-template-manager
 *
 * @author Ryan Haworth
 */
if (!class_exists('template_manager')) {

    abstract class template_locator {

        static $_base_directory;
        static $_base_template_directory;

        public function __construct($base_directory, $base_template_directory) {

            static::$_base_directory = $base_directory;
            static::$_base_template_directory = $base_template_directory;

            add_filter('template_include', array($this, 'template_loader'));
        }

        public abstract function template_loader($template);

        public static function get_template_part($slug, $name = '') {

            $template = '';

            $base_directory = static::$_base_directory;
            $base_template_directory = static::$_base_template_directory;

            $url = STYLESHEETPATH . "/{$base_template_directory}{$slug}-{$name}.php";

            // Check the theme for the templates - look in yourtheme/slug-name.php and yourtheme/{template-directory}/slug-name.php
            if ($name) {
                $template = locate_template(array("{$slug}-{$name}.php", "{$base_template_directory}{$slug}-{$name}.php"));
            }

            // Get default slug-name.php
            if (!$template && $name && file_exists($base_directory . "{$base_template_directory}{$slug}-{$name}.php")) {
                $template = "{$base_directory}{$base_template_directory}{$slug}-{$name}.php";
            }

            // If template file doesn't exist, look in yourtheme/slug.php and yourtheme/{template-directory}/slug.php
            if (!$template) {
                $template = locate_template(array("{$slug}.php", "{$base_template_directory}{$slug}.php"));
            }
            if ($template) {
                load_template($template, false);
            }
        }

    }

}
