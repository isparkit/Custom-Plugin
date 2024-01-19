<?php
/**
* Plugin Name: Camper Management Addon
* Plugin URI:
* Description: Custom plugin for our camper management system. Save your permalik again to enable add camper page on my account. <a href="/wp-admin/options-permalink.php" target="blank">Goto Permalink</a>
* Version: 3.0 | By <a href="https://conspiredminds.com">ConspiredMinds</a>
**/

// create table if not exists
register_activation_hook( __FILE__, 'myPluginCreateTable');
function myPluginCreateTable() {
// 18 fname lname bdate size emergencycheck pfname plname relationship phone p2fname p2lname relationship2 phone2 allergiescheck allergies medidevicecheck medidevice additional
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'booking_student';
    $sql = "CREATE TABLE `$table_name` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `p_id` int(220) DEFAULT NULL,
        `fname` varchar(220) DEFAULT NULL,
        `lname` varchar(220) DEFAULT NULL,
        `bdate` varchar(55) DEFAULT '1',
        `size` varchar(11) DEFAULT '1',
        `emergencycheck` varchar(11) DEFAULT '1',
        `pfname` varchar(220) DEFAULT NULL,
        `plname` varchar(220) DEFAULT NULL,
        `relationship` varchar(220) DEFAULT NULL,
        `phone` varchar(220) DEFAULT NULL,
        `p2fname` varchar(220) DEFAULT NULL,
        `p2lname` varchar(220) DEFAULT NULL,
        `relationship2` varchar(220) DEFAULT NULL,
        `phone2` varchar(220) DEFAULT NULL,
        `allergiescheck` varchar(11) DEFAULT NULL,
        `allergies` varchar(220) DEFAULT NULL,
        `medidevicecheck` varchar(11) DEFAULT NULL,
        `medidevice` varchar(220) DEFAULT NULL,
        `additional` varchar(220) DEFAULT NULL,
        `photo` varchar(220) DEFAULT NULL,
        `acceptance` varchar(220) DEFAULT NULL,
        `waiver` varchar(220) DEFAULT NULL,
        `initials` varchar(220) DEFAULT NULL,
        PRIMARY KEY(id)
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
    ";
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    // waitinglist table create start

    $waiting_table_name = $wpdb->prefix . 'waiting_list';
    $waitinglist_sql = "CREATE TABLE `$waiting_table_name` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `pid` int(220) DEFAULT NULL,
        `bsid` varchar(220) DEFAULT NULL,
        `campid` int(220) DEFAULT NULL,
        `note` varchar(550) DEFAULT NULL,
        `inquirydate` varchar(55) DEFAULT '1',
        PRIMARY KEY(id)
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
    ";
    if ($wpdb->get_var("SHOW TABLES LIKE '$waiting_table_name'") != $waiting_table_name) {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($waitinglist_sql);
    }

    // waiting list table create end
}
// ########################## plugin common files, js, css enque ############################### //
require_once('inc.php');
// ########################## plugin common files, js, css enque ############################### //

//############ manage camper related things for parent login ############//
require_once('manage_camper.php');
// ############ above related with camper adding in my-account ############ //


// custom product meta data, item meta data, order meta data
// ######################################################
// https://sarkware.com/woocommerce-change-product-price-dynamically-while-adding-to-cart-without-using-plugins/
// adding meta data to single product page 
function add_camper_field() {
    ?>
    <h3 class="campers-label"style="margin-bottom: 0px;">Camper(s)</h3>

    <div class="table-wrapper">
        <div class="scrollable">
            <table class="variations" cellspacing="0"><tbody>
                <?php 
                global $wpdb;
                $table_name = $wpdb->prefix . 'booking_student';
                $pid = get_current_user_id();
                $results = $wpdb->get_results( "SELECT * FROM $table_name WHERE p_id= $pid");
                if(!empty($results))
                {
                    foreach($results as $row){
// echo "<pre>";
// print_r($row);
                        $dateOfBirth = $row->bdate;
                        $today = date("Y-m-d");
                        $diff = date_diff(date_create($dateOfBirth), date_create($today));
                        
                        $mydata[]= $row->fname . ' ' . $row->lname . ', Age ' . $diff->format('%y'). ', Size ' . $row->size;

                        if($row->emergencycheck == 'yes'){
                            $pdetails1 = '( First Name: ' . $row->pfname .' plname: ' . $row->plname .' relationship: ' . $row->relationship .' phone: ' . $row->phone . ')';

                            $pdetails2 = '( First Name: ' . $row->p2fname .' plname: ' . $row->p2lname .' relationship: ' . $row->relationship2 . ' phone: ' . $row->phone2 . ')';
                        }
                        if($row->allergiescheck == 'yes'){
                            $allergy = $row->allergies;
                        }
                        if($row->medidevicecheck == 'yes'){
                            $device = $row->medidevice;
                        }    

                        // $myid = 'ID: '.$row->id;
                        
                        $myname = '[ID: '.$row->id .'] '. $row->fname . ' ' . $row->lname .', ';
                        $myage = ' Age: '.$diff->format('%y') .', ';
                        $mysize = ' Shirt Size: '. strtoupper($row->size) .'';

                        $myemergencycheck = $row->emergencycheck;
                        if($myemergencycheck) {
                            if($row->pfname || $row->plname) {    
                                $mypname = 'Parent Name: '. $row->pfname . ' ' . $row->plname  .', ';
                            }
                            if($row->relationship){
                                $myrelationship = 'Relationship: '. $row->relationship  .', ';
                            }
                            if($row->phone){
                                $myphone = 'Phone: '. $row->phone .', ';
                            }
                            if($row->p2fname) {
                                $mypfname2 = 'Another Parent Name: '.$row->p2fname . ' ' . $row->p2lname .', ';
                            }
                            if($row->relationship2){
                                $myrelationship2 = 'Relationship: '.$row->relationship2 .', ';
                            }
                            if($row->phone2){
                                $myphone2 = 'Phone: '. $row->phone2 .', ';
                            }
                        }

                        if($row->allergiescheck){
                            $myallergies = 'Allergies: '. $row->allergies .', ';
                        }
                        if($row->medidevicecheck){
                            $mymedidevice = 'Medical Device: '. $row->medidevice .', ';
                        }
                        if($row->additional){
                            $myadditional = 'Additional: '. $row->additional .', ';
                        }
                        if($row->photo){
                            $myphoto = 'Photo: '. $row->photo .', ';
                        }
                        if($row->initials){
                            $myinitials = 'Initials: '. $row->initials;
                        }

                        $myorderdata[] = $myname . $myage . ' ' . $mysize ;
//                         $myorderdata[] = $myname . $myage . ' ' . $mysize . ' ' . $mypname . ' ' . $myrelationship . ' ' . $myphone. ' ' . $mypname2 . ' ' . $myrelationship2 . ' ' . $myphone2 . ' ' . $myallergies . ' ' . $mymedidevice . ' ' . $myadditional . ' ' . $myphoto . ' ' . $myinitials;


                        $mysizedata[]=$row->size;
                    }
                }
                else
                {
    // echo '<p>You must have atleast one camper registered to book : <a href="/my-account/add-camper/">Manage Camper</a></p> ';
        // Displaying a custom message

                    $message = __("You must have at least one camper Enrolled to book.", "woocommerce");
                    $button_link = '/my-account/add-camper/';
                    $button_text = __("Manage Camper(s)", "woocommerce");
                    $message .= ' <a href="'.$button_link.'" class="login-register button" style="">'.$button_text.'</a>';
                    wc_add_notice( $message, 'error' );

                }
                ?>
                <?php 


                if(isset($mydata[0])){ ?>
                    <tr>
                        <td class="label"><label><?php echo $mydata[0]; ?></label></td>
                        <td class="value">
                            <label><input id="q0" type="checkbox" name="c0" value="<?php echo $myorderdata[0]; ?>" /></label>                    
                        </td>
                    </tr>
                    <?php
                }
                if(isset($mydata[1])){ ?>

                    <tr>
                        <td class="label"><label><?php echo $mydata[1]; ?></label></td>
                        <td class="value">
                            <label><input id="q1" type="checkbox" name="c1" value="<?php echo $myorderdata[1]; ?>" /></label>                        
                        </td>
                    </tr>
                    <?php
                }
                if(isset($mydata[2])){ ?>

                    <tr>
                        <td class="label"><label><?php echo $mydata[2]; ?></label></td>
                        <td class="value">
                            <label><input id="q2" type="checkbox" name="c2" value="<?php echo $myorderdata[2]; ?>" /></label>                        
                        </td>
                    </tr>
                    <?php
                }
                if(isset($mydata[3])){ ?>

                    <tr>
                        <td class="label"><label><?php echo $mydata[3]; ?></label></td>
                        <td class="value">
                            <label><input id="q3" type="checkbox" name="c3" value="<?php echo $myorderdata[3]; ?>" /></label>                        
                        </td>
                    </tr>
                    <?php
                }
                if(isset($mydata[4])){ ?>

                    <tr>
                        <td class="label"><label><?php echo $mydata[4]; ?></label></td>
                        <td class="value">
                            <label><input id="q4" type="checkbox" name="c4" value="<?php echo $myorderdata[4]; ?>" /></label>                        
                        </td>
                    </tr>
                    <?php
                }
                if(isset($mydata[5])){ ?>

                    <tr>
                        <td class="label"><label><?php echo $mydata[5]; ?></label></td>
                        <td class="value">
                            <label><input id="q5" type="checkbox" name="c5" value="<?php echo $myorderdata[5]; ?>" /></label>                        
                        </td>
                    </tr>
                    <?php
                }
                if(isset($mydata[6])){ ?>

                    <tr>
                        <td class="label"><label><?php echo $mydata[6]; ?></label></td>
                        <td class="value">
                            <label><input id="q6" type="checkbox" name="c6" value="<?php echo $myorderdata[6]; ?>" /></label>                        
                        </td>
                    </tr>
                    <?php
                }
                if(isset($mydata[7])){ ?>

                    <tr>
                        <td class="label"><label><?php echo $mydata[7]; ?></label></td>
                        <td class="value">
                            <label><input id="q7" type="checkbox" name="c7" value="<?php echo $myorderdata[7]; ?>" /></label>                        
                        </td>
                    </tr>
                    <?php
                }
                if(isset($mydata[8])){ ?>

                    <tr>
                        <td class="label"><label><?php echo $mydata[8]; ?></label></td>
                        <td class="value">
                            <label><input id="q8" type="checkbox" name="c8" value="<?php echo $myorderdata[8]; ?>" /></label>                        
                        </td>
                    </tr>
                    <?php
                }
                if(isset($mydata[9])) { ?>

                    <tr>
                        <td class="label"><label><?php echo $mydata[9]; ?></label></td>
                        <td class="value">
                            <label><input id="q9" type="checkbox" name="c9" value="<?php echo $myorderdata[9]; ?>" /></label>                        
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div></div>
    <input type="hidden" class="qtotal" name="camprtxt" id="camprtxt">
    <?php
}
add_action( 'woocommerce_before_add_to_cart_button', 'add_camper_field', 60 );

// cart items meta data
function save_camper_fee( $cart_item_data, $product_id ) {

    if( isset( $_POST['c0'] )) {
        $cart_item_data[ "camper_fee0" ] =  sanitize_text_field($_POST['c0']);     
    }
    if( isset( $_POST['c1'] )) {
        $cart_item_data[ "camper_fee1" ] =  sanitize_text_field($_POST['c1']);     
    }
    if( isset( $_POST['c2'] )) {
        $cart_item_data[ "camper_fee2" ] =  sanitize_text_field($_POST['c2']);     
    }
    if( isset( $_POST['c3'] )) {
        $cart_item_data[ "camper_fee3" ] =  sanitize_text_field($_POST['c3']);     
    }
    if( isset( $_POST['c4'] )) {
        $cart_item_data[ "camper_fee4" ] =  sanitize_text_field($_POST['c4']);     
    }
    if( isset( $_POST['c5'] )) {
        $cart_item_data[ "camper_fee5" ] =  sanitize_text_field($_POST['c5']);     
    }
    if( isset( $_POST['c6'] )) {
        $cart_item_data[ "camper_fee6" ] =  sanitize_text_field($_POST['c6']);     
    }
    if( isset( $_POST['c7'] )) {
        $cart_item_data[ "camper_fee7" ] =  sanitize_text_field($_POST['c7']);     
    }
    if( isset( $_POST['c8'] )) {
        $cart_item_data[ "camper_fee8" ] =  sanitize_text_field($_POST['c8']);     
    }
    if( isset( $_POST['c9'] )) {
        $cart_item_data[ "camper_fee9" ] =  sanitize_text_field($_POST['c9']);     
    }   
    if( isset( $_POST['camprtxt'] )) {
        $cart_item_data[ "camprtxt" ] =  sanitize_text_field($_POST['camprtxt']);     
    }                    
    return $cart_item_data;

}
add_filter( 'woocommerce_add_cart_item_data', 'save_camper_fee', 99, 2 );



// before calculation this code is used to update price, update of qty is used on js file
function calculate_camper_fee( $cart_object ) {
    if( !WC()->session->__isset( "reload_checkout" )) {
        /* Gift wrap price */
        foreach ( WC()->cart->get_cart() as $key => $value ) {
            $additionalPrice = floatval( $value['quantity']);

// echo "<pre>";
// print_r($value);

            if( isset( $value["camper_fee0"] ) ) {                
                if( method_exists( $value['data'], "set_price" ) ) {
                    /* Woocommerce 3.0 + */
                    $orgPrice = floatval( $value['data']->get_price() );
                    if($orgPrice == $additionalPrice){
                        $value['data']->set_price( $orgPrice );                   
                    }
                   //  else {
                   //     $value['data']->set_price( $orgPrice * $additionalPrice );   
                   // }
                }                      
            }
            elseif( isset( $value["camper_fee1"] ) ) {                
                if( method_exists( $value['data'], "set_price" ) ) {
                    $orgPrice = floatval( $value['data']->get_price() );
                    if($orgPrice == $additionalPrice){
                        $value['data']->set_price( $orgPrice );                   
                    }
               //  else {
               //     $value['data']->set_price( $orgPrice * $additionalPrice );   
               // }
                }                         
            }    
            elseif( isset( $value["camper_fee2"] ) ) {                
                if( method_exists( $value['data'], "set_price" ) ) {
                    $orgPrice = floatval( $value['data']->get_price() );
                    if($orgPrice == $additionalPrice){
                        $value['data']->set_price( $orgPrice );
                    }
           //  else {
           //     $value['data']->set_price( $orgPrice * $additionalPrice );   
           // }
                }                          
            }    
            elseif( isset( $value["camper_fee3"] ) ) {                
                if( method_exists( $value['data'], "set_price" ) ) {
                    $orgPrice = floatval( $value['data']->get_price() );
                    if($orgPrice == $additionalPrice){
                        $value['data']->set_price( $orgPrice );
                    }
       //  else {
       //     $value['data']->set_price( $orgPrice * $additionalPrice );   
       // }
                }         
            }    
            elseif( isset( $value["camper_fee4"] ) ) {                
                if( method_exists( $value['data'], "set_price" ) ) {
                    $orgPrice = floatval( $value['data']->get_price() );
                    if($orgPrice == $additionalPrice){
                        $value['data']->set_price( $orgPrice );
                    }
       //  else {
       //     $value['data']->set_price( $orgPrice * $additionalPrice );   
       // }
                }         
            }    
            elseif( isset( $value["camper_fee5"] ) ) {                
                if( method_exists( $value['data'], "set_price" ) ) {
                    $orgPrice = floatval( $value['data']->get_price() );
                    if($orgPrice == $additionalPrice){
                        $value['data']->set_price( $orgPrice );
                    }
       //  else {
       //     $value['data']->set_price( $orgPrice * $additionalPrice );   
       // }
                }         
            } 
            elseif( isset( $value["camper_fee6"] ) ) {                
                if( method_exists( $value['data'], "set_price" ) ) {
                    $orgPrice = floatval( $value['data']->get_price() );
                    if($orgPrice == $additionalPrice){
                        $value['data']->set_price( $orgPrice );
                    }
       //  else {
       //     $value['data']->set_price( $orgPrice * $additionalPrice );   
       // }
                }         
            }    
            elseif( isset( $value["camper_fee7"] ) ) {                
                if( method_exists( $value['data'], "set_price" ) ) {
                    $orgPrice = floatval( $value['data']->get_price() );
                    if($orgPrice == $additionalPrice){
                        $value['data']->set_price( $orgPrice );
                    }
       //  else {
       //     $value['data']->set_price( $orgPrice * $additionalPrice );   
       // }
                }         
            }                                                     
            elseif( isset( $value["camper_fee8"] ) ) {                
                if( method_exists( $value['data'], "set_price" ) ) {
                    $orgPrice = floatval( $value['data']->get_price() );
                    if($orgPrice == $additionalPrice){
                        $value['data']->set_price( $orgPrice );
                    }
       //  else {
       //     $value['data']->set_price( $orgPrice * $additionalPrice );   
       // }
                }         
            }    
            elseif( isset( $value["camper_fee9"] ) ) {                
                if( method_exists( $value['data'], "set_price" ) ) {
                    $orgPrice = floatval( $value['data']->get_price() );
                    if($orgPrice == $additionalPrice){
                        $value['data']->set_price( $orgPrice );
                    }
       //  else {
       //     $value['data']->set_price( $orgPrice * $additionalPrice );   
       // }
                }         
            }                                                     
        }
    }
}
add_action( 'woocommerce_before_calculate_totals', 'calculate_camper_fee', 99 );


add_action('woocommerce_before_calculate_totals', 'change_cart_item_quantities', 20, 1 );
function change_cart_item_quantities ( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    // if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
    //     return;
    foreach( $cart->get_cart() as $cart_item_key => $cart_item ) {
// echo "<pre>";
// print_r($cart_item);
        $myqty = $cart_item['camprtxt'];
// $myqty = 2;
        if( $cart_item['quantity'] != $myqty ){
            $cart->set_quantity( $cart_item_key, $myqty ); // Change quantity
        }
    }
}


// get item data for orders
function render_meta_on_cart_and_checkout( $cart_data, $cart_item = null ) {
    $meta_items = array();
    /* Woo 2.4.2 updates */
    if( !empty( $cart_data ) ) {
        $meta_items = $cart_data;
        // print_r($cart_item);
    }
    //     if( isset( $cart_item["camprtxt"] ) ) {
    //     $meta_items[] = array( "name" => 'Total Quantity ', "value" => $cart_item['camprtxt'] );
    // } 
    if( isset( $cart_item["camper_fee0"] ) ) {
        $meta_items[] = array( "name" => 'Camper ', "value" => $cart_item['camper_fee0'] );
    }
    if( isset( $cart_item["camper_fee1"] ) ) {
        $meta_items[] = array( "name" => 'Camper ', "value" => $cart_item['camper_fee1'] );
    }
    if( isset( $cart_item["camper_fee2"] ) ) {
        $meta_items[] = array( "name" => 'Camper ', "value" => $cart_item['camper_fee2'] );
    }
    if( isset( $cart_item["camper_fee3"] ) ) {
        $meta_items[] = array( "name" => 'Camper ', "value" => $cart_item['camper_fee3'] );
    }
    if( isset( $cart_item["camper_fee4"] ) ) {
        $meta_items[] = array( "name" => 'Camper ', "value" => $cart_item['camper_fee4'] );
    }
    if( isset( $cart_item["camper_fee5"] ) ) {
        $meta_items[] = array( "name" => 'Camper ', "value" => $cart_item['camper_fee5'] );
    }
    if( isset( $cart_item["camper_fee6"] ) ) {
        $meta_items[] = array( "name" => 'Camper ', "value" => $cart_item['camper_fee6'] );
    }
    if( isset( $cart_item["camper_fee7"] ) ) {
        $meta_items[] = array( "name" => 'Camper ', "value" => $cart_item['camper_fee7'] );
    }
    if( isset( $cart_item["camper_fee8"] ) ) {
        $meta_items[] = array( "name" => 'Camper ', "value" => $cart_item['camper_fee8'] );
    } 
    if( isset( $cart_item["camper_fee9"] ) ) {
        $meta_items[] = array( "name" => 'Camper ', "value" => $cart_item['camper_fee9'] );
    }   

    return $meta_items;
}
add_filter( 'woocommerce_get_item_data', 'render_meta_on_cart_and_checkout', 99, 2 );

// add custom meta data to order details
add_action('woocommerce_checkout_create_order_line_item', 'save_custom_camper_order_item_meta_data', 10, 4 );
function save_custom_camper_order_item_meta_data( $item, $cart_item_key, $values, $order ) {
    // if( isset( $values['camprtxt'] ) ) {
    //    $item->update_meta_data( 'Total Quantity', $values['camprtxt'] );
    // } 
    if( isset( $values['camper_fee0'] ) ) {
        $v1 = $values['camper_fee0'];
        $c1 = substr($v1, strpos($v1, "-") + 1);    
        // $c1v = '<a href="'.$c1.'">"'.$v1.'</a>'; 
        $c1v = $v1; 
        $item->update_meta_data( 'Camper 1', $c1v );
    }
    if( isset( $values['camper_fee1'] ) ) {
       $item->update_meta_data( 'Camper 2', $values['camper_fee1'] );
   } 
   if( isset( $values['camper_fee2'] ) ) {
       $item->update_meta_data( 'Camper 3', $values['camper_fee2'] );
   }  
   if( isset( $values['camper_fee3'] ) ) {
       $item->update_meta_data( 'Camper 4', $values['camper_fee3'] );
   }    
   if( isset( $values['camper_fee4'] ) ) {
       $item->update_meta_data( 'Camper 5', $values['camper_fee4'] );
   }    
   if( isset( $values['camper_fee5'] ) ) {
       $item->update_meta_data( 'Camper 6', $values['camper_fee5'] );
   }    
   if( isset( $values['camper_fee6'] ) ) {
       $item->update_meta_data( 'Camper 7', $values['camper_fee6'] );
   }    
   if( isset( $values['camper_fee7'] ) ) {
       $item->update_meta_data( 'Camper 8', $values['camper_fee7'] );
   }    
   if( isset( $values['camper_fee8'] ) ) {
       $item->update_meta_data( 'Camper 9', $values['camper_fee8'] );
   }    
   if( isset( $values['camper_fee9'] ) ) {
       $item->update_meta_data( 'Camper 10', $values['camper_fee9'] );
   }                                    
}


// admin side all camper details
require_once('admin_manage_camper.php');
// admin side all camper details

// waiting list front
require_once('waiting_list.php');
// waiting list front


// admin side duplicate camper details
// require_once('admin_duplicate_camper.php');
// admin side duplicate camper details


// ###################### messaging ################################### // 
// Avoid add to cart for non logged user (or not registered)
add_filter( 'woocommerce_add_to_cart_validation', 'logged_in_customers_validation', 10, 3 );
function logged_in_customers_validation( $passed, $product_id, $quantity) {
    if( ! is_user_logged_in() ) {
        $passed = false;
        // Displaying a custom message
        $message = __("You must be logged in to sign up for this camp.", "woocommerce");
        $button_link = get_permalink( get_option('woocommerce_myaccount_page_id') );
        $button_text = __("Login/Enroll", "woocommerce");
        $message .= ' <a href="'.$button_link.'" class="login-register button" style="">'.$button_text.'</a>';

        wc_add_notice( $message, 'error' );
    }
    return $passed;
}

// message if no camper selected
function camper_name_validation() { 
    if ( empty( $_REQUEST['camprtxt'] ) || $_REQUEST['camprtxt'] == '' ) {
        wc_add_notice( __( 'You must select at least one camper.', 'woocommerce' ), 'error' );
        return false;
    }
    return true;
}
add_action( 'woocommerce_add_to_cart_validation', 'camper_name_validation', 10, 3 );
// ###################### messaging ################################### // 


// ###################### registraion ################################### // 
require_once('parent_registration.php');
// ###################### registraion ################################### // 




// change the category name on single product page
add_filter( 'ngettext', 'change_category_label', 9999, 5 );
function change_category_label( $translation, $single, $plural, $number, $domain ) {
   if ( is_product() && 'woocommerce' === $domain ) {
      // This will only trigger on the WooCommerce single product page
      $translation = ( 1 === $number ) ? str_ireplace( 'Category:', 'State:', $translation ) : str_ireplace( 'Tags:', 'Brands:', $translation );
  }
  return $translation;
}

add_action( 'woocommerce_add_to_cart_validation', 'wc_add_to_cart_validation', 11, 3 ); 
function wc_add_to_cart_validation( $passed, $product_id, $quantity ) {
    if ( $quantity == 0 ){
        wc_add_notice( __( 'You must have at least one camper Enrolled to book…', 'woocommerce' ), 'error' );
        $passed = false;
    }
    return $passed;
}

function change_role_name() {
    global $wp_roles;
    if ( ! isset( $wp_roles ) )
        $wp_roles = new WP_Roles();
    $wp_roles->roles['customer']['name'] = 'Guardian/Parent';
    $wp_roles->role_names['customer'] = 'Guardian/Parent';           
}
add_action('init', 'change_role_name');
// remove guardian role
remove_role( 'Gaurdian' );


// add booked camper menu in admin
if ( is_admin() ) {
    add_action( 'admin_menu', 'menu_booked_camper', 100 );
}

function menu_booked_camper() {
    add_submenu_page(
        'edit.php?post_type=product', // parent url
        // __( 'Booked Camper' ), // title
        __( 'Camp Rosters' ), // title
        __( '<span id="activeroster">Rosters</span>' ), // Label of menu
        'manage_woocommerce', // Required user capability to mange woocommerce page
        // 'booked-camper', // url/slug
        'camp-rosters', // url/slug
        'generate_booked_camper' // function
    );

     // This is the hidden page
    add_submenu_page(
      null, 
      'Rosters detail page',
      'Rosters detail page', 
      'manage_woocommerce', 
      'booked-camper-detail', 
      'generate_booked_camper_detail'
  );

     // This is the hidden page for attandance pdf
    add_submenu_page(
      null, 
      'Attandance pdf',
      'Attandance pdf', 
      'manage_options', 
      'attandance-pdf', 
      'generate_booked_att_pdf'
  );
     // This is the hidden page for year based roster
    add_submenu_page(
      null, 
      'Rosters',
      '2022', 
      'manage_options', 
      'rosters', 
      'rosters'
  );
    // waiting list sub menu
    // waiting list -> 2023 year selection
    add_submenu_page(
        'edit.php?post_type=product', // parent url
        __( 'Wait List' ), // title
        __( 'Wait List' ), // Label of menu
        'manage_woocommerce', // Required user capability to mange woocommerce page
        'wait-list', // url/slug
        'call_camper_waiting_list' // function
    );    
    // This is the hidden page for waiting list records - camps
    add_submenu_page(
      null, 
      'Wait List',
      '2023', 
      'manage_options', 
      'waitinglistFunc', 
      'waitinglistFunc'
  );
    // This is the hidden page for waiting list records - waiting list form recrods
    add_submenu_page(
      null, 
      'Wait List',
      '2023', 
      'manage_options', 
      'waitinglistRecord', 
      'waitinglistRecord'
  );    
}

function generate_booked_camper() {
    // require_once('admin-booked-camper.php');
    require_once('admin-camp-rosters.php');
}

function generate_booked_camper_detail() {
    require_once('admin-booked-camper-detail.php');
}

function call_camper_waiting_list() {
    // require_once('admin-booked-camper.php');
    require_once('admin_waiting_list.php');
}
function waitinglistFunc() {
    // require_once('admin-booked-camper.php');
    require_once('admin_waiting_list_data.php');
}
function waitinglistRecord() {
    // require_once('admin-booked-camper.php');
    require_once('admin_waiting_list_record.php');
}


function generate_booked_att_pdf() {
    require_once('attpdf.php');
}

function rosters() {
    require_once('admin-booked-camper.php');
}