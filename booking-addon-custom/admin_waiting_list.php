<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css' type='text/css' media='all' />
<link rel='stylesheet' href='https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css' type='text/css' media='all' />
<style type="text/css">
    .mybtn{
    border: 1px solid #2271b1;
    padding: 10px 20px;
    text-decoration: none;
    color: #fff;
    background: #2271b1;
    margin: 5px;
    display: table;
    border-radius: 2px;
}
    .mybtn:hover, .mybtn:active{
    border: 1px solid #2271b1;
    padding: 10px 20px;
    text-decoration: none;
    color: #2271b1;
    background: #fff;
    margin: 5px;
    display: table;
    border-radius: 2px;
}   
</style>
<div class="wrap">
    <?php $adminurl = get_admin_url(); ?>
    <a class="mybtn" href="<?php echo $adminurl; ?>edit.php?post_type=product&amp;page=waitinglistFunc&amp;yr=2023">2023 Wait List</a>
</div>