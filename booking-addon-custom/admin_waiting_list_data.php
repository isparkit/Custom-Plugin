<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css' type='text/css' media='all' />
<link rel='stylesheet' href='https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css' type='text/css' media='all' />
<?php 
 global $wpdb;
if(isset($_GET['yr'])){
	$qyr= $_GET['yr'];
}
?>
<div class="wrap">
<?php 
$argscamps = array(
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
            // 'operator' => 'NOT IN'
        ),
        array(
            'taxonomy'     => 'product_cat',
            'field'        => 'name',
            'terms'        => '2023'
        ),

    ),
);
$loopcamps = new WP_Query( $argscamps );
?>

	<h2>Wait List Camp(s)</h2>
	<table id="cmptTable" class="table table-striped" style="width:100%">
		<thead> 
			<tr>
				<th>ID</th>
				<th>Camp Name</th>
				<th>Count</th>
				<th>Details</th>
			</tr>
		</thead>
		<tbody>
<?php 
 while ( $loopcamps->have_posts() ) : $loopcamps->the_post();
    global $product;
    $wcampids = get_the_ID();
     ?>
			<tr>
				<td><?php echo $wcampids; ?></td>
				<td><?php echo get_the_title(); ?></td>
				<td>
					<?php 
					$waiting_table_name = $wpdb->prefix . 'waiting_list';				
				    $countcampsql = "(SELECT campid FROM $waiting_table_name WHERE campid = $wcampids)";
				    $resultcountcamp = $wpdb->get_results($countcampsql);
				    $campcount = count($resultcountcamp);
				   	echo $campcount;
   
 ?>
				</td>
				<td><a href="edit.php?post_type=product&page=waitinglistRecord&id=<?php echo $wcampids; ?>&yr=<?php echo $qyr; ?>" class="btn btn-primary">More Details</a></td>
			</tr>
<?php endwhile; ?>			
		</tbody>
		<tfoot>
			<tr>
				<th>ID</th>
				<th>Camp Name</th>
				<th>Count</th>
				<th>Details</th>
			</tr>
		</tfoot>
	</table>
</div>
<script type='text/javascript' src='https://code.jquery.com/jquery-3.5.1.js' id='jquery-core-js'></script>
<script type='text/javascript' src='https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js' id='jquery-core-js'></script>
<script type='text/javascript' src='https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js' id='jquery-core-js'></script>
<script type="text/javascript">
	$(document).ready(function() {
            // datatable
		$('#cmptTable').DataTable( {
			// "order": [[ 3, "desc" ]],
			"pageLength": 25,

rowCallback: function( row, data, index ) {
    if (data['2'] <= 0) {
        $(row).hide();
    }
},

		} );
	} );
</script>