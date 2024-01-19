<?php
$product_title = $producttitle->name;
$wc_item_meta = $wpdb->prefix . 'woocommerce_order_itemmeta';
$wc_items = $wpdb->prefix . 'woocommerce_order_items';
$wc_posts = $wpdb->prefix . 'posts';
$wc_post_meta = $wpdb->prefix . 'postmeta';
$tbl_manage_booking = $wpdb->prefix . 'booking_student';
$wc_user_meta = $wpdb->prefix . 'usermeta';
$wc_user = $wpdb->prefix . 'users';

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

// print_r($oids);
// get all order items id from order table
$oitemids = implode(",",$oitemidData);
// step 2 - get customer ids from order
// removed
// $get_ordercustid = "(SELECT meta_value FROM $wc_post_meta WHERE post_id IN ($oids) AND meta_key = '_customer_user')";
$get_ordercustid = "(SELECT DISTINCT(meta_value) FROM $wc_post_meta where meta_key = '_customer_user' AND post_id IN ($oids))";

// print_r($get_ordercustid);
$customer_details = $wpdb->get_results($get_ordercustid);
// echo  $get_ordercustid->num_rows;
$ocidData = [];
foreach ($customer_details as $cust){
  $ocidData[] = $cust->meta_value;
}
$ocids = implode(",",$ocidData);  //customer id
// step 3
$today = date("Y-m-d");
$camp_count = 0;
// if($yr >= 2023){
//   $getlastorderid = 11086;
// }
$get_ordercamperid ="(SELECT p.post_status, oim.meta_id, oim.order_item_id, oim.meta_value, oi.order_item_name as oi_camp, oi.order_id as oi_order_id,  pm.meta_value as user_id,  SUBSTRING_INDEX(oim.meta_value, ',', 1) as bookingcamper, SUBSTRING_INDEX(oim.meta_value, 'Shirt Size: ', -1) as osize,
SUBSTRING_INDEX(SUBSTRING_INDEX(oim.meta_value, ', Shirt Size: ', 1),'Age: ',-1) as oage,
pmodate.meta_value as order_date,
bc.id, bc.size, bc.bdate, bc.allergiescheck, bc.medidevicecheck, bc.allergies, bc.medidevice, bc.additional, bc.pfname, bc.plname, bc.relationship, bc.phone, bc.p2fname, bc.p2lname, bc.relationship2, bc.phone2, bc.photo, u.user_email, 
MAX(
IF(
um.meta_key = 'first_name', um.meta_value,
NULL
)
) AS fname,
MAX(
IF(
um.meta_key = 'last_name', um.meta_value,
NULL
)
) AS lname,
MAX(
IF(
um.meta_key = 'billing_phone', um.meta_value,
NULL
)
) AS billingphone,
MAX(
IF(
um.meta_key = 'afreg_additional_1808',
um.meta_value, NULL
)
) AS g1fn,
MAX(
IF(
um.meta_key = 'afreg_additional_1809',
um.meta_value, NULL
)
) AS g1ln,
MAX(
IF(
um.meta_key = 'afreg_additional_1810',
um.meta_value, NULL
)
) AS g1phone,
MAX(
IF(
um.meta_key = 'afreg_additional_1811',
um.meta_value, NULL
)
) AS g1relation,
MAX(
IF(
um.meta_key = 'afreg_additional_1815',
um.meta_value, NULL
)
) AS g2fn,
MAX(
IF(
um.meta_key = 'afreg_additional_1816',
um.meta_value, NULL
)
) AS g2ln,
MAX(
IF(
um.meta_key = 'afreg_additional_1817',
um.meta_value, NULL
)
) AS g2phone,
MAX(
IF(
um.meta_key = 'afreg_additional_1818',
um.meta_value, NULL
)
) AS g2relation,
MAX(
IF(
um.meta_key = 'afreg_additional_1821',
um.meta_value, NULL
)
) AS t1fn,
MAX(
IF(
um.meta_key = 'afreg_additional_1822',
um.meta_value, NULL
)
) AS t1ln,
MAX(
IF(
um.meta_key = 'afreg_additional_1823',
um.meta_value, NULL
)
) AS t1phone,
MAX(
IF(
um.meta_key = 'afreg_additional_1824',
um.meta_value, NULL
)
) AS t1relation,
MAX(
IF(
um.meta_key = 'afreg_additional_1826',
um.meta_value, NULL
)
) AS t2fn,
MAX(
IF(
um.meta_key = 'afreg_additional_1827',
um.meta_value, NULL
)
) AS t2ln,
MAX(
IF(
um.meta_key = 'afreg_additional_1828',
um.meta_value, NULL
)
) AS t2phone,
MAX(
IF(
um.meta_key = 'afreg_additional_1829',
um.meta_value, NULL
)
) AS t2relation
FROM $wc_item_meta oim
LEFT JOIN $wc_items oi ON oi.order_item_id = oim.order_item_id
LEFT JOIN $wc_posts p ON p.ID = oi.order_id
LEFT JOIN $wc_post_meta pm ON oi.order_id = pm.post_id AND pm.meta_key = '_customer_user'
LEFT JOIN $wc_post_meta pmodate ON oi.order_id IN(pmodate.post_id) AND pmodate.meta_key = '_paid_date'
LEFT JOIN $wc_user_meta um ON pm.meta_value = um.user_id
LEFT JOIN $wc_user u ON u.ID = um.user_id
LEFT JOIN $tbl_manage_booking bc ON 
SUBSTRING_INDEX(SUBSTRING_INDEX(TRIM(oim.meta_value), ']', 1), '[ID: ', -1) = bc.id
WHERE oim.meta_key in( 'Camper 1', 'Camper 2', 'Camper 3', 'Camper 4', 'Camper 5', 'Camper 6', 'Camper 7', 'Camper 8', 'Camper 9', 'Camper 10' ) AND oim.order_item_id in($oitemids) AND p.post_status = 'wc-completed' AND oi.order_id > $searchorderid
GROUP BY oim.meta_id )";



$camper_details = $wpdb->get_results($get_ordercamperid);
if (count($camper_details)> 0){ //check condition if record exists or not
// echo "<pre>";
// print_r($get_ordercamperid);
  $total = count( $camper_details );
  echo "<div class='div'>";
  echo "<h5>" .  $product_title . ' (' . $total .")</h5>";
// echo "<ul id='listcamper'>";
  ?>
  <?php
  $dupData = [];
  foreach ($camper_details as $current_key => $campr){
    foreach ($camper_details as $search_key => $search_array) {
      if ($search_array->bookingcamper == $campr->bookingcamper) {
        if ($search_key != $current_key) {
          $dupData[] = $campr->bookingcamper;
        }
      }
    }
    $duplicatecamper1 = implode(",",$dupData);
    $duplicatecamper = implode(',', array_unique(explode(',', $duplicatecamper1)));
  }          
  foreach ($camper_details as $campr){

    $camp_count = $camp_count + 1;
    $diffCurr = date_diff(date_create($campr->bdate), date_create($campr->order_date));
    $camperCurrAge = $diffCurr->format('%y');

    $diff = date_diff(date_create($campr->bdate), date_create($today));
    $camperAge = $diff->format('%y');
    if($campr->additional == '') { $additioncheck = 'No'; } else { $additioncheck = 'Yes'; }
                                    // attendance data
    $ocamperData = $campr->bookingcamper . ', Age: ' . $camperAge . ', Size: ' . $campr->size . ', H: ' . $campr->allergiescheck . ', MD: ' . $campr->medidevicecheck . ', ADDL: ' . $additioncheck . ', Photo: ' . $campr->photo;
    $attpdfmeta[] = $ocamperData;

                                    // signin data for pdf start////////////////////////////////////////////
    $signinpdfmeta[] = $campr->bookingcamper;
                                   // echo $ocamperData . "<br>";
    $ys[] =  substr_count($ocamperData,"Size: Y S");
    $ym[] =  substr_count($ocamperData,"Size: Y M");
    $yl[] =  substr_count($ocamperData,"Size: Y L");
    $yxl[] =  substr_count($ocamperData,"Size: Y XL");
    $yxxl[] =  substr_count($ocamperData,"Size: Y XXL");
    $yxxxl[] =  substr_count($ocamperData,"Size: Y XXXL");
    $as[] =  substr_count($ocamperData,"Size: A S");
    $am[] =  substr_count($ocamperData,"Size: A M");
    $al[] =  substr_count($ocamperData,"Size: A L");
    $axl[] =  substr_count($ocamperData,"Size: A XL");
    $axxl[] =  substr_count($ocamperData,"Size: A XXL");
    $axxxl[] =  substr_count($ocamperData,"Size: A XXXL");
                                    // old sizes
    $s[] =  substr_count($ocamperData,"Size: S");
    $m[] =  substr_count($ocamperData,"Size: M");
    $l[] =  substr_count($ocamperData,"Size: L");
    $xl[] =  substr_count($ocamperData,"Size: XL");
    $xxl[] =  substr_count($ocamperData,"Size: XXL");
    $xxxl[] =  substr_count($ocamperData,"Size: XXXL");
                                    // 
  }

                 } //check condition if record exists or not
                 ?>
                 <?php
                 echo "</div>";
                 ?>
                 <?php if (count($camper_details)> 0){  //check condition if record exists or not?>
                 </div>
                 <div class="div">
                  <h5>Summary:</h5>
                  <p><b><?php echo $producttitle->name; ?></b> <span class="spanTshirt">Total T-Shirts: <?php echo $total; ?></span></p>
                  <?php if(number_format(array_sum($ys)) != 0) { echo '<span class="spanTshirt">'.' Y S: '. array_sum($ys) .'</span>'; } ?>
                  <?php if(number_format(array_sum($ym)) != 0) { echo '<span class="spanTshirt">'.' Y M: '. array_sum($ym) .'</span>'; } ?>
                  <?php if(number_format(array_sum($yl)) != 0) { echo '<span class="spanTshirt">'.' Y L: '. array_sum($yl) .'</span>'; } ?>
                  <?php if(number_format(array_sum($yxl)) != 0) { echo '<span class="spanTshirt">'.'Y XL: '. array_sum($yxl) .'</span>'; } ?>
                  <?php if(number_format(array_sum($yxxl)) != 0) { echo '<span class="spanTshirt">'.' Y XXL: '. array_sum($yxxl) .'</span>'; } ?>
                  <?php if(number_format(array_sum($yxxxl)) != 0) { echo '<span class="spanTshirt">'.' Y XXXL: '. array_sum($yxxxl) .'</span>'; } ?>
                  <?php if(number_format(array_sum($as)) != 0) { echo '<span class="spanTshirt">'.' A S: '. array_sum($as) .'</span>'; } ?>
                  <?php if(number_format(array_sum($am)) != 0) { echo '<span class="spanTshirt">'.' A M: '. array_sum($am) .'</span>'; } ?>
                  <?php if(number_format(array_sum($al)) != 0) { echo '<span class="spanTshirt">'.' A L: '. array_sum($al) .'</span>'; } ?>
                  <?php if(number_format(array_sum($axl)) != 0) { echo '<span class="spanTshirt">'.' A XL: '. array_sum($axl) .'</span>'; } ?>
                  <?php if(number_format(array_sum($axxl)) != 0) { echo '<span class="spanTshirt">'.' A XXL: '. array_sum($axxl) .'</span>'; } ?>
                  <?php if(number_format(array_sum($axxxl)) != 0) { echo '<span class="spanTshirt">'.' A XXXL: '. array_sum($axxxl) .'</span>'; }
                      // for old sizes
                  if(number_format(array_sum($s)) != 0) { echo '<span class="spanTshirt">'.' S: '. array_sum($s) .'</span>'; }
                  if(number_format(array_sum($m)) != 0) { echo '<span class="spanTshirt">'.' M: '. array_sum($m) .'</span>'; }
                  if(number_format(array_sum($l)) != 0) { echo '<span class="spanTshirt">'.' L: '. array_sum($l) .'</span>'; }
                  if(number_format(array_sum($xl)) != 0) { echo '<span class="spanTshirt">'.' XL: '. array_sum($xl) .'</span>'; }
                  if(number_format(array_sum($xxl)) != 0) { echo '<span class="spanTshirt">'.' XXL: '. array_sum($xxl) .'</span>'; }
                  if(number_format(array_sum($xxxl)) != 0) { echo '<span class="spanTshirt">'.'XXXL: '. array_sum($xxxl) .'</span>'; }
                  ?>
                  <?php
              // condition to check if no value
                  ?>
                  <?php
                      // for pdf header
                  if(number_format(array_sum($s)) != 0) {$apdfheaders = ' [S: '. array_sum($s).']';}
                  if(number_format(array_sum($m)) != 0) {$apdfheaderm = ' [M: '. array_sum($m).']';}
                  if(number_format(array_sum($l)) != 0) {$apdfheaderl = ' [L: '. array_sum($l).']';}
                  if(number_format(array_sum($xl)) != 0) {$apdfheaderxl = ' [XL: '. array_sum($xl).']';}
                  if(number_format(array_sum($xxl)) != 0) {$apdfheaderxxl = ' [XXL: '. array_sum($xxl).']';}
                  if(number_format(array_sum($xxxl)) != 0) {$apdfheaderxxxl = ' [XXXL: '. array_sum($xxxl).']';}
                  if(number_format(array_sum($ys)) != 0) {$apdfheaderys = ' [Y S: '. array_sum($ys).']';}
                  if(number_format(array_sum($ym)) != 0) {$apdfheaderym = ' [Y M: '. array_sum($ym).']';}
                  if(number_format(array_sum($yl)) != 0) {$apdfheaderyl = ' [Y L: '. array_sum($yl).']';}
                  if(number_format(array_sum($yxl)) != 0) {$apdfheaderyxl = ' [Y XL: '. array_sum($yxl).']';}
                  if(number_format(array_sum($yxxl)) != 0) {$apdfheaderyxxl = ' [Y XXL: '. array_sum($yxxl).']';}
                  if(number_format(array_sum($yxxxl)) != 0) {$apdfheaderyxxxl = ' [Y XXXL: '. array_sum($yxxxl).']';}
                  if(number_format(array_sum($as)) != 0) {$apdfheaderas = ' [A S: '. array_sum($as).']';}
                  if(number_format(array_sum($am)) != 0) {$apdfheaderam = ' [A M: '. array_sum($am).']';}
                  if(number_format(array_sum($al)) != 0) {$apdfheaderal = ' [A L: '. array_sum($al).']';}
                  if(number_format(array_sum($axl)) != 0) {$apdfheaderaxl = ' [A XL: '. array_sum($axl).']';}
                  if(number_format(array_sum($axxl)) != 0) {$apdfheaderaxxl = ' [A XXL: '. array_sum($axxl).']';}
                  if(number_format(array_sum($axxxl)) != 0) {$apdfheaderaxxxl = ' [A XXXL: '. array_sum($axxxl).']';}
                  ?>
                </div>
                <div class='div'>
                  <form method="POST">
                    <input type="submit" name="signin" value="Sign In / Sign Out PDF">
                    <input type="submit" name="attendance" value="Roster PDF">
                    <input type="submit" name="camperdetail" value="Camper Details PDF">
                    <input type="submit" name="sessionemails" value="Session Emails PDF">
                  </form>
                </div>  
                <br>
                <table id="example" class="table table-striped" style="width:100%">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Camper Details</th>
                      <th>DOB</th>
                      <th>Phone</th>
                      <th>EC1</th>
                      <th>EC2</th>
                      <th>TA1</th>
                      <th>TA2</th>
                      <th>H</th>
                      <th>MD</th>
                      <th>ADDL</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $camper1data = []; 

                    foreach ($camper_details as $campr){

                      $diffCurr = date_diff(date_create($campr->bdate), date_create($campr->order_date));
                      $camperCurrAge = $diffCurr->format('%y');

                      $diff = date_diff(date_create($campr->bdate), date_create($today));
                      $camperAge = $diff->format('%y');

                      $camper1data[] = $campr->bookingcamper.' Age: '.$camperAge;
                      if($campr->additional){  $castatus = "Yes"; } else { $castatus = "No";}
                        // camper information
                      $mycamperpdf = $campr->bookingcamper . ', Age: ' . $camperAge . ', Size: ' . $campr->size;
                      
                                    // camper parent details 
                      if($campr->fname || $campr->lname || $campr->billingphone)
                      { 
                        $myparentdata = $campr->fname . ' ' . $campr->lname . ', ' . $campr->billingphone;
                      }
                      else
                      {
                      // $myparentdata = 'Data not found!';
                        $myparentdata = '';
                      }
                      // camper emergendy 1 details   
                      if(! empty($campr->pfname)){ $getfn = $campr->pfname .' '; } else { $getfn = ''; }
                      if(! empty($campr->plname)){ $getln = $campr->plname .' '; } else { $getln = ''; }
                      if(! empty($campr->phone)){ $getphone = $campr->phone .' '; } else { $getphone = ''; }
                      if(! empty($campr->relationship)){ $getrela = ' ('.$campr->relationship .'), '; } else { $getrela = ' '; }
                      if(! empty($campr->g1fn)){ $get1fn = $campr->g1fn . ' '; } else { $get1fn = ''; }
                      if(! empty($campr->g1ln)){ $get1ln = $campr->g1ln . ' '; } else { $get1ln = ''; }
                      if(! empty($campr->g1phone)){ $get1phone = $campr->g1phone. ' '; } else { $get1phone = ''; }
                      if(! empty($campr->g1relation)){ $get1rela = ' ('.$campr->g1relation .') '; } else { $get1rela = ' '; }

                      $mye1data = $getfn . $getln . $getrela . $getphone ;
                      $mye11data = $get1fn . $get1ln . $get1rela . $get1phone ; 

// camper emergendy 2 details  

                      if(! empty($campr->p2fname)){ $gete1fn = $campr->p2fname .' '; } else { $gete1fn = ''; }
                      if(! empty($campr->p2lname)){ $gete1ln = $campr->p2lname .' '; } else { $gete1ln = ''; }
                      if(! empty($campr->phone2)){ $gete1phone = $campr->phone2 .' '; } else { $gete1phone = ''; }
                      if(! empty($campr->relationship2)){ $gete1rela = ' ('.$campr->relationship2 .'), '; } else { $gete1rela = ' '; }
                      if(! empty($campr->g2fn)){ $gete2fn = $campr->g2fn . ' '; } else { $gete2fn = ''; }
                      if(! empty($campr->g2ln)){ $gete2ln = $campr->g2ln . ' '; } else { $gete2ln = ''; }
                      if(! empty($campr->g2phone)){ $gete2phone = $campr->g2phone. ' '; } else { $gete2phone = ''; }
                      if(! empty($campr->g2relation)){ $gete2rela = ' ('.$campr->g2relation .') '; } else { $gete2rela = ' '; }

                      $mye2data = $gete1fn . $gete1ln . $gete1rela . $gete1phone ;
                      $mye22data = $gete2fn . $gete2ln . $gete2rela . $gete2phone ; 


                      // camper Trusted adult 1 details   
                      if($campr->t1fn || $campr->t1ln || $campr->t1relation || $campr->t1phone)
                      { 
                        $myt1data = $campr->t1fn . ' ' . $campr->t1ln . ' (' . $campr->t1relation . ') '. $campr->t1phone ;
                      }
                      else {
                      // $myt1data = "No Data found!";
                        $myt1data = "";
                      }
                      // camper Trusted adult 2 details   
                      if($campr->t2fn || $campr->t2ln || $campr->t2relation || $campr->t2phone)
                      { 
                        $myt2data = $campr->t2fn . ' ' . $campr->t2ln . ' (' . $campr->t2relation . ') '. $campr->t2phone ;
                      }
                      else {
                        // $myt2data = "No Data found!";
                        $myt2data = "";
                      }
                      if(count(array($campr->bookingcamper))>1){ $aaa = '<span style="color:red;">'.$campr->bookingcamper.'</span>'; }
                      else{
                        $aaa = '<span>'.$campr->bookingcamper.'</span>';
                      }
                      $myt2pdf[] = $myt2data;   
                      if($campr->osize == $campr->size){
                        $changebox = ' style="color:black"';
                      }      
                      else{
                        $changebox = 'href="post.php?post='.$campr->oi_order_id.'&action=edit" style="color:red" target="_blank"';
                      }
                      $order_url = 'href="post.php?post='.$campr->oi_order_id.'&action=edit" style="color:black; text-decoration:none;" target="_blank"';
                                    // oage
                      // echo '<td>'.ucwords($campr->bookingcamper).'Shirt: '.$campr->size. ' DOB: '.$campr->bdate.' CURR AGE: '.$camperAge. ' ORDER AGE: ' . $campr->oage . '</td>';
                      if($duplicatecamper == $campr->bookingcamper){
                        $camperdeet = '<span style="background:red; padding:0px 3px">'.ucwords($campr->bookingcamper).'</span>';
                      }
                      else{
                        $camperdeet = '<span >'.ucwords($campr->bookingcamper).'</span>';
                      }    
                      // if(isset($mye1data))
                      //   {
                      //    echo ucwords($mye1data);
                      //   };              
                      echo '<tr>';
                      // echo '<td>'.$campr->oi_order_id.' '.$campr->id.'</td>';
                      echo '<td>'.$campr->id.'</td>';
                      echo '<td><b><a '.$order_url.'>'.$camperdeet. '</a></b><br><a '.$changebox.'>(Shirt: '.$campr->size. ')</a> <br><span style="font-size:12px" id="dob">CURR AGE: '.$camperAge. '<br>ORDER AGE: ' . $campr->oage . '</span></td>';
                      echo '<td>'.$campr->bdate.'</td>';
                      echo '<td>'.ucwords($myparentdata).' <a href="mailto:'. $campr->user_email .'">'. $campr->user_email .'</a></td>';
                      echo '<td>'.ucwords($mye1data). '' . $mye11data .'</td>';
                      echo '<td>'.ucwords($mye2data). '' . $mye22data .'</td>';
                      echo '<td>'.ucwords($myt1data).'</td>';
                      echo '<td>'.ucwords($myt2data).'</td>';
                      echo '<td>'.ucwords($campr->allergiescheck).'</td>';
                      echo '<td>'.ucwords($campr->medidevicecheck).'</td>';
                      echo '<td>'.ucwords($campr->additional).'</td>';
                      echo '</tr>';
                    }
                    ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>ID</th>
                      <th>Camper Details</th>
                      <th>DOB</th>
                      <th>Phone</th>
                      <th>EC1</th>
                      <th>EC2</th>
                      <th>TA1</th>
                      <th>TA2</th>
                      <th>H</th>
                      <th>MD</th>
                      <th>ADDL</th>
                    </tr>
                  </tfoot>
                </table>  
                
                <?php
                $camper1details = implode(", ",$camper1data);
                $plugin_dir = WP_PLUGIN_DIR . '/booking-addon-custom'; 
                ?>
                <!-- signup pdf tcpdf start -->
                <?php
                if(isset($_POST['signin'])){ include($plugin_dir.'/pdf-signin.php'); }
                ?>
                <!-- signup pdf tcpdf end -->
                <!-- attendance pdf start -->
                <?php include($plugin_dir.'/pdf-attendance.php'); ?>
                <!-- attendance pdf end -->
                <!-- camper pdf start -->
                <?php
                if(isset($_POST['camperdetail'])){ include($plugin_dir.'/pdf-camper-detail.php'); }
                ?>
                <!-- camper pdf end -->
                <!-- camper pdf start -->
                <?php
                if(isset($_POST['sessionemails'])){ include($plugin_dir.'/pdf-session-emails.php'); }
                ?>
                <!-- camper pdf end -->

              </div>
              <div>

                <?php
                function get_orders_ids_by_product_id( $product_id ){
                  global $wpdb;
                  $results = $wpdb->get_col("
                    SELECT order_items.order_item_id
                    FROM {$wpdb->prefix}woocommerce_order_items as order_items
                    LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
                    LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
                    WHERE posts.post_type = 'shop_order'
                    AND order_items.order_item_type = 'line_item'
                    AND order_item_meta.meta_key = '_product_id'
                    AND order_item_meta.meta_value = '$product_id'
                    ");
                  return $results;
                }
            // get all order id from the product id
                $aa = get_orders_ids_by_product_id($product_id);
                $orderitemid = implode(", ",$aa);
            // echo "<pre>";
                  // print_r($orderitemid);
                $campers_metakey = "'Camper 1', 'Camper 2', 'Camper 3', 'Camper 4', 'Camper 5', 'Camper 6', 'Camper 7', 'Camper 8', 'Camper 9', 'Camper 10'";
                  // get all order item id from the order id
                $campers = ("SELECT woim.meta_value FROM $wc_item_meta woim WHERE order_item_id in($orderitemid) AND meta_key in($campers_metakey) ORDER BY order_item_id" );
                $campersResult = $wpdb->get_results( $campers);
                echo '<h5> Order Data: '.count($campersResult).'</h5>';
                  // echo "<pre>";
                        // print_r($campers);
                ?>
                <style type="text/css">
                  #orders table {
                    border-collapse: collapse;
                    width: auto;
                  }
                  #orders thead tr{background-color: #ccc;}
                  #orders th, #orders td {
                    text-align: left;
                    padding: 8px;
                  }
                  #orders tr:nth-child(even) {background-color: #f2f2f2;}
                </style>
                <table border="1" id="orders">
                  <thead >
                    <tr>
                      <th>Sr No</th>
                      <th>Number of Woocommerce Orders</th>
                    </tr>
                  </thead>    
                  <tbody>
                    <?php
                    $i=1;
                    $camper2data = [];
                    foreach ($campersResult as $key => $value) {
                      $c2data = str_replace(","," ",substr($value->meta_value, 0, strpos($value->meta_value, ", Shirt Size")));
                      $camper2data[] = $c2data;
                      echo "<tr><td>".$i."</td><td>".$value->meta_value."</td></tr>";
                      $i++;
                    }
                    $camper2details = implode(", ",$camper2data);

                    ?>
                  </tbody>
                </table>
              </div>
              <?php
            //check condition if record exists or not
            }  else {

              echo "<h5>No Record Found!<h5>";
            }
        //check condition if record exists or not
          ?>
</div>          
<div style="clear:both;">&nbsp;</div>
<style type="text/css">
  #wpfooter{position: relative !important;}
</style>