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
  
$("button").click(function() {
    var $btn = $(this);
    $btn.button('loading');
    
    setTimeout(function () {
        $btn.button('reset');
    }, 2000);
    
    $('#myModal').modal();
    setTimeout(function() { 
    	window.location.href='<?=base_url()?>clouds/new_vm/<?=$cloud['cID']?>';
	}, 1000);
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
        &nbsp;&nbsp;<span class="glyphicon glyphicon-envelope"></span> User id: <span class="text-info"><?=$cuser->user->ucID?></span></p>
        <p><span class="glyphicon glyphicon-user"></span> Username: <span class="text-info"><?=$cuser->user->username?></span>
        <?php if($cloud['type'] == 'OpenStack') { ?>&nbsp;&nbsp;<span class="glyphicon glyphicon-record"></span> Tenant: <span class="text-info"><?=$cuser->user->tenant?></span></p><?php } else { ?> &nbsp;&nbsp;<span class="glyphicon glyphicon-record"></span> Organization: <span class="text-info"><?=$cuser->user->tenant?></span></p>
        <?php } ?>
		</div>
	</div>

  <div class="panel panel-default">

    <div class="btn-group btn-group-xs pull-right" style="padding-top:5px; padding-right:10px;">
      <button type="button" data-loading-text="Loading..." class="btn btn-primary btn-sm" ><span class="glyphicon glyphicon-cog"></span> Add virtual machine</button>
    </div>

    <div class="panel-heading">
      <h3 class="panel-title">Virtual machines</h3>
    </div>

    <div class="panel-body">

      <?php if(empty($virtual_machines)){ ?>
        <small><em>There is currently no virtual machines.</em></small>
      <?php } else { ?>
  <table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-striped" id="users">
    <thead>
          <tr>
            <th class="header" style="width: 400px">vmID</th>
            <th>Name</th>
            <!--th>Image</th-->
            <th>Flavor</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
  <?php foreach ($virtual_machines as $row => $value) {
  ?>
    <tr>
      <td><?=$value['rvmID']?></td>
      <td><?=$value['name']?></td>
      <!--td><?=$value['image']?></td-->
      <td><?=$value['flavor']?></td>
      <td>
        <button type="button" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-bullhorn"></span> more info</button>
        <button type="button" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span> delete</button>
      </td>
    </tr>

  <?php } ?>
    </tbody>
  </table>
  <?php } ?>

    </div>

  </div>
  
  	
  	
	<div id="myModal" class="modal fade bs-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-sm">
	    <div class="modal-content">
	
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
	          <h4 class="modal-title" id="mySmallModalLabel">Processing...</h4>
	        </div>
	        <div class="modal-body">
				<div class="progress progress-striped active">
				  <div class="progress-bar"  role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
				  </div>
				</div>	
            </div>
	      </div>
	  </div>
	</div>

  


<? $this->load->view('includes/footer'); ?>