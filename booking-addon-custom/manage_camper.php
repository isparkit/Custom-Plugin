<?php
// Manages my-account/my-camper 

// 1. Register new endpoint (URL) for My Account page
// Note: Re-save Permalinks or it will give 404 error
function booking_add_add_camper_endpoint() {
    add_rewrite_endpoint( 'add-camper', EP_ROOT | EP_PAGES );
}

add_action( 'init', 'booking_add_add_camper_endpoint' );

// ------------------
// 2. Add new query var

function booking_add_camper_query_vars( $vars ) {
    $vars[] = 'add-camper';
    return $vars;
}

add_filter( 'query_vars', 'booking_add_camper_query_vars', 0 );

// ------------------
// 3. Insert the new endpoint into the My Account menu

//keep logout the last menu item
function lougout_at_last_my_account_menu_items( $items ) {
    unset($items['customer-logout']);
    $items['customer-logout'] = 'Logout';
    return $items;
}
add_filter( 'woocommerce_account_menu_items', 'lougout_at_last_my_account_menu_items', 999 );

function booking_add_add_camper_link_my_account( $items ) {
    $items['add-camper'] = 'Manage Campers';
    return $items;
}

add_filter( 'woocommerce_account_menu_items', 'booking_add_add_camper_link_my_account', 30, 1 );

// ------------------
// 4. Add content to the new tab

function booking_add_camper_content() {
    echo '<h3>Manage Campers</h3>';
// echo do_shortcode( '[add_campers]' );
// 18 fname lname bdate size emergencycheck pfname plname relationship phone p2fname p2lname relationship2 phone2 allergiescheck allergies medidevicecheck medidevice additional
    global $wpdb;
    $db_table_name = $wpdb->prefix . 'booking_student';
    $pid = get_current_user_id();
    if(isset($_POST['submit']) || isset($_POST['edit'])){


        $fname1 = str_replace(' ', '', $_POST['fname']);
        $lname1 = str_replace(' ', '', $_POST['lname']);
        $fname = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $fname1);
        $lname = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $lname1);

        $bdate = $_POST['bdate'];
        $size = $_POST['size'];
        $emergencycheck = $_POST['emergencycheckval'];
        $pfname = $_POST['pfname'];
        $plname = $_POST['plname'];
        $relationship = $_POST['relationship'];
        $phone = $_POST['phone'];
        $p2fname = $_POST['p2fname'];
        $p2lname = $_POST['p2lname'];
        $relationship2 = $_POST['relationship2'];
        $phone2 = $_POST['phone2'];
        $allergiescheck = $_POST['allergiescheck'];
        $allergies = $_POST['allergies'];
        $medidevicecheck = $_POST['medidevicecheck'];
        $medidevice = $_POST['medidevice'];
        $additional = $_POST['additional'];
        $photo = $_POST['photo'];
        $acceptance = $_POST['acceptance'];
        $waiver = $_POST['waiver'];
        $initials = $_POST['initials'];
    }



    if(isset($_POST['submit'])){
        $total_count_query = "select count(*) from $db_table_name WHERE p_id=$pid";
        $total_num = $wpdb->get_var($total_count_query);

        if($total_num >= 10){
            echo '<p style="color:red; text-align: center;">You can not add more than 10 Campers!</p>';
        }
        else {
            $count_query = "select count(*) from $db_table_name WHERE fname = '".$fname."' AND lname = '".$lname."' AND p_id = '".$pid."'";
            $num = $wpdb->get_var($count_query);

            if($num > 0){
                echo '<p style="color:red; text-align: center;">Already Found!</p>';
            }
            else{

                $wpdb->insert(
                    $db_table_name,
                    array(
                        'p_id' => $pid,
                        'fname' => $fname,
                        'lname' => $lname,
                        'bdate' => $bdate,
                        'size' => $size,
                        'emergencycheck' => $emergencycheck,
                        'pfname' => $pfname,
                        'plname' => $plname,
                        'relationship' => $relationship,
                        'phone' => $phone,
                        'p2fname' => $p2fname,
                        'p2lname' => $p2lname,
                        'relationship2' => $relationship2,
                        'phone2' => $phone2,
                        'allergiescheck' => $allergiescheck,
                        'allergies' => $allergies,
                        'medidevicecheck' => $medidevicecheck,
                        'medidevice' => $medidevice,
                        'additional' => $additional,
                        'photo' => $photo,
                        'acceptance' => $acceptance,
                        'waiver' => $waiver,
                        'initials' => $initials,
                    ),
                    array(
                        '%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s'
                    )
                );
            }
        }
    }

// update query
    $editid = isset($_GET['id']) ? $_GET['id'] : '';
    if(isset($_POST['edit'])){
        $wpdb->update($db_table_name, array('p_id' => $pid, 'fname' => $fname, 'lname' => $lname, 'bdate' => $bdate, 'size' => $size, 'emergencycheck' => $emergencycheck, 'pfname' => $pfname, 'plname' => $plname, 'relationship' => $relationship, 'phone' => $phone, 'p2fname' => $p2fname, 'p2lname' => $p2lname, 'relationship2' => $relationship2, 'phone2' => $phone2, 'allergiescheck' => $allergiescheck, 'allergies' => $allergies, 'medidevicecheck' => $medidevicecheck, 'medidevice' => $medidevice, 'additional' => $additional, 'photo' => $photo, 'acceptance' => $acceptance, 'waiver' => $waiver, 'initials' => $initials ), array('id'=>$editid ) );

    }
// delete query
    $deleteid = isset($_GET["del"]) ? $_GET["del"] : '';
    if(isset($_GET['del'])) {
        $wpdb->delete($db_table_name, array('id'=>$deleteid ) );
    }
    ?>
    <!-- table -->
    <!-- <a class="woocommerce-Button button" href="#camperForm">+ Add Camper</a> -->
    <div class="table-wrapper">
        <div class="scrollable">
            <table class="table-camper">
                <thead>
                    <tr>
                        <th>Camper</th>
                        <th>Camper DOB</th>
                        <th>T-Shirt Size</th>
                        <th>Edit</th>
                        <!-- <th>Delete</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $results = $wpdb->get_results( "SELECT * FROM $db_table_name WHERE p_id= $pid");
                    if(!empty($results))
                    {
                        foreach($results as $row){
                            ?>
                            <tr>
                                <td data-column="First Name"><?php echo '[ID: '.$row->id .'] '.$row->fname .' '. $row->lname ; ?></td>
                                <td data-column="Last Name">
                                    <?php 
                                    echo $newDate = date("m/d/Y", strtotime($row->bdate));

                                ?></td>
                                <td data-column="Column-1"><?php echo $row->size; ?></td>
                                <td data-column="Edit">
                                    <a href="?id=<?php echo $row->id; ?>" id="cedit">Edit</a>
                                </td>
<!--             <td data-column="Delete">
            <a href="?del=<?php echo $row->id; ?>" name="delete" onclick="return confirm('Are you sure you want to Remove?');">Delete</a>

        </td> -->
    </tr>
    <?php
}} else {
    ?>
    <tr>
        <td colspan="5" align="center" >
            <p style="color:red; text-align: center;">No Camper(s) Found </br> Please add your first camper below!</p>
        </td>
    </tr>
<?php } ?>
</tbody>
</table>
</div></div>
<!-- table end -->
<!-- form start -->
<?php

// if(isset($_POST['edit']))
// {
// $cid = $_GET['id'];
// $wpdb->update( $db_table_name, array( 'fname' => $fname, 'bdate' => $bdate),array('id'=>$cid));
// }

if(isset($_GET['id'])){
    $cid = $_GET['id'];
    $results = $wpdb->get_results( "SELECT * FROM $db_table_name WHERE id = $cid");
    if(!empty($results))
    {
        $gfname = '';
        $glname = '';
        $gbdate = '';
        $gsize = '';
        $gpfname = '';
        $gplname = '';
        $grelationship = '';
        $gphone = '';
        $gp2fname = '';
        $gp2lname = '';
        $grelationship2 = '';
        $gphone2 = '';
        $gallergies = '';
        $gmedidevice = '';
        $gadditional = '';
        $gallergiescheck = '';
        $gmedidevicecheck = '';
        $gemergencycheck = '';
        $gphoto = '';
        $gacceptance = '';
        $gwaiver = '';
        $ginitials = '';
        foreach($results as $rowinner)
        {   
            $gfname .= str_replace(' ', '', $rowinner->fname);
            $glname .= str_replace(' ', '', $rowinner->lname);
            $gbdate .= $rowinner->bdate;
            $gsize .= $rowinner->size;
            $gpfname .= $rowinner->pfname;
            $gplname .= $rowinner->plname;
            $grelationship .= $rowinner->relationship;
            $gphone .= $rowinner->phone;
            $gp2fname .= $rowinner->p2fname;
            $gp2lname .= $rowinner->p2lname;
            $grelationship2 .= $rowinner->relationship2;
            $gphone2 .= $rowinner->phone2;
            $gallergies .= $rowinner->allergies;
            $gmedidevice .= $rowinner->medidevice;
            $gadditional .= $rowinner->additional;
            $gallergiescheck .= $rowinner->allergiescheck;
            $gmedidevicecheck .= $rowinner->medidevicecheck;
            $gemergencycheck .= $rowinner->emergencycheck;
            $gphoto .= $rowinner->photo;
            $gacceptance .= $rowinner->acceptance;
            $gwaiver .= $rowinner->waiver;
            $ginitials .= $rowinner->initials;
        }  
    } 
}


?>

<form id="camperForm" class="form-camper" method="post">
    <label class="checkbox-label">Camper Details*</label>
    <p><?php if(isset($_GET['id'])) { echo $gfname . ' ' . $glname; } ?></p>
    <div class="double">
        <div class="sideleft">
            
            <?php if(isset($_GET['id'])) { echo ''; } else { echo '<label>First Name</label>'; } ?>
            <input type="<?php if(isset($_GET['id'])) { echo 'hidden'; } else { echo 'text'; } ?>" id="fname" name="fname" placeholder="First Name" required value="<?php if(isset($_GET['id'])) { echo $gfname; } ?>">
        </div>
        <div class="side-right">
            <!-- <label>Last Name</label> -->
            <?php if(isset($_GET['id'])) { echo ''; } else { echo '<label>Last Name</label>'; } ?>
            <input type="<?php if(isset($_GET['id'])) { echo 'hidden'; } else { echo 'text'; } ?>" id="lname" name="lname" placeholder="Last Name" required value="<?php if(isset($_GET['id'])) { echo $glname; } ?>" >
        </div>
    </div>
    <?php if(isset($_GET['id'])){ echo '<div class="single">'; } else { echo '<div class="double">'; } ?>
    <div class="sideleft">
        <!-- <label>Date of Birth</label> -->
        <?php if(isset($_GET['id'])) { echo ''; } else { echo '<label>Date o Birth</label>'; } ?>
        <input type="<?php if(isset($_GET['id'])) { echo 'hidden'; } else { echo 'date'; } ?>"  id="bdate" name="bdate" required value="<?php if(isset($_GET['id'])) { echo $gbdate; } ?>" autocomplete="off" placeholder="Date of Birth ">

    </div>
    <div class="side-right">
        <label>Shirt Size</label>
        <select name="size" required>
            <?php if(isset($_GET['id'])){ echo '<option value="'.$gsize.'">'.$gsize.'</option>'; } ?>
            <option value="">T-Shirt Size</option>
            <option value="Y S">Youth S</option>
            <option value="Y M">Youth M</option>
            <option value="Y L">Youth L</option>
            <option value="Y XL">Youth XL</option>
<!--             <option value="Y XXL">Youth XXL</option>
            <option value="Y XXXL">Youth XXXL</option> -->
            <option value="A S">Adult S</option>
            <option value="A M">Adult M</option>
            <option value="A L">Adult L</option>
            <option value="A XL">Adult XL</option>
<!--             <option value="A XXL">Adult XXL</option>
            <option value="A XXXL">Adult XXXL</option> -->
        </select>
        
    </div>
</div>
<div class="single">
    <div class="checkbox-single">
        <label class="checkbox-label">Emergency Contact*</label>
        <input type="checkbox" id="emergencycheck" name="emergencycheck" value="yes"
        <?php if(isset($_GET['id'])) { echo ($gemergencycheck == 'yes') ?  "checked" : "" ; }  ?> checked>
        <input type="hidden" name="emergencycheckval" id="emergencycheckval" >
        <label class="light" for="development">Use primary Parent(s)/Guardian provided during enrollment.</label>
    </div>
</div>
<div id="emergencycheckdiv">
   <label class="checkbox-label">Emergency Contact</label>
   <div class="double">
    <div class="sideleft">
        <input type="text" id="pfname" name="pfname" placeholder="First Name" value="<?php if(isset($_GET['id'])) { echo $gpfname; } ?>">
    </div>
    <div class="side-right">
        <input type="text" id="plname" name="plname" placeholder="Last Name" value="<?php if(isset($_GET['id'])) { echo $gplname; } ?>">
    </div>
</div>
<div class="double">
    <div class="sideleft">
        <input type="text" id="relationship" name="relationship" placeholder="Relationship to Camper" value="<?php if(isset($_GET['id'])) { echo $grelationship; } ?>">
    </div>
    <div class="side-right">
        <input type="number"  id="phone" name="phone" placeholder="Phone Number" value="<?php if(isset($_GET['id'])) { echo $gphone; } ?>">
    </div>
</div>

<div class="single">
    <div class="checkbox-single">        
        <label class="checkbox-label">Add an additional Emergency Contact?</label>
        <input type="radio" value="yes" name="addi" id="addi">
        <label class="light" for="addi">Yes</label>
        <input type="radio" value="no" name="addi" id="addi" checked>
        <label class="light" for="addi">No</label>
    </div>
</div>
<div id="addicheckdiv">
    <div class="double">
        <div class="sideleft">
            <input type="text" id="p2fname" name="p2fname" placeholder="First Name" value="<?php if(isset($_GET['id'])) { echo $gp2fname; } ?>">
        </div>
        <div class="side-right">
            <input type="text" id="p2lname" name="p2lname" placeholder="Last Name" value="<?php if(isset($_GET['id'])) { echo $gp2lname; } ?>">
        </div>
    </div>
    <div class="double">
        <div class="sideleft">
            <input type="text" id="relationship2" name="relationship2" placeholder="Relationship to Camper" value="<?php if(isset($_GET['id'])) { echo $grelationship2; } ?>">
        </div>
        <div class="side-right">
            <input type="number" id="phone2" name="phone2" placeholder="Phone" value="<?php if(isset($_GET['id'])) { echo $gphone2; } ?>">
        </div>
    </div>
</div>
</div>
<div class="single">
    <div class="checkbox-single">
        <label class="checkbox-label">Does your camper have any allergies, or health concerns?*</label>
        <input type="radio" id="allergiescheck" value="yes" name="allergiescheck" required 
        <?php if(isset($_GET['id'])) { echo ($gallergiescheck == 'yes') ?  "checked" : "" ; } ?> >
        <label class="light" for="allergiescheck">Yes</label>
        <input type="radio" id="allergiescheck" value="no" name="allergiescheck" required 
        <?php if(isset($_GET['id'])) { echo ($gallergiescheck == 'no') ?  "checked" : "" ; } ?> >
        <label class="light" for="allergiescheck">No</label>
        <?php //if(isset($_GET['id'])) { echo $gallergiescheck; } ?>
    </div>
</div>
<div class="single" id="allergiescheckdiv">
    <label for="message">Describe the allergies or health concerns so our team may best prepare and accommodate</label>
    <textarea name="allergies" id="allergies"><?php if(isset($_GET['id'])) { echo $gallergies; } ?></textarea>
</div>
<div class="single">
    <div class="checkbox-single">
        <label class="checkbox-label">Will your camper have a medical device?*</label>
        <input type="radio" id="medidevicecheck" value="yes" name="medidevicecheck" required 
        <?php if(isset($_GET['id'])) { echo ($gmedidevicecheck == 'yes') ?  "checked" : "" ; } ?>>
        <label class="light" for="medidevicecheck" required>Yes</label>
        <input type="radio" id="medidevicecheck" value="no" name="medidevicecheck" required 
        <?php if(isset($_GET['id'])) { echo ($gmedidevicecheck == 'no') ?  "checked" : "" ; } ?>>
        <label class="light" for="medidevicecheck" required>No</label>
    </div>
</div>
<div class="single" id="medidevicecheckdiv">
    <label for="message">Describe the condition, care of device, plan of action, etc.</label>
    <textarea name="medidevice" id="medidevice"><?php if(isset($_GET['id'])) { echo $gmedidevice; } ?></textarea>
</div>
<div class="single">
    <label for="message" class="checkbox-label">Additional care information? Share here!</label>
    <textarea name="additional" id="additional"><?php if(isset($_GET['id'])) { echo $gadditional; } ?></textarea>
</div>

<div class="single">
    <div class="checkbox-single">
        <label class="checkbox-label">Include in camp photos/activities?*</label>
        <input type="radio" id="photo" value="yes" name="photo" required checked 
        <?php if(isset($_GET['id'])) { echo ($gphoto == 'yes') ?  "checked" : "" ; } ?>>
        <label class="light" for="photo" required>Yes</label>
        <input type="radio" id="photo" value="no" name="photo" required 
        <?php if(isset($_GET['id'])) { echo ($gphoto == 'no') ?  "checked" : "" ; } ?>>
        <label class="light" for="photo" required>No</label>
    </div>
</div>
<div class="single">
    <div class="checkbox-single">
        <label class="checkbox-label">Acceptance of Terms and Waiver*</label>
        <input type="checkbox" id="acceptance" name="acceptance" value="yes"
        <?php if(isset($_GET['id'])) { echo ($gacceptance == 'yes') ?  "checked" : "" ; }  ?>  required>
        <label class="light" for="development">I have read, understood, and agreed to the attendee & guardian code of conduct, inclement weather, refund, transfer, and cancellation policy found <a href="/terms/" target="_blank">here</a>.</label>
    </div>
</div>
<div class="single">
    <div class="checkbox-single">
<!--             <label class="checkbox-label">I have read, understood, and agree to your </label>
-->            <input type="checkbox" id="waiver" name="waiver" value="yes" required
<?php if(isset($_GET['id'])) { echo ($gwaiver == 'yes') ?  "checked" : "" ; }  ?> >
<label class="light" for="development">I have read, understood, and agree to your  <a href="/wp-content/uploads/2022/01/MSC-Waiver.pdf" target="_blank">waiver</a>.</label>
</div>
</div>   
<div class="single">
    <label class="checkbox-label"></label>
    <input type="text" name="initials" placeholder="Initials" value="<?php if(isset($_GET['id'])) { echo $ginitials; } ?>" required>
</div>     
<div class="single">
    <?php 
    if(isset($_GET['id'])){
        echo '<button type="submit" onclick="myValidation()" class="woocommerce-Button button" name="edit" value="Edit changes">Save Changes</button>';
    } else {
        echo '<button type="submit" onclick="myValidation()" class="woocommerce-Button button" name="submit" value="Save changes">Add Camper</button>';

    }?>

</div>
</form>

<!-- form end -->
<?php
}
add_action( 'woocommerce_account_add-camper_endpoint', 'booking_add_camper_content' );