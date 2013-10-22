<? $this->load->view('includes/header'); ?>
<? $this->load->view('includes/nav_bar'); ?>

<script type="text/javascript">

$( document ).ready(function() {
	$('#users').dataTable({
    "bPaginate": true,
    "bFilter": true,
    "bSort": true,
    "bInfo": true,
    "iDisplayLength": 5,
    "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
	});
});

</script>

<ol class="breadcrumb">
  <li><a href="<?=base_url()?>admin/index">Clouds</a></li>
  <li><a href="<?=base_url()?>admin/cloud/<?=$cloud['cID']?>">Cloud: <?=$cloud['name']?></a></li>
  <li class="active">Link user</li>
</ol>


 <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">System users</h3>
  </div>
  <div class="panel-body">

  	<?php
		if(empty($users)){
	?>
	<small><em>There is currently no users</em></small>
	<?php	} else { ?>
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-hover" id="users">
		<thead>
          <tr>
            <th>Username</th>
            <th>Service</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Cloud</th>
            <th>Type</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
	<?php	foreach ($users as $row => $value) {
	?>
		<tr>
			<td><?=$value['username']?></td>
			<td><?=$value['service']?></td>
			<td><?=$value['first_name']?></td>
			<td><?=$value['last_name']?></td>
			<td><?=$value['email']?></td>
			<td>#</td>
			<td><?=$value['user_type']?></td>
			<td><a href="<?=base_url()?>admin/adduser/<?=$cloud['cID']?>/<?=$value['userID']?>"><button type="button" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-user"></span> add to cloud</button></a> <a href="<?=base_url()?>admin/viewuser/<?=$value['username']?>"><button type="button" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-primary"></span> view</button></a> <a href="<?=base_url()?>admin/deleteuser/<?=$value['userID']?>"><button type="button" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span> delete</button></a></td>
		</tr>

	<?php } ?>
		</tbody>
	</table>
	<?php } ?>
  </div>
</div>


<? $this->load->view('includes/footer'); ?>