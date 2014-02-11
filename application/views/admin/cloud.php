<? $this->load->view('includes/header'); ?>
<? $this->load->view('includes/nav_bar'); ?>

<script type="text/javascript">

$(document).ready(function() {

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
  <li class="active">Cloud: <?=$cloud['name']?></li>
</ol>

<?php if($errors){ ?>
  <div class="alert alert-block alert-danger">
    <a class="close" data-dismiss="alert" href="#">X</a>
    <h4 class="alert-heading">Error!</h4>
    <?=$errors?>
  </div>
<?php } ?>

<?php if($success){ ?>
    <div class="alert alert-block alert-success">
     <a class="close" data-dismiss="alert" href="#">X</a>
     <h4 class="alert-heading">Success!</h4>
       <?=$success?>
    </div>
<?php } ?>

	<div class="panel panel-info">
		<div class="panel-heading">
      <h2><span class="glyphicon glyphicon-cloud"></span> <?=$cloud['name']?></h2>
    </div>
			<div class="panel-body">

				<ol class="breadcrumb">
				  <li>Cloud added on: <a class="text-info" href="#"><?=strftime('%d/%m/%Y', strtotime($cloud['date']))?></a></li>
          <li>Number of users: <a class="text-info" href="#"><!--?=$rcounter?--></a></li>
          <a href="<?=base_url()?>admin/auth/<?=$cloud['cID']?>/<?=$this->session->userdata('userID')?>" target="_blank" class="pull-right"><span class="glyphicon glyphicon-cloud"></span> Dashboard login</a>
				</ol>

        <p class="pull-right"><img src="<?=base_url()?>assets/img/<?=$cloud['type']?>.png" width="100px"></p>
				<p><span class="glyphicon glyphicon-lock"></span> Endpoint: <span class="text-info"><?=$cloud['endpoint']?></span></p>
        <p><span class="glyphicon glyphicon-dashboard"></span> Dashboard: <span class="text-info"><?=$cloud['dashboard']?></span></p>
        <p class="help-block"><br/></p>
        <span class="glyphicon glyphicon-user"></span> Admin user: <span class="text-info"><?=$cloud['admin_user']?></span>
        &nbsp;&nbsp;<span class="glyphicon glyphicon-circle-arrow-right"></span> Admin token: <span class="text-info"><?=$cloud['admin_token']?></span>
        <?php if($cloud['type'] == 'OpenStack') { ?>&nbsp;&nbsp;<span class="glyphicon glyphicon-record"></span> User tenant: <span class="text-info"><?=$cloud['user_tenant']?></span><?php } else { ?> &nbsp;&nbsp;<span class="glyphicon glyphicon-record"></span> Organization: <span class="text-info"><?=$cloud['user_tenant']?></span>
        <?php } ?>
        </p>
		</div>
	</div>

  <div class="panel panel-default">          
    <div class="panel-heading">
      <h3 class="panel-title">Cloud info</h3>
    </div>

    <div class="panel-body"><small><em>Additional information</em></small>

    </div>
  </div>

  <div class="panel panel-default">
  <div class="btn-group btn-group-xs pull-right" style="padding-top:5px; padding-right:10px;">
    <a href="<?=base_url()?>admin/linkuser/<?=$cloud['cID']?>"><button type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-user"></span> Link user</button></a>
  </div>
  <div class="panel-heading">
    <h3 class="panel-title">Cloud Users</h3>
  </div>
  <div class="panel-body">
  <?php
    if(empty($users)){
  ?>
  <small><em>There is currently no users</em></small>
  <?php } else { ?>
  <table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-striped" id="users">
    <thead>
          <tr>
            <th class="header" style="width: 200px">Username</th>
            <th>Email</th>
            <th>Type</th>
            <th>Enabled</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
  <?php foreach ($users as $row => $value) {
  ?>
    <tr>
      <td><?=$value['username']?></td>
      <td><?=$value['email']?></td>
      <td><?=$value['user_type']?></td>
      <td><?=$value['enabled']==1 ? 'yes' : 'no'; ?></td>
      <td>
        <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal_<?=$value['pID']?>"><span class="glyphicon glyphicon-bullhorn"></span> more info</button>
        <a href="<?=base_url()?>admin/deleteuser/<?=$value['cID']?>/<?=$value['ucID']?>"><button type="button" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span> delete</button></a>
      </td>
    </tr>

  <?php } ?>
    </tbody>
  </table>
  <?php } ?>
  </div>
  </div>

  <!-- modal view -->
 <?php foreach($users as $u) { ?>
  <div class="modal fade" id="myModal_<?=$u['pID']?>">
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><?=$u['username'].' cloud profile'; ?></h4>
        </div>
        <div class="modal-body">
          <?php foreach ($cinfo[$u['userID']] as $res) { ?>
          <form action="#" class="well" >
            
            <p class="help-block"></p>
            <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
              <input type="text" name="username" class="form-control" value="<?=$res->name?>" readonly/>
            </div>
            
            <?php if($cloud['type'] == 'OpenStack') { ?>
            <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
              <input type="text" name="email" class="form-control" value="<?=$res->email?>" readonly/>
            </div>
			<?php } else { ?>
			
			<p class="help-block"></p>
			<div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
              <input type="text" name="role" class="form-control" value="<?=$res->role?>" readonly/>
              <span class="input-group-addon"><em><small>&nbsp; role &nbsp;</small></em></span>
            </div>
            
			<p class="help-block"></p>            
            <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-cloud"></span></span>
              <input type="text" name="StoredVmQuota" class="form-control" value="<?=$res->StoredVmQuota?>" readonly/>
              <span class="input-group-addon"><em><small>&nbsp; StoredVmQuota &nbsp;</small></em></span>
            </div>
            
            <p class="help-block"></p>
            <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-cloud"></span></span>
              <input type="text" name="DeployedVmQuota" class="form-control" value="<?=$res->DeployedVmQuota?>" readonly/>
              <span class="input-group-addon"><em><small>&nbsp; DeployedVmQuota &nbsp;</small></em></span>
            </div>
			
			<?php } ?>

            <p class="help-block"></p>
            <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-record"></span></span>
              <input type="text" name="tenant" class="form-control" value="<?=$res->tenantId?>" readonly/>
              <span class="input-group-addon"><em><small>tenant id</small></em></span>
            </div>

            <p class="help-block"></p>
            <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-magnet"></span></span>
              <input type="text" name="uID" class="form-control" value="<?=$res->id?>" readonly/>
              <span class="input-group-addon"><em><small>&nbsp; user id &nbsp;</small></em></span>
            </div>
          </form>
          <? } ?>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>
<?php } ?>
  <!-- end modal view -->

<? $this->load->view('includes/footer'); ?>