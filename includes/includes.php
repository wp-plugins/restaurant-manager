<?php

namespace syntaxthemes\restaurant;

require_once('functions-helpers.php');
require_once('functions-admin-menu.php');

require_once('post-types/class-reservation-post-type.php');
require_once('post-types/class-reservation-post-meta-boxes.php');
require_once('post-types/class-reservation-status.php');
require_once('post-types/class-meal-post-type.php');
require_once('post-types/class-meal-post-meta-boxes.php');
require_once('post-types/class-template-locator.php');

require_once('functions-form-processing.php');
require_once('class-email-notifications.php');
require_once('shortcodes/class-shortcode-extensions.php');
require_once('class-reservation-repository.php');

require_once('data/class-events-data.php');
require_once('data/class-eventmeta-data.php');
require_once('class-event-log.php');
require_once('class-events-list-table.php');
?>