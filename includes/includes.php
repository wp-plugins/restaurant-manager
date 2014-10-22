<?php

namespace syntaxthemes\restaurant;

require_once('functions-helpers.php');
require_once('functions-admin-menu.php');
require_once('post-types/class-reservation-post-type.php');
require_once('post-types/class-reservation-post-meta-boxes.php');
require_once('functions-form-processing.php');
require_once('class-email-notifications.php');
require_once('shortcodes/class-shortcode-extensions.php');
$taurus_shortcode_extensions = new shortcode_extensions();
$taurus_shortcode_extensions->create_shortcodes_button();
$taurus_shortcode_extensions->create_shortcodes();
$taurus_shortcode_extensions->initialise_tinymce_manager();

require_once('class-reservation-repository.php');
?>