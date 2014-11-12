"use strict";

jQuery(document).ready(function ()
{
    syn_restaurant_manager_datepicker();
    syn_restaurant_manager_schedular_timepicker();
    syn_restaurant_manager_scheduler();

    syn_restaurant_manager_customer_email();
});

function syn_restaurant_manager_datepicker() {

    var todays_date = new Date();

    jQuery('.syn-datepicker').datepicker({
        minDate: todays_date
    });
}

function syn_restaurant_manager_schedular_timepicker() {

    jQuery('.syn-timepicker').timepicker();
    jQuery('.syn-scheduler-timepicker').timepicker();
}

function syn_restaurant_manager_scheduler() {

    var $schedule_container = '';
    var template;
    var _index = 0;
    initialize();

    function initialize() {

        $schedule_container = jQuery('#schedule_container');
        _index = $schedule_container.children().length;

        jQuery('#schedule_container').on('click', '.scheduler .toggle-schedule-button', toggle_schedule_content);
        jQuery('#add_schedule_button').click(add_schedule);
        jQuery('#schedule_container').on('click', '.scheduler .delete-schedule-button', delete_schedule);
    }

    function toggle_schedule_content() {

        var $schedule = jQuery(this).closest('.scheduler');

        if ($schedule.hasClass('closed')) {
            $schedule.removeClass('closed');
        }
        else {
            $schedule.addClass('closed');
        }
    }

    function add_schedule() {

        var template = jQuery('#schedule_template').html();
        _index = $schedule_container.children().length;

        var $template = jQuery(template);

        $template.find('input').each(function () {

            var name = jQuery(this).attr('name');
            name = name.replace('%index%', _index);
            jQuery(this).attr('name', name);
        });

        $schedule_container.append($template);

        jQuery('.syn-scheduler-timepicker').timepicker();
    }

    function delete_schedule() {

        var $scheduler = jQuery(this).closest('.scheduler');
        $scheduler.remove();
    }

}

function syn_restaurant_manager_customer_email() {

    jQuery('#customer_email_send').click(send_the_email);

    function send_the_email() {

        if (window.tinyMCE) {
            var reservation_id = jQuery('#reservation_id').val();
            var content = tinyMCE.get('email_content').getContent();

            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                dataType: 'json',
                data: {
                    action: 'restaurant_manager_send_customer_email',
                    post_id: reservation_id,
                    email_content: content
                },
                success: function (response) {

                    if (response) {
                        jQuery('#admin-email-message').html('<div class="updated"><p>' + response.message + '</p></div>');
                    }
                },
                error: function (response, jqXHR, textStatus, errorThrown) {

                    alert(textStatus, errorThrown);
                },
                complete: function (response) {
                }
            });
        }

    }

}