<?php
if ( is_admin() ) {
    add_action( 'admin_menu', 'add_products_menu_entry_camper', 100 );
}
function add_products_menu_entry_camper() {
    add_submenu_page(
        'edit.php?post_type=product',
        __( 'Camper' ),
        __( 'Duplicate Campers' ),
'manage_woocommerce', // Required user capability
'duplicate-camper-product',
'duplicate_camper_page'
);
}
function duplicate_camper_page() {
    // 1==========================
                        global $wpdb;
                        $db_table_name = $wpdb->prefix . 'booking_student';
                        // $results = $wpdb->get_results( "SELECT fname, lname,  COUNT(*) as entries FROM  $db_table_name GROUP BY fname, lname HAVING COUNT(*) > 1");
                        // if(!empty($results))
                        // {
                        //     foreach($results as $row){
                        //     echo $row->fname .' '. $row->entries;
                        //     echo "<br>";
                        //     }
                        // }

// 2==========================
        global $wpdb;
        // $product_id = $_GET['id'];
        $product_id = 1571;
        $producttitle = wc_get_product( $product_id );
        global $wpdb;
        $product_title = $producttitle->name;
        $wc_item_meta = $wpdb->prefix . 'woocommerce_order_itemmeta';
        $wc_items = $wpdb->prefix . 'woocommerce_order_items';
        $wc_post_meta = $wpdb->prefix . 'postmeta';
        $tbl_manage_booking = $wpdb->prefix . 'booking_student';
        $wc_user_meta = 'wp_aab0e48489_usermeta';

       // step1
        $get_orderid = "(SELECT DISTINCT p.order_item_id, pg.order_item_name, pg.order_id FROM $wc_item_meta p INNER JOIN $wc_items pg ON (pg.order_item_id = p.order_item_id) WHERE (p.meta_key = '_product_id' AND p.meta_value = $product_id))";


        $orderids = $wpdb->get_results($get_orderid);

        $oidData = [];
        $oitemidData = [];
        foreach ($orderids as $retrieved_data){
            $oidData[] = $retrieved_data->order_id;
            $oitemidData[] = $retrieved_data->order_item_id;
        }
        // get all order ids from order table
        $oids = implode(",",$oidData);

        // get all order items id from order table
        $oitemids = implode(",",$oitemidData);

        // step 2 - get customer ids from order
        $get_ordercustid = "(SELECT DISTINCT(meta_value) FROM $wc_post_meta where meta_key = '_customer_user' AND post_id IN ($oids))";

        $customer_details = $wpdb->get_results($get_ordercustid);
        echo  $get_ordercustid->num_rows;
        $ocidData = [];
        foreach ($customer_details as $cust){
            $ocidData[] = $cust->meta_value;
        }
        $ocids = implode(",",$ocidData);  //customer id     

        // step 3

        $today = date("Y-m-d");
        $camp_count = 0;

$get_ordercamperid ="( SELECT pg.id as camper_id, p.order_item_id, p.meta_value as order_value, pg.p_id, concat(pg.fname, ' ', pg.lname) as bookingcamper, pg.fname as camperfname, pg.bdate, pg.size, pg.allergiescheck, pg.medidevicecheck, pg.allergies, pg.medidevice, pg.additional, pg.pfname, pg.plname, pg.relationship, pg.phone, pg.p2fname, pg.p2lname, pg.relationship2, pg.phone2, pg.photo, MAX( IF(um.meta_key = 'first_name', um.meta_value, NULL) ) AS fname, MAX( IF(um.meta_key = 'last_name', um.meta_value, NULL) ) AS lname, MAX( IF( um.meta_key = 'billing_phone', um.meta_value, NULL ) ) AS billingphone, MAX( IF( um.meta_key = 'afreg_additional_1808', um.meta_value, NULL ) ) AS g1fn, MAX( IF( um.meta_key = 'afreg_additional_1809', um.meta_value, NULL ) ) AS g1ln, MAX( IF( um.meta_key = 'afreg_additional_1810', um.meta_value, NULL ) ) AS g1phone, MAX( IF( um.meta_key = 'afreg_additional_1811', um.meta_value, NULL ) ) AS g1relation, MAX( IF( um.meta_key = 'afreg_additional_1815', um.meta_value, NULL ) ) AS g2fn, MAX( IF( um.meta_key = 'afreg_additional_1816', um.meta_value, NULL ) ) AS g2ln, MAX( IF( um.meta_key = 'afreg_additional_1817', um.meta_value, NULL ) ) AS g2phone, MAX( IF( um.meta_key = 'afreg_additional_1818', um.meta_value, NULL ) ) AS g2relation, MAX( IF( um.meta_key = 'afreg_additional_1821', um.meta_value, NULL ) ) AS t1fn, MAX( IF( um.meta_key = 'afreg_additional_1822', um.meta_value, NULL ) ) AS t1ln, MAX( IF( um.meta_key = 'afreg_additional_1823', um.meta_value, NULL ) ) AS t1phone, MAX( IF( um.meta_key = 'afreg_additional_1824', um.meta_value, NULL ) ) AS t1relation, MAX( IF( um.meta_key = 'afreg_additional_1826', um.meta_value, NULL ) ) AS t2fn, MAX( IF( um.meta_key = 'afreg_additional_1827', um.meta_value, NULL ) ) AS t2ln, MAX( IF( um.meta_key = 'afreg_additional_1828', um.meta_value, NULL ) ) AS t2phone, MAX( IF( um.meta_key = 'afreg_additional_1829', um.meta_value, NULL ) ) AS t2relation FROM $wc_item_meta p JOIN $tbl_manage_booking pg ON ( pg.fname = SUBSTRING_INDEX(p.meta_value, ' ', 1)) LEFT JOIN $wc_user_meta um ON (um.user_id = pg.p_id) WHERE (pg.p_id IN($ocids) AND p.order_item_id IN($oitemids)) GROUP BY p.meta_value ORDER BY pg.fname DESC)";



        $camper_details = $wpdb->get_results($get_ordercamperid);
        $camper1 = [];
        foreach ($camper_details as $key => $value) {
            // $diff = date_diff(date_create($campr->bdate), date_create($today));
            // $camperAge = $diff->format('%y');
            $camper1[] = $value->order_value;
        }
        echo "<pre>";
        print_r($camper1);
        echo "<hr>";

// camper2
$campers_metakey = "'Camper 1', 'Camper 2', 'Camper 3', 'Camper 4', 'Camper 5', 'Camper 6', 'Camper 7', 'Camper 8', 'Camper 9', 'Camper 10'";

// get all order item id from the order id
$campers = ("SELECT woim.meta_value FROM `wp_aab0e48489_woocommerce_order_itemmeta` woim WHERE order_item_id in($orderitemid) AND meta_key in($campers_metakey) ORDER BY order_item_id" );
$campersResult = $wpdb->get_results( $campers);
        $camper2 = [];
        foreach ($campersResult as $key => $value) {
            $camper2[] = $value->order_value;
        }
        echo "<pre>";
        print_r($camper2);
        echo "<hr>";






// /////////////////////////////////////////////////////////////////////////
/*
// list of duplicate campers
$campers = ("SELECT
    T2.*
FROM
    (
    SELECT 
       fname, lname, p_id
    FROM
       $db_table_name
    GROUP BY
       fname, lname, p_id
    HAVING
       COUNT(*) >= 2
    ) T1
    JOIN
    $db_table_name T2 ON T1.fname = T2.fname AND T1.lname = T2.lname AND T1.p_id = T2.p_id ORDER BY T2.fname ASC" );

$campersResult = $wpdb->get_results( $campers);
        foreach ($campersResult as $key => $value) {
            echo $value->fname.' '.$value->lname;
            echo "<br>";
        }
 // list of duplicate campers

*/

    }
ob_end_clean();
?>