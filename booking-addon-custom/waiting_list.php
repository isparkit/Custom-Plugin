<?php 
add_shortcode('shotcode1','shortcode2');
function shortcode2(){
ob_start();
$contenct = '';
    if ( is_user_logged_in() ) {
    // login he
        global $wpdb;
        $active_user_id = get_current_user_id();
        $tbl_manage_booking = $wpdb->prefix . 'booking_student';
        $getcamper = "(SELECT * FROM $tbl_manage_booking WHERE p_id = $active_user_id)";
        $getcamperdetail = $wpdb->get_results($getcamper);

if(isset($_POST['submit'])){
        if(!isset($_POST['wbsid'])) {
                if(count($getcamperdetail) >= 1){
                    $contenct .= "<p class='error-camper'>Please Select one Campers!</p>";
                }
                else{
                    $contenct .= "<p class='error-camper'>You must have at least one camper registered to join our wait list. Please add your first camper here ";
                    $contenct .= "<a href=".get_permalink( get_option('woocommerce_myaccount_page_id') ).">My Account</a>";
                    $contenct .= "</p>";
                }
            }
        if(!isset($_POST['campsid'])) {
            $contenct .= "<p class='error-camper'>Please select atleast one camp week!</p>";
        }
        if(isset($_POST['wbsid']) && isset($_POST['campsid'])){
            $waiting_table_name = $wpdb->prefix . 'waiting_list';
            $bsid = implode(',', $_POST['wbsid']);    
            $campsid = $_POST['campsid'];
            $note = $_POST['note'];
            $inquirydate = date('Y-m-d');
            foreach ($campsid as $key => $campid) {
                $wpdb->insert(
                    $waiting_table_name,
                    array(
                        'pid' => $active_user_id,
                        'bsid' => $bsid,
                        'campid' => $campid,
                        'note' => $note,
                        'inquirydate' => $inquirydate,
                    ),
                    array(
                        '%d','%s','%d','%s','%s'
                    )
                );
            }
            $contenct .="<p class='green-camper'>Join Wait List Data Successfully</p>";
        }

}


$contenct .='<div class="wrap">';
$contenct .='<form method="POST" class="waitinglist-form" id="waitinglist-requird">';
$contenct .='<div class="campers-data">';
$contenct .='<div class="formtitle">Select Camper(s)</div>';
foreach ($getcamperdetail as $key => $value) {
    $campersvalue =  $camperVal = $value->fname .' '. $value->lname . ', Age: '. $value->bdate.', Size: '. $value->size;
    $contenct .='<p><input type="checkbox" name="wbsid[]" value="' .$value->id. '"><label>'.$campersvalue.'</label></p>';
}
$contenct .='</div> ';
$args = array(
    'post_type'      => 'product',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'tax_query' => array(
        'relation' => 'AND',
        array(
            'taxonomy' => 'product_type',
            'field'    => 'slug',
            'terms'    => 'simple',
        ),
        array(
            'taxonomy' => 'product_visibility',
            'field'    => 'name',
            'terms'    => array('outofstock'),
        ),
        array(
            'taxonomy'     => 'product_cat',
            'field'        => 'name',
            'terms'        => '2023'
        ),
    ),
);
$loop = new WP_Query( $args );
$contenct .='<div class="campers-data camp-week-data">';
$contenct .='<div class="formtitle">Select Camp Week(s)</div>';
while ( $loop->have_posts() ) : $loop->the_post();
    global $product;
$contenct .='<p><input type="checkbox" name="campsid[]" value="'.get_the_id().'"><label>' .get_the_title().'</label></p>';
endwhile;
$contenct .='</div>';
$contenct .='<div class="campers-data additional-data">';
$contenct .='<div class="formtitle">Additional Information</div>';
$contenct .='<textarea rows="4" cols="50" name="note"></textarea>';
$contenct .='</div>';
$contenct .='<input type="submit" name="submit" class="fusion-button button-flat fusion-button-default-size fusion-button-default-span  form-form-submit button-default" value="Join Waitlist">';
$contenct .='</form>';
$contenct .='</div>';


    }
    else
    {
        $contenct .="<p class='error-camper'>You must be logged in to join our wait list.</p>";
    }

return $contenct;

}

