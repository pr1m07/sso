<? $this->load->view('includes/header'); ?>
<? $this->load->view('includes/nav_bar'); ?>
  
<script type="text/javascript">

$( document ).ready(function() {
	$('#clouds').dataTable({
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
		<a href="<?=base_url()?>admin/new_cloud"><button type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-cloud"></span> Add cloud</button></a>
	</div>
  <div class="panel-heading">
  	<h3 class="panel-title">Clouds</h3>
  </div>

  <div class="panel-body">
	<table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-striped" id="clouds">

		<?php if(empty($clouds)){ ?>
				<p class="help-block"></p>
				<p style="padding-left:10px">There is currently no clouds</p>
		<?php } else { ?>

		<thead>
          <tr>
          	<th></th>
            <th>Type</th>
            <th>Title</th>
            <th>Username</th>
            <th>Date</th>
            <th>Active</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
	<?php	foreach ($clouds as $row) {
	?>
		<tr>
			<td><img src="<?=base_url()?>assets/img/<?=$row['type']?>.png" width="20px"></td>
			<td><?=$row['type']?></td>
			<td><?=$row['name']?></td>
			<td><?=$row['username']?></td>
			<td><?=strftime('%d-%m-%Y', strtotime($row['date']))?></td>
			<td><?php echo $row['active']==1 ? "yes" : "no"; ?></td>
			<td><a href="<?=base_url()?>admin/editcloud/<?=$row['cID']?>"><button type="button" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-pencil"></span> edit</button></a> <a href="<?=base_url()?>admin/linkuser/<?=$row['cID']?>"><button type="button" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-user"></span> link user</button></a> <a href="<?=base_url()?>admin/cloud/<?=$row['cID']?>"><button type="button" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> view</button></a> <a href="<?=$row['endpoint']?>"><button type="button" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-cloud"></span> login</button></a> <a href="<?=base_url()?>admin/deletecloud/<?=$row['cID']?>"><button type="button" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span> delete</button></a></td>
		</tr>

	<?php } ?>
		</tbody>
	</table>
	<?php } ?>
 </div>
</div>

<? $this->load->view('includes/footer'); ?>