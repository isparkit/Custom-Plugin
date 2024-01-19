<?php
if ( is_admin() ) {
    add_action( 'admin_menu', 'add_products_menu_entry', 100 );
}
function add_products_menu_entry() {
    add_submenu_page(
        'edit.php?post_type=product',
        __( 'Camper' ),
        __( 'All Campers' ),
'manage_woocommerce', // Required user capability
'camper-product',
'all_camper_page'
);
}
function all_camper_page() {
    ?>
<!--     <div class="table-wrapper">
    <div class="scrollable"> -->
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css' type='text/css' media='all' />
        <link rel='stylesheet' href='https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css' type='text/css' media='all' />
        <style type="text/css">
            #example222 th, #example222 td {
                font-size: 14px;
            }

.modaldiv{padding: 4px;}
.loading{ background-color:black !important; display:none; }
td.dt-center.editor-edit {padding-top: 17px; cursor: pointer;}
span.editbtn {background: green; padding: 6px 15px;color: #fff; border-radius: 3px; cursor: pointer !important;}
span.editbtn:hover{background: red;}
            
        </style>
        <div class="wrap">
            <div>
                <h3>All Campers</h3>
                <?php $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
                <div>

                   <table id="example222" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Parent</th>
                            <th>Camper</th>
                            <th>Age</th>
                            <th>Shirt</th>
                            <th>EC 1</th>
                            <th>EC 2</th>
                            <th>H</th>
                            <th>Md</th>
                            <th>ADDL</th>
                            <th>Photo</th>
                            <!--  <th>Accepted</th> -->
                            <th>Initials</th>
                            <th>Edit</th>
                          
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        global $wpdb;
                        $db_table_name = $wpdb->prefix . 'booking_student';
                        // $results = $wpdb->get_results( "SELECT * FROM $db_table_name");
                        // 29-03-22
                        $results = $wpdb->get_results( "SELECT * FROM $db_table_name WHERE id IN (SELECT MAX(id) FROM $db_table_name GROUP BY id)");
                       
                        if(!empty($results))
                        {
                            foreach($results as $row){
                                $user_info = get_userdata($row->p_id);
                                $pusername = '<a href="user-edit.php?user_id='.$user_info->ID.'" >'.$user_info->user_email;
                                // echo "<pre>";
                                    // print_r($user_info);
                                ?>
                                <tr><td ><?php echo '#'.$row->id; ?></td>
                                    <td ><?php echo $pusername; ?></td>
                                    <td ><?php echo $row->fname .' '. $row->lname ; ?></td>
                                    <td ><?php // echo $row->bdate ; ?>
                                    <?php
                                    if($row->bdate){
                                        $dateOfBirth = $row->bdate;
                                        $today = date("Y-m-d");
                                        $diff = date_diff(date_create($dateOfBirth), date_create($today));
                                        echo $diff->format('%y');
                                        echo '<br>';
                                        echo $dateOfBirth;
                                    }
                                    ?>
                                </td>
                                <td ><?php echo $row->size; ?></td>
                                <td >
                                    <?php
                                    if($row->pfname || $row->pfname) { echo $row->pfname .' '. $row->plname .'</br>'; }
                                    if($row->relationship) { echo ' ('. $row->relationship .') ' .'</br>'; }
                                    if($row->phone) { echo $row->phone; }   else {
                                                    //replace emergency contact with parent info if missing
                                        if($user_info->afreg_additional_1808 || $user_info->afreg_additional_1808) { echo $user_info->afreg_additional_1808 .' '. $user_info->afreg_additional_1809 .'</br>'; }
                                        if($user_info->afreg_additional_1811) { echo ' ('. $user_info->afreg_additional_1811 .') ' .'</br>'; }
                                        if($user_info->afreg_additional_1810) { echo $user_info->afreg_additional_1810; }  
                                    }  
                                ?></td>
                                <td >
                                    <?php
                                    if($row->p2fname || $row->p2fname) { echo $row->p2fname .' '. $row->p2lname; }
                                    if($row->relationship2) { echo ' ('. $row->relationship2 .') '; }
                                    if($row->phone2) { echo $row->phone2; }    else {
                                        if($user_info->afreg_additional_1815 || $user_info->afreg_additional_1816) { echo $user_info->afreg_additional_1815 .' '. $user_info->afreg_additional_1816 .'</br>'; }
                                        if($user_info->afreg_additional_1818) { echo ' ('. $user_info->afreg_additional_1818 .') ' .'</br>'; }
                                        if($user_info->afreg_additional_1817) { echo $user_info->afreg_additional_1817; }  
                                    }  
                                ?></td>
                                <td ><?php if($row->allergiescheck ) { echo $row->allergies; } ?></td>
                                <td ><?php if($row->medidevicecheck  ) { echo $row->medidevice; } ?></td>
                                <td ><?php if($row->medidevicecheck  ) { echo $row->additional; } ?></td>
                                <td ><?php if($row->photo  ) { echo $row->photo; } ?></td>

                                <td ><?php if($row->initials  ) { echo $row->initials; } ?></td>
                                <td class="dt-center editor-edit"><span class="editbtn">Edit</span></td>
                            </tr>
                            <?php
                        }} ?>
                    </tbody>

                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Parent</th>
                            <th>Camper</th>
                            <th>Age</th>
                            <th>Shirt</th>
                            <th>EC 1</th>
                            <th>EC 2</th>
                            <th>H</th>
                            <th>Md</th>
                            <th>ADDL</th>
                            <th>Photo</th>
                            <!--  <th>Accepted</th> -->
                            <th>Initials</th>
                            <th>Edit</th>
                            
                        </tr>
                    </tfoot>

                </table> 
            </div>
        </div>
    </div>

                  <!-- modal -->
                  <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">
                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Camper Details</h4>
                        </div>
                        <div class="modal-body" id="formdata">
                          <div class="row">
                            <div class="col-md-7">
                              <form class="modal-edit" method="post">
                                <div class="field" id="data1">
                                  <input type="text" name="data1" />
                                </div>
                                <div class="field modaldiv" id="data2">
                                  <label>Date of Birth:</label> <input type="text" name="data2" />
                                </div>
                                <div class="modaldiv">
                                    <input type="submit" name="submit" value="Update" class="btn btn-sm btn-success" />
                                    <!-- <input type="submit" id="delete" name="delbutton" class="btn btn-sm btn-danger" value="Delete" onclick="return confirm('Are you sure you want to Remove?');">  -->
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Cancel</button>                                 
                                </div>
                              </form>      
                            </div>
                           <!--  <div class="col-md-5">
                              <div class="childid"><span></span></div>
                            </div> -->
                          </div>                        
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                      <?php 
                      // updating date of birth
                        global $wpdb;
                      if(isset($_POST['submit'])){
                        $myid = substr($_POST['data1'], 1);
                        $mybdate = $_POST['data2'];
                        $updatequery = $wpdb->query("UPDATE $db_table_name SET bdate = '$mybdate' WHERE id = $myid");          
                        if($updatequery){
                         ?>
                         <script type="text/javascript">
                            $('#example222').DataTable().ajax.reload();
                            alert('Camper Date of Birth Updated!');
                         </script>
                         <?php
header("location: $actual_link");
// this will refresh the page after update

                       }
                       else{
                        ?>
                        <script type="text/javascript">alert('Camper Date of Birth Not Updated!');</script>
                        <?php
                      }
                    }

                        // delete query
                        if(isset($_POST['delbutton'])) {
                            // $myid = $_POST['data1'];
                            $myid = substr($_POST['data1'], 1);
                            // $deleted = $wpdb->delete($db_table_name, array('id'=>$myid ) );
                        if($deleted){
                        ?>
                         <script type="text/javascript">
                            // $('#example222').DataTable().ajax.reload();
                            // alert('Camper Deleted!');
                         </script>
                        <?php                            
                        }
                        else{
?>
                         <script type="text/javascript">
                            // alert('Issue in Deleting!');
                         </script>   
<?php                         
                        }
                        }                     
                    ?>                    
                  </div>
                </div>

<script type='text/javascript' src='https://code.jquery.com/jquery-3.5.1.js' id='jquery-core-js'></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

<!-- Modal -->


    <script type='text/javascript' src='https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js' id='jquery-core-js'></script>
    <script type='text/javascript' src='https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js' id='jquery-core-js'></script>
    <script type="text/javascript">
        
        $(document).ready(function() {
            $('#example222').DataTable( {
                "order": [[ 2, "desc" ]],
                "pageLength": 25,
                "processing": true,
            } );

            var table = $('#example222').DataTable();

            // $('#example222 tbody').on('click', 'tr', function (e) {
            //   $(".modal-body div span").text("");
            //     const mydata = table.row(this).data()[3];
            //     const myArray = mydata.split("<br>");
            //   // $(".childid span").html(table.row(this).data()[5]);
            //   $("#data1 input").val(table.row(this).data()[0]);
            //   $("#data2 input").val(myArray[1]);
            //   $("#myModal").modal("show");
            // });


    $('#example222').on('click', 'td.editor-edit', function (e) {
        e.preventDefault();
        console.log('edit');
        const mydata = table.row(this).data()[3];
        const myArray = mydata.split("<br>");
        $("#data1 input").val(table.row(this).data()[0]);
        $("#data2 input").val(myArray[1]);
        $("#myModal").modal("show");
    } );


        } );
    </script>
    <?php

}
?>