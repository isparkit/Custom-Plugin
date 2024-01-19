<?php 
$plugin_dir = WP_PLUGIN_DIR . '/booking-addon-custom'; 
if(isset($_POST['attendance'])){
   function fetch_signup_data()  
   {  
      $output = '';  
      $product_id = $_GET['id'];
      $producttitle = wc_get_product( $product_id );
      global $wpdb;
	   // order id
      $product_title = $producttitle->name;
      $wc_item_meta = $wpdb->prefix . 'woocommerce_order_itemmeta';
      $wc_items = $wpdb->prefix . 'woocommerce_order_items';
      $wc_posts = $wpdb->prefix . 'posts';
      $wc_post_meta = $wpdb->prefix . 'postmeta';
      $tbl_manage_booking = $wpdb->prefix . 'booking_student';
      $wc_user_meta = $wpdb->prefix . 'usermeta';
		// step1

     $searchorderid = 11086;
       if($_GET['yr'] > '2022'){
         
       // 2023 or bigger // new
           $qry = 'AND oi.order_id > '.$searchorderid;
           $qoid = '> 11086';
           $qryjoin = ' LEFT JOIN '.$tbl_manage_booking.' bc ON 
       SUBSTRING_INDEX(SUBSTRING_INDEX(TRIM(oim.meta_value), "]", 1), "[ID: ", -1) = bc.id ';
       }
       else{
         // old
            $qry = 'AND oi.order_id <= '.$searchorderid;
            $qoid = '<= 11086';
            $qryjoin = ' LEFT JOIN '.$tbl_manage_booking.' bc ON SUBSTRING_INDEX(TRIM(oim.meta_value), " ", 1) = SUBSTRING_INDEX(TRIM(bc.fname), " ", 1) AND SUBSTRING_INDEX(TRIM(SUBSTRING_INDEX(oim.meta_value, ", ", 1))," ", -1) = SUBSTRING_INDEX(TRIM(bc.lname), " ", -1) ';
       } 

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
     $ocidData = [];
     foreach ($customer_details as $cust){
         $ocidData[] = $cust->meta_value;
     }
		$ocids = implode(",",$ocidData);  //customer id		

		// step 3

		$today = date("Y-m-d");
		$camp_count = 0;


      $get_ordercamperid ="(SELECT oim.meta_id, oim.order_item_id, oim.meta_value, oi.order_item_name as oi_camp, oi.order_id as oi_order_id,  pm.meta_value as user_id,  SUBSTRING_INDEX(oim.meta_value, ',', 1) as bookingcamper, SUBSTRING_INDEX(oim.meta_value, 'Shirt Size: ', -1) as osize,
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
      $qryjoin 
      WHERE oim.meta_key in( 'Camper 1', 'Camper 2', 'Camper 3', 'Camper 4', 'Camper 5', 'Camper 6', 'Camper 7', 'Camper 8', 'Camper 9', 'Camper 10' ) AND oim.order_item_id in($oitemids) AND p.post_status = 'wc-completed'
      GROUP BY oim.meta_id ORDER BY bc.fname )";

      // echo "<pre>";
      // print_r($get_ordercamperid);
      // exit;

      $camper_details = $wpdb->get_results($get_ordercamperid);      
      foreach ($camper_details as $campr){

         $osignupdata = $campr->bookingcamper;
         $diff = date_diff(date_create($campr->bdate), date_create($today));
         $camperAge = $diff->format('%y');
			// camper information
         $mycamperpdf = $campr->bookingcamper . ', Age: ' . $camperAge . ', Size: ' . $campr->size;

         if($campr->additional){ $addicheck = "Yes"; } else { echo $addicheck = "No";}

         $output .= '<tr>

         <td> '.ucfirst($campr->bookingcamper).' (' .$campr->fname.' '. $campr->lname. ') </td>
         <td>'.$camperAge.'</td>
         <td>'.$campr->size.'</td>
         <td>'.ucfirst($campr->allergiescheck).'</td>
         <td>'.ucfirst($campr->medidevicecheck).'</td>
         <td>'.$addicheck.'</td>
         </tr>
         ';
     }  

     return $output;  
 }  

      // require_once('./././tcpdf_include.php');
   $pdflogo = 'https://marinesciencecamp.com/wp-content/uploads/2022/01/MSC-Logo-Product.jpg';
 require_once($plugin_dir.'/TCPDF/examples/tcpdf_include.php');
 $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
 $obj_pdf->SetCreator(PDF_CREATOR);  
 $obj_pdf->setPrintHeader(false);  
 $obj_pdf->setPrintFooter(true);  
 $obj_pdf->setFooterData(array(0,64,0), array(0,64,128));
 $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

 $obj_pdf->SetAutoPageBreak(TRUE, 10);  
      // $obj_pdf->SetFont('helvetica', '', 11);  
 $obj_pdf->AddPage();  
 $obj_pdf->Image($pdflogo,1,1,70,0,'JPG');  
 $obj_pdf->SetFont('helvetica','B',12);  
 $obj_pdf->Cell( 0, 10, $producttitle->name, 0, 0, 'R' ); 
 $obj_pdf->Ln();
 $obj_pdfTotaltshirt = $total . ' Shirts';
			// $obj_pdfTotalCamper = 'Campers (' . $total . ')';
 $obj_pdf->Cell( 0, 10, $obj_pdfTotaltshirt, 0, 0, 'R' ); 
// $pdfAlltshirtHeader = $apdfheaderys . '' . $apdfheaderym . '' . $apdfheaderyl . '' . $apdfheaderyxl . '' . $apdfheaderyxxl . '' . $apdfheaderyxxxl . '' . $apdfheaderas . '' . $apdfheaderam . '' . $apdfheaderal . '' . $apdfheaderaxl . '' . $apdfheaderaxxl . '' . $apdfheaderaxxxl;
 $pdfAlltshirtHeader = $apdfheaders . '' . $apdfheaderm . '' . $apdfheaderl . '' . $apdfheaderxl . '' . $apdfheaderxxl . '' . $apdfheaderxxxl . '' . $apdfheaderys . '' . $apdfheaderym . '' . $apdfheaderyl . '' . $apdfheaderyxl . '' . $apdfheaderyxxl . '' . $apdfheaderyxxxl . '' . $apdfheaderas . '' . $apdfheaderam . '' . $apdfheaderal . '' . $apdfheaderaxl . '' . $apdfheaderaxxl . '' . $apdfheaderaxxxl;
 $obj_pdf->Ln();
 $obj_pdf->Cell( 0, 10, $pdfAlltshirtHeader, 0, 0, 'R' );

 $obj_pdf->Ln(20);
 $obj_pdf->SetFont('helvetica','',12);  
 $content = '';  
 $content .= '  

 <table border="1" cellpadding="3">
 <tr  style="background-color:#C1E5FC;" >
 <th style="width:60%;"> <b>Camper Count ('.$total.')</b></th>
 <th style="width:8%;" align="center"> <b>AGE</b></th>
 <th style="width:8%;" align="center"> <b>SIZE</b></th>
 <th style="width:8%;" align="center"> <b>H</b></th>
 <th style="width:8%;" align="center"> <b>MD</b></th>
 <th style="width:8%;" align="center"> <b>ADDL</b></th>
 </tr>
 ';  
 $content .= fetch_signup_data();  
 $content .= '</table>';  
 $obj_pdf->writeHTML($content);  
 ob_end_clean();
 $obj_pdf->Output('Roster-'.$product_title.'.pdf', 'D');  
 exit;
}
?>