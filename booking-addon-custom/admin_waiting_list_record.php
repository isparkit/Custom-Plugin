<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css' type='text/css' media='all' />
<link rel='stylesheet' href='https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css' type='text/css' media='all' />
<?php 
global $wpdb;
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if(isset($_GET['yr']) && isset($_GET['id'])){
	$qyr = $_GET['yr'];
	$cid = $_GET['id'];
	?>
	<div class="wrap">
		<?php 
		$waiting_table_name = $wpdb->prefix . 'waiting_list';
		$wc_posts = $wpdb->prefix . 'posts';
		$wc_post_meta = $wpdb->prefix . 'postmeta';
		$tbl_manage_booking = $wpdb->prefix . 'booking_student';
		$wc_user_meta = $wpdb->prefix . 'usermeta'; 


		$countcampsql = "(
		SELECT wl.*, 
		um1.meta_value as parentfname,
		um2.meta_value as parentlname,
		um3.meta_value as parentphone,
		um4.meta_value as parentemail
		FROM $waiting_table_name wl
		LEFT JOIN $wc_user_meta um1 ON um1.user_id = wl.pid 
		LEFT JOIN $wc_user_meta um2 ON um2.user_id = wl.pid 
		LEFT JOIN $wc_user_meta um3 ON um3.user_id = wl.pid 
		LEFT JOIN $wc_user_meta um4 ON um4.user_id = wl.pid 
		WHERE wl.campid = $cid
		AND um1.meta_key = 'afreg_additional_1808'
		AND um2.meta_key = 'afreg_additional_1809'
		AND um3.meta_key = 'afreg_additional_1810'
		AND um4.meta_key = 'billing_email'
	)";

	$resultcountcamp = $wpdb->get_results($countcampsql);
	$camperids = [];
// foreach is inside the data table
	?>

	<h2>Wait List Camp(s)</h2>

	<?php 
	if(isset($_POST['delbutton'])) {
		global $wpdb;
    // $myid = $_POST['data1'];
		$myid = $_POST['myid'];
		$deleted = $wpdb->delete($waiting_table_name, array('id'=>$myid ) );
		if($deleted){
			// echo $message = '<div class="alert alert-danger" role="alert">
			// Record Deleted Successfully!
			// </div>';
			?>
			<script type="text/javascript">
                $('#example222').DataTable().ajax.reload();
                alert('Record Deleted Successfully!');
            </script>
			<?php
			header("location: $actual_link");
		}
		else{
			// echo $message = '<div class="alert alert-warning" role="alert">
			// Not Deleted!
			// </div>';   
			?>
			 <script type="text/javascript">alert('Record not Deleted!');</script>
			<?php  
		}
	} 

	?>	
	<table id="cmptTable" class="table table-striped" style="width:100%">
		<thead> 
			<tr>
				<th>Sr.No.</th>
				<th>Parent Details</th>
				<th>Camper Details</th>
				<th>Notes</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>

			<?php 
			foreach ($resultcountcamp as $resultcountcamp){ 
				$wlid = $resultcountcamp->id;
				$inquirydate = $resultcountcamp->inquirydate;
				$parentfname = $resultcountcamp->parentfname;
				$parentlname = $resultcountcamp->parentlname;
				$parentphone = $resultcountcamp->parentphone;
				$parentemail = $resultcountcamp->parentemail;
				$note = $resultcountcamp->note;
				$wbsid = $resultcountcamp->bsid;

				$campdetsql = "(SELECT * FROM $tbl_manage_booking WHERE id IN($wbsid))";
				$resultcampers = $wpdb->get_results($campdetsql);
				?>
				<tr>
					<td></td>
					<td><p><?php echo $parentfname.' '. $parentlname.'<br>'.$parentphone.'<br><a href="mailto:'.$parentemail.'" blank="_blank">'. $parentemail.'</a></p>'; ?></td>
					<td>
						<?php 
						foreach($resultcampers as $resultcamper){
							$bsid = $resultcamper->id;
							$bsfname = $resultcamper->fname;
							$bslname = $resultcamper->lname;
							$bsbdate = $resultcamper->bdate;
							$bssize = $resultcamper->size;
							
							$bsbdate1 = new DateTime($bsbdate);
							$today = new Datetime(date('y-m-d'));
							$bsage = $today->diff($bsbdate1);
							?>
							<?php echo '<p>[ID: '.$bsid.']<br>'.$bsfname.' '. $bslname.'<br>Age: '.$bsage->y.'<br>Shirt Size: '. $bssize; ?>
						<?php } ?>
					</td>
					<td><p><?php echo $note; ?></p></td>
					<td>
						<form method="post"><input type="hidden" name="myid" value="<?php echo $wlid; ?>">
							<input type="submit" id="delete" name="delbutton" class="btn btn-sm btn-danger" value="Delete" onclick="return confirm('Are you sure you want to Remove?');">
						</form></td>
					</tr>

					<?php
				}
				?>


			</tbody>
			<tfoot>
				<tr>
					<th>Sr.No.</th>
					<th>Parent Details</th>
					<th>Camper Details</th>
					<th>Notes</th>
					<th>Action</th>
				</tr>
			</tfoot>
		</table>
	</div>
	<?php 

} else {
	echo "No Record!";
} ?>

<script type='text/javascript' src='https://code.jquery.com/jquery-3.5.1.js' id='jquery-core-js'></script>
<script type='text/javascript' src='https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js' id='jquery-core-js'></script>
<script type='text/javascript' src='https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js' id='jquery-core-js'></script>
<script type="text/javascript">
	$(document).ready(function() {
            // datatable
		$('#cmptTable').DataTable( {
			"order": [[ 3, "desc" ]],
			"pageLength": 25,

			"fnRowCallback" : function(nRow, aData, iDisplayIndex){
				$("td:first", nRow).html(iDisplayIndex +1);
				return nRow;
			},
		} );
	} );
</script>