<?php

/**
 * Initialise plugin core.
 */
require_once('base/class-plugin-core.php');

/**
 * Initialise plugin base.
 */
require_once('base/class-plugin-base.php');

/**
 * Initialise helper functions.
 */
require_once('helpers/class-plugin-helpers.php');

/**
 * Initialise custom metaboxes.
 */
require_once('helpers/class-custom-meta-box.php');

/**
 * Initialise synth session.
 */
require_once('helpers/class-synth-session.php');

/**
 * Initialise synth controls.
 */
require_once('controls/class-synth-control-manager.php');

/**
 * Initialise template locator.
 */
require_once('helpers/class-template-locator.php');

/**
 * Initialise the shortcode script loader.
 */
require_once('php/shortcodes/class-shortcode-extensions.php');

/**
 * Initialise email.
 */
require_once('php/email/class-synth-email.php');

?>
