<?php
class User_Reviews_Table extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $action = $this->current_action();

        $data = $this->table_data();
        usort($data, array(&$this, 'sort_data'));

        $perPage = 20;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args(array(
            'total_items' => $totalItems,
            'per_page' => $perPage,
        ));

        $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);
        $this->_column_headers = array($columns, $hidden, $sortable);
       
        $this->items = $data;
    }
    
    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'reference' => 'Post Reference',
            'username' => 'User name',
            'ratings' => 'Rates',
            'status' => 'Status',
            'date' => 'Time'
        );

        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns() {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns() {
        return array(
            'reference' => array('reference', true),
            'username' => array('username', true),
            'ratings' => array('ratings', true),
            'status' => array('status', true),
            'date' => array('date', true)
        );
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data() {
        global $wpdb;
        $data = array();
        
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}user_reviews");
        if($results){
            foreach($results as $result){
                $arr = [
                    'ID' => $result->ID,
                    'reference' => get_the_title( $result->reference ),
                    'username' => $result->name,
                    'ratings' => get_ur_reviews_ratings($result->star),
                    'status' => ucfirst($result->status),
                    'date' => date("Y-m-d h:i:s a", strtotime($result->date))
                ];

                $data[] = $arr;
            }
        }

        return $data;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default($item, $column_name) {
        switch ($column_name) {
            case $column_name:
                return $item[$column_name];
            default:
                return print_r($item, true);
        }
    }

    public function column_reference($item) {
        $actions = array(
            'view' => '<a href="?page=user-reviews&action=view&id='.$item['ID'].'">View</a>',
            'delete' => '<a href="?page=user-reviews&action=delete&reviews='.$item['ID'].'">Delete</a>'
        );

        return sprintf('%1$s %2$s', $item['reference'], $this->row_actions($actions));
    }

    public function get_bulk_actions() {
        $actions = array(
            'approve' => 'Approve',
            'denay' => 'Denay',
            'delete' => 'Delete'
        );
        return $actions;
    }

    public function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="reviews[]" value="%s" />', $item['ID']
        );
    }

    // All form actions
    public function current_action() {
        global $wpdb;
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete' && isset($_REQUEST['reviews'])) {
            if(is_array($_REQUEST['reviews'])){
                $ids = $_REQUEST['reviews'];
                foreach($ids as $ID){
                    $wpdb->query("DELETE FROM {$wpdb->prefix}user_reviews WHERE ID = $ID");
                }
            }else{
                $ID = intval($_REQUEST['reviews']);
                $wpdb->query("DELETE FROM {$wpdb->prefix}user_reviews WHERE ID = $ID");
            }

            wp_safe_redirect( admin_url( 'admin.php?page=user-reviews' ) );
            exit;
        }
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'approve' && isset($_REQUEST['reviews'])) {
            if(is_array($_REQUEST['reviews'])){
                $ids = $_REQUEST['reviews'];
                foreach($ids as $ID){
                    $wpdb->update($wpdb->prefix.'user_reviews', array(
                        'status' => 'approved'
                    ), array("ID" => $ID), array('%s'), array('%d'));
                }
            }
        }
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'denay' && isset($_REQUEST['reviews'])) {
            if(is_array($_REQUEST['reviews'])){
                $ids = $_REQUEST['reviews'];
                foreach($ids as $ID){
                    $wpdb->update($wpdb->prefix.'user_reviews', array(
                        'status' => 'pending'
                    ), array("ID" => $ID), array('%s'), array('%d'));
                }
            }
        }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data($a, $b) {
        // If no sort, default to user_login
        $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'reference';
        // If no order, default to asc
        $order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';
        // Determine sort order
        $result = strnatcmp($a[$orderby], $b[$orderby]);
        // Send final sort direction to usort
        return ($order === 'asc') ? $result : -$result;
    }

} //class
