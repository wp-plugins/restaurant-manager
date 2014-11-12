<?php

//namespace syntaxthemes\restaurant;

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Description of class-emails-list-table
 *
 * @author Ryan
 */
class emails_list_table extends WP_List_Table {

    public function __construct($args = array()) {

        $args = array(
            'singular' => 'Email', //Singular label
            'plural' => 'Emails', //plural label, also this well be one of the table css class
            'ajax' => false
        );

        parent::__construct($args);
    }

    function get_columns() {

        return $columns = array(
            'comment_author' => _('Customer Name', 'syn_restaurant_plugin'),
            'comment_content' => _('Email Content', 'syn_restaurant_pluginf')
        );
    }

    public function get_sortable_columns() {

        return $sortable = array(
            'comment_author' => array('comment_author', false)
        );
    }

    function prepare_items() {

        $screen = get_current_screen();

        $args = array(
            'post_id' => '1101',
            'count' => true
        );

        $comments = new WP_Comment_Query();

        /* -- Ordering parameters -- */
        //Parameters that are going to be used to order the result
        $orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'ASC';
        $order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : 'comment_date';

        /* -- Pagination parameters -- */
        //Number of elements in your table?
        $totalitems = $comments->query($args); //return the total number of affected rows
        //
        //How many to display per page?
        $perpage = 5;

        //Which page is this?
        $paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';

        //Page Number
        if (empty($paged) || !is_numeric($paged) || $paged <= 0) {
            $paged = 1;
        }

        $page_offset = ($perpage * ($paged - 1));

        //How many pages do we have in total?
        $totalpages = ceil($totalitems / $perpage);
        //adjust the query to take pagination into account

        /* -- Register the pagination -- */
        $this->set_pagination_args(array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
        ));

        $this->_column_headers = array(
            $this->get_columns(), // columns
            array(), // hidden
            $this->get_sortable_columns(), // sortable
        );

        //The pagination links are automatically built according to those parameters
        /* -- Fetch the items -- */
        $args = array(
            'post_id' => '1101',
            'orderby' => $orderby,
            'order' => $order,
            'number' => $perpage,
            'offset' => $page_offset,
            'count' => false
        );

        $this->items = $comments->query($args);
    }

    function display_rows() {

        $even_odd = 'odd alt';
        $records = $this->items;

        list($columns, $hidden) = $this->get_column_info();

        if (!empty($records)) {
            foreach ($records as $rec) {

                $even_odd = ('odd alt' != $even_odd) ? 'odd alt' : 'even';

                //Open the line
                echo '<tr id="record_' . $rec->comment_ID . '" class="' . $even_odd . '">';

                foreach ($columns as $column_name => $column_display_name) {

                    //Style attributes for each col
                    $class = "class='$column_name column-$column_name'";

                    $style = "";

                    if (in_array($column_name, $hidden)) {
                        $style = ' style="display:none;"';
                    }

                    $attributes = $class . $style;

                    $date_format = get_option('date_format');
                    $time_format = get_option('time_format');

                    //edit link
                    $editlink = '/wp-admin/link.php?action=edit&link_id=' . (int) $rec->comment_ID;

                    $comment_date = date("{$date_format}", strtotime($rec->comment_date));
                    $comment_time = date("{$time_format}", strtotime($rec->comment_date));

                    $comment_created = sprintf("Submitted on %s at %s", $comment_date, $comment_time);

                    //Display the cell
                    switch ($column_name) {
                        case "comment_author": echo '<td ' . $attributes . '>' . stripslashes($rec->comment_author) . '<span class="comment-email">' . $rec->comment_author_email . '</span></td>';
                            break;
                        case "comment_content": echo '<td ' . $attributes . '><span class="comment-submitted">' . $comment_created . '</span>' . stripslashes($rec->comment_content) . '</td>';
                            break;
                    }
                }

                //Close the line
                echo '</tr>';
            }
        }
    }

}

?>