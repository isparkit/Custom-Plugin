<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css' type='text/css' media='all' />
<link rel='stylesheet' href='https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css' type='text/css' media='all' />
<?php
if(isset($_GET['yr'])){
    $searchorderid = 11086; 
if($_GET['yr']){
    $qyr = $_GET['yr'];
    if($qyr == '2022'){
        $qoid = '<= 11086';
    }
    elseif($qyr == '2023'){
        $qoid = '> 11086';
    }
    else{
        $qoid = '> 0';   
    }
}
}
else{
    $qyr = 'All';
    $qoid = '> 0'; 
}
 ?>
<script type="text/javascript">
    jQuery(document).prop('title', '<?php echo $qyr . ' Rosters'; ?>');
</script>
<div class="wrap">
    <?php
    global $wpdb;
    // $product_title = $producttitle->name;
    $wc_item_meta = $wpdb->prefix . 'woocommerce_order_itemmeta';
    $wc_items = $wpdb->prefix . 'woocommerce_order_items';
    $wc_posts = $wpdb->prefix . 'posts';
    $wc_post_meta = $wpdb->prefix . 'postmeta';
    $tbl_manage_booking = $wpdb->prefix . 'booking_student';
    $wc_user_meta = $wpdb->prefix . 'usermeta';   
    $counttotal = 0;
    $counts = 0;
    $countm = 0;
    $countl = 0;
    $countxl = 0;
    $countxxl = 0;
    $countxxxl = 0;
    $countys = 0;
    $countym = 0;
    $countyl = 0;
    $countyxl = 0;
    $countyxxl = 0;
    $countyxxxl = 0;
    $countas = 0;
    $countam = 0;
    $countal = 0;
    $countaxl = 0;
    $countaxxl = 0;
    $countaxxxl = 0;
    $countno = 0;    
    // Define Query Arguments
    if($qyr == '2022'){
    $loop = new WP_Query( array(
        'post_type'      => 'product',
        // 'post_status'    => 'publish',
        // 's'           => $qyr,
        'posts_per_page' => -1,
        'order'          => 'DESC',
        'orderby'        => 'ID',
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'product_type',
                'field'    => 'slug',
                'terms'    => 'simple',
            ),
            array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => '2023',
            'operator'  => 'NOT IN'
            ),
        ),
    ) );
    }
    else{
        // new
    $loop = new WP_Query( array(
        'post_type'      => 'product',
        // 'post_status'    => 'publish',
        // 's'           => $qyr,
        'posts_per_page' => -1,
        'order'          => 'DESC',
        'orderby'        => 'ID',
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'product_type',
                'field'    => 'slug',
                'terms'    => 'simple',
            ),
            array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => '2023',
            ),
        ),
    ) );
    }

    // Get products number
    $product_count = $loop->post_count;
    $plugin_dir = WP_PLUGIN_DIR . '/booking-addon-custom'; 
   // echo '<h1 class="wp-heading-inline">Booked Camps ('. $product_count .') <br><span id="showshirtcount" style="font-size:14px"></span></h1>';
    // echo '<p id="showshirtcount"></p>';
    // include('pdf-booked-camps.php');
     if(isset($_POST['downloadpdf']) && isset($_POST['yr'])){ include($plugin_dir.'/pdf-booked-camps.php'); }
    ?>
    <h1 class="wp-heading-inline"> <?php echo $qyr  . ' Rosters: '.$product_count; ?></h1>
    <h6 id="showshirtcount"></h6>
    <p>
        <span id="showcounts"></span>
        <span id="showcountm"></span>
        <span id="showcountl"></span>
        <span id="showcountys"></span>
        <span id="showcountym"></span>
        <span id="showcountyl"></span>
        <span id="showcountyxl"></span>
        <span id="showcountyxxl"></span>
        <span id="showcountyxxxl"></span>   
        <span id="showcountas"></span>
        <span id="showcountam"></span>
        <span id="showcountal"></span>
        <span id="showcountaxl"></span>
        <span id="showcountaxxl"></span>
        <span id="showcountaxxxl"></span>
        <span id="showcountno"></span>
    </p>
<form method="POST">
<input type="hidden" name="yr" value="<?php echo $_GET['yr']; ?>">
  <input type="submit" name="downloadpdf" value="Download Shirt PDF">
</form>
<div>&nbsp;</div>
<table id="example" class="table table-striped" style="width:100%">
    <thead> 
        <tr>
            <th>Camp ID</th>
            <th>Camp Name</th>
            <th>Shirt Sizes</th>
            <th>Booked</th>
            <th>Open</th>
            <th>Details</th>
        </tr>
    </thead>
    <tbody>
        <?php

        foreach($loop->posts as $pro)
        {
            $allirs = $pro->ID;
            $allpostnm = $pro->post_title;
            // new

  
            // removed this query
            // $get_orderid = "(SELECT * FROM $wc_items WHERE order_item_name = '$product_title')";
            // step1
            $get_orderid = "(SELECT DISTINCT oim.order_item_id, oi.order_item_name, oi.order_id FROM $wc_item_meta oim 
            INNER JOIN $wc_items oi ON (oi.order_item_id = oim.order_item_id)
            LEFT JOIN $wc_posts p ON p.ID = oi.order_id
            WHERE (oim.meta_key = '_product_id' AND oim.meta_value = $allirs) AND p.post_status = 'wc-completed' AND oi.order_id $qoid  )";
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
            // echo "oitemids";
            // echo $oitemids;
            // step 2 - get customer ids from order
            // removed
            // $get_ordercustid = "(SELECT meta_value FROM $wc_post_meta WHERE post_id IN ($oids) AND meta_key = '_customer_user')";
            // $get_ordercustid = "(SELECT DISTINCT(meta_value) FROM $wc_post_meta where meta_key = '_customer_user' AND post_id IN ($oids))";
            $get_ordercustid = "(SELECT DISTINCT(meta_value) FROM $wc_post_meta where meta_key = '_customer_user' AND post_id IN ($oids))";
            $customer_details = $wpdb->get_results($get_ordercustid);
            $ocidData = [];
            foreach ($customer_details as $cust){
                $ocidData[] = $cust->meta_value;
            }
            $ocids = implode(",",$ocidData);  //customer id
            // step 3
            $today = date("Y-m-d");
            $camp_count = 0;
            $get_ordercamperid ="(SELECT p.post_status, oim.meta_id, oim.order_item_id, oim.meta_value, oi.order_item_name as oi_camp, oi.order_id as oi_order_id,  pm.meta_value as user_id,  SUBSTRING_INDEX(oim.meta_value, ',', 1) as bookingcamper, SUBSTRING_INDEX(oim.meta_value, 'Shirt Size: ', -1) as osize,
    SUBSTRING_INDEX(SUBSTRING_INDEX(oim.meta_value, ', Shirt Size: ', 1),'Age: ',-1) as oage,
    pmodate.meta_value as order_date,
    bc.size, bc.bdate, bc.allergiescheck, bc.medidevicecheck, bc.allergies, bc.medidevice, bc.additional, bc.pfname, bc.plname, bc.relationship, bc.phone, bc.p2fname, bc.p2lname, bc.relationship2, bc.phone2, bc.photo,
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
    LEFT JOIN $tbl_manage_booking bc ON SUBSTRING_INDEX(TRIM(oim.meta_value), ' ', 1) = SUBSTRING_INDEX(TRIM(bc.fname), ' ', 1) AND SUBSTRING_INDEX(TRIM(SUBSTRING_INDEX(oim.meta_value, ', ', 1)),' ', -1) = SUBSTRING_INDEX(TRIM(bc.lname), ' ', -1)
    WHERE oim.meta_key in( 'Camper 1', 'Camper 2', 'Camper 3', 'Camper 4', 'Camper 5', 'Camper 6', 'Camper 7', 'Camper 8', 'Camper 9', 'Camper 10' ) AND oim.order_item_id in($oitemids) AND p.post_status = 'wc-completed' AND oi.order_id $qoid
    GROUP BY oim.meta_id )";
            // $get_ordercamperid ="(SELECT oim.meta_id, oim.order_item_id, oim.meta_value, oi.order_item_name as oi_camp, oi.order_id as oi_order_id,  pm.meta_value as user_id
            // FROM $wc_item_meta oim
            // LEFT JOIN $wc_posts p ON p.ID = oi.order_id
            // LEFT JOIN $wc_items oi ON oi.order_item_id = oim.order_item_id
            // LEFT JOIN $wc_post_meta pm ON oi.order_id = pm.post_id AND pm.meta_key = '_customer_user'
            // WHERE oim.meta_key in( 'Camper 1', 'Camper 2', 'Camper 3', 'Camper 4', 'Camper 5', 'Camper 6', 'Camper 7', 'Camper 8', 'Camper 9', 'Camper 10' ) AND oim.order_item_id in($oitemids) AND p.post_status = 'wc-completed'
            // GROUP BY oim.meta_id )";
            $camper_details = $wpdb->get_results($get_ordercamperid);
            
            // echo "<pre>";
            // print_r($get_ordercamperid);
            // echo "<pre>";
            // print_r($camper_details);

            $total = count( $camper_details );
            // echo $allpostnm . ' ' . $total .'<br>';
            $product = wc_get_product($pro->ID);
            $stock = $product->get_stock_quantity();
            $counttotal += $total;
  
            ?>
            <tr>
                <td><?php echo '#'.$pro->ID; ?></td>
                <td><?php echo $pro->post_title; ?></td>
                <td><?php 
$sizes1 = [];
 if( $total != '0'){
// echo "<pre>";
// print_r($camper_details);    
foreach ($camper_details as $shirtval) {

// get order id echo $shirtval->oi_order_id;
$sizes1[] = $shirtval->osize;
//echo $sizes; 
// echo $shirtval->osize;   
$sizes = array_replace($sizes1,array_fill_keys(array_keys($sizes1, null),''));
} 
$vals = array_count_values($sizes); 
$array_keys = array_keys($vals);
$array_values = array_values($vals);


for($i=0; $i<count($array_keys); $i++) {
    // echo '<br>';
    echo ' ['.$array_keys[$i].': '.$array_values[$i].'] ';//add \n or \br tag
    if($array_keys[$i] == 'S'){
        $sizes = $array_values[$i];
       $counts += $sizes;
    }
    if($array_keys[$i] == 'M'){
        $sizem = $array_values[$i];
       $countm += $sizem;
    }
    if($array_keys[$i] == 'L'){
        $sizel = $array_values[$i];
       $countl += $sizel;
    }  
    if($array_keys[$i] == 'XL'){
        $sizexl = $array_values[$i];
       $countxl += $sizexl;
    } 
    if($array_keys[$i] == 'XXL'){
        $sizexxl = $array_values[$i];
       $countxxl += $sizexxl;
    }            
    if($array_keys[$i] == 'XXXL'){
        $sizexxxl = $array_values[$i];
       $countxxxl += $sizexxxl;
    }   
    if($array_keys[$i] == 'Y S'){
        $sizeys = $array_values[$i];
       $countys += $sizeys;
    }
    if($array_keys[$i] == 'Y M'){
        $sizeym = $array_values[$i];
       $countym += $sizeym;
    }
    if($array_keys[$i] == 'Y L'){
        $sizeyl = $array_values[$i];
       $countyl += $sizeyl;
    }  
    if($array_keys[$i] == 'Y XL'){
        $sizeyxl = $array_values[$i];
       $countyxl += $sizeyxl;
    } 
    if($array_keys[$i] == 'Y XXL'){
        $sizeyxxl = $array_values[$i];
       $countyxxl += $sizeyxxl;
    }            
    if($array_keys[$i] == 'Y XXXL'){
        $sizeyxxxl = $array_values[$i];
       $countyxxxl += $sizeyxxxl;
    }  
    if($array_keys[$i] == 'A S'){
        $sizeas = $array_values[$i];
       $countas += $sizeas;
    }
    if($array_keys[$i] == 'A M'){
        $sizeam = $array_values[$i];
       $countam += $sizeam;
    }
    if($array_keys[$i] == 'A L'){
        $sizeal = $array_values[$i];
       $countal += $sizeal;
    }  
    if($array_keys[$i] == 'A XL'){
        $sizeaxl = $array_values[$i];
       $countaxl += $sizeaxl;
    } 
    if($array_keys[$i] == 'A XXL'){
        $sizeaxxl = $array_values[$i];
       $countaxxl += $sizeaxxl;
    }            
    if($array_keys[$i] == 'A XXXL'){
        $sizeaxxxl = $array_values[$i];
       $countaxxxl += $sizeaxxxl;
    } 
    if($array_keys[$i] == ''){
        $sizeno = $array_values[$i];
       $countno += $sizeno;
    }      
    }
}
else{
    echo "0";
}
?></td>
                <td><?php echo $total; ?> </td>
                <td><?php echo $stock; ?></td>
                <td><?php //$yr = date("Y"); ?>
                    <a href="edit.php?post_type=product&page=booked-camper-detail&id=<?php echo $pro->ID; ?>&yr=<?php echo $qyr; ?>" class="btn btn-primary">More Details</a></td>
            </tr>
            <?php
            // new
        
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th>ID</th>
            <th>Camp Name</th>
            <th>Shirt Sizes</th>
            <th>Booked</th>
            <th>Open</th>
            <th>Details</th>
        </tr>
    </tfoot>
</table>
</div>
<input type="hidden" name="allshirtcount" id="allshirtcount" value="<?php echo $counttotal; ?>">
<input type="hidden" name="counts" id="counts" value="<?php echo $counts; ?>">
<input type="hidden" name="countm" id="countm" value="<?php echo $countm; ?>">
<input type="hidden" name="countl" id="countl" value="<?php echo $countl; ?>">
<input type="hidden" name="countys" id="countys" value="<?php echo $countys; ?>">
<input type="hidden" name="countym" id="countym" value="<?php echo $countym; ?>">
<input type="hidden" name="countyl" id="countyl" value="<?php echo $countyl; ?>">
<input type="hidden" name="countyxl" id="countyxl" value="<?php echo $countyxl; ?>">
<input type="hidden" name="countyxxl" id="countyxxl" value="<?php echo $countyxxl; ?>">
<input type="hidden" name="countyxxxl" id="countyxxxl" value="<?php echo $countyxxxl; ?>">
<input type="hidden" name="countas" id="countas" value="<?php echo $countas; ?>">
<input type="hidden" name="countam" id="countam" value="<?php echo $countam; ?>">
<input type="hidden" name="countal" id="countal" value="<?php echo $countal; ?>">
<input type="hidden" name="countaxl" id="countaxl" value="<?php echo $countaxl; ?>">
<input type="hidden" name="countaxxl" id="countaxxl" value="<?php echo $countaxxl; ?>">
<input type="hidden" name="countaxxxl" id="countaxxxl" value="<?php echo $countaxxxl; ?>">
<input type="hidden" name="countno" id="countno" value="<?php echo $countno; ?>">
<script type='text/javascript' src='https://code.jquery.com/jquery-3.5.1.js' id='jquery-core-js'></script>
<script type='text/javascript' src='https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js' id='jquery-core-js'></script>
<script type='text/javascript' src='https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js' id='jquery-core-js'></script>
<script type="text/javascript">
    $(document).ready(function() {
        // shot total shirt count at top
        var myinput = document.getElementById('allshirtcount').value;

        var counts = document.getElementById('counts').value;
        var countm = document.getElementById('countm').value;
        var countl = document.getElementById('countl').value;

        var countys = document.getElementById('countys').value;
        var countym = document.getElementById('countym').value;
        var countyl = document.getElementById('countyl').value;
        var countyxl = document.getElementById('countyxl').value;
        var countyxxl = document.getElementById('countyxxl').value;
        var countyxxxl = document.getElementById('countyxxxl').value;        

        var countas = document.getElementById('countas').value;
        var countam = document.getElementById('countam').value;
        var countal = document.getElementById('countal').value;
        var countaxl = document.getElementById('countaxl').value;
        var countaxxl = document.getElementById('countaxxl').value;
        var countaxxxl = document.getElementById('countaxxxl').value;

        var countno = document.getElementById('countno').value;  

        document.getElementById('showshirtcount').innerHTML = ' Estimated Total Shirts Count ('+myinput+')';

        document.getElementById('showcounts').innerHTML = ' [S: '+counts+'] ';
        document.getElementById('showcountm').innerHTML = ' [M: '+countm+'] ';
        document.getElementById('showcountl').innerHTML = ' [L: '+countl+'] ';

        document.getElementById('showcountys').innerHTML = ' [Y S: '+countys+'] ';
        document.getElementById('showcountym').innerHTML = ' [Y M: '+countym+'] ';
        document.getElementById('showcountyl').innerHTML = ' [Y L: '+countyl+'] ';
        document.getElementById('showcountyxl').innerHTML = ' [Y XL: '+countyxl+'] ';
        document.getElementById('showcountyxxl').innerHTML = ' [Y XXL: '+countyxxl+'] ';
        document.getElementById('showcountyxxxl').innerHTML = ' [Y XXXL: '+countyxxxl+'] ';

        document.getElementById('showcountas').innerHTML = ' [A S: '+countas+'] ';
        document.getElementById('showcountam').innerHTML = ' [A M: '+countam+'] ';
        document.getElementById('showcountal').innerHTML = ' [A L: '+countal+'] ';
        document.getElementById('showcountaxl').innerHTML = ' [A XL: '+countaxl+'] ';
        document.getElementById('showcountaxxl').innerHTML = ' [A XXL: '+countaxxl+'] ';
        document.getElementById('showcountaxxxl').innerHTML = ' [A XXXL: '+countaxxxl+'] ';

        document.getElementById('showcountno').innerHTML = ' [Unknown: '+countno+'] ';

        // datatable
        $('#example').DataTable( {
            "order": [[ 3, "desc" ]],
            "pageLength": 25
        } );
    } );
</script>

   

