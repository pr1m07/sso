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

<div class="panel panel-default">
	<div class="btn-group btn-group-xs pull-right" style="padding-top:5px; padding-right:10px;">
		<a href="<?=base_url()?>users/new_user"><button type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-user"></span> Add user</button></a>
	</div>
  <div class="panel-heading">
  	<h3 class="panel-title">Users</h3>
  </div>
   <div class="panel-body">
  <?php
		if(empty($users)){
	?>
	<small><em>There is currently no users</em></small>
	<?php	} else { ?>
	<table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-striped" id="users">
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
			<td><a href="<?=base_url()?>users/edituser/<?=$value['userID']?>"><button type="button" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-pencil"></span> edit</button></a> <a href="<?=base_url()?>users/profile/<?=$value['userID']?>" target="_blank"><button type="button" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-primary"></span> view</button></a> <a href="<?=base_url()?>users/delete/<?=$value['userID']?>"><button type="button" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span> delete</button></a></td>
		</tr>

	<?php } ?>
		</tbody>
	</table>
	<?php } ?>
	</div>
</div>

<? $this->load->view('includes/footer'); ?>