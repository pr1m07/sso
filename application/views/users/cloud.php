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

	<div class="panel panel-info">
		<div class="panel-heading">
      <h2><span class="glyphicon glyphicon-cloud"></span> <?=$cloud['name']?></h2>
    </div>
			<div class="panel-body">

				<ol class="breadcrumb">
				  <li>Cloud added on: <a class="text-info" href="#"><?=strftime('%d/%m/%Y', strtotime($cloud['date']))?></a></li>
          <a href="<?=base_url()?>users/auth/<?=$cloud['cID']?>/<?=$this->session->userdata('userID')?>" target="_blank" class="pull-right"><span class="glyphicon glyphicon-cloud"></span> Dashboard login</a>
				</ol>

        <p class="pull-right"><img src="<?=base_url()?>assets/img/<?=$cloud['type']?>.png" width="100px"></p>

        <p><span class="glyphicon glyphicon-dashboard"></span> Dashboard: <span class="text-info"><?=$cloud['dashboard']?></span></p>
        <p class="help-block" style="padding-bottom:4px"></p>
        <h3>Your cloud user information:</h3>
        <p class="help-block"></p>
        <span class="glyphicon glyphicon-envelope"></span> Email: <span class="text-info"><?=$cuser->user->email?></span>
        &nbsp;&nbsp;<span class="glyphicon glyphicon-envelope"></span> User id: <span class="text-info"><?=$cuser->user->id?></span></p>
        <p><span class="glyphicon glyphicon-user"></span> Username: <span class="text-info"><?=$cuser->user->name?></span>
        &nbsp;&nbsp;<span class="glyphicon glyphicon-record"></span> Tenant: <span class="text-info"><?=$cuser->user->tenantId?></span></p>
		</div>
	</div>

  <div class="panel panel-default">

    <div class="btn-group btn-group-xs pull-right" style="padding-top:5px; padding-right:10px;">
      <a href="#"><button type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-cog"></span> Add virtual machine</button></a>
    </div>

    <div class="panel-heading">
      <h3 class="panel-title">Virtual machines</h3>
    </div>

    <div class="panel-body">

      <?php if(empty($virtual_machines)){ ?>
        <small><em>There is currently no virtual machines.</em></small>
      <?php } ?>

    </div>

  </div>

  


<? $this->load->view('includes/footer'); ?>