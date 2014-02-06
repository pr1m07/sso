<? $this->load->view('includes/header'); ?>
<? $this->load->view('includes/nav_bar'); ?>
  
<script type="text/javascript">

$(document).ready(function() {
  document.getElementById("<?=$cloud['type']?>").style.display = "block";
});

function selectCheck(nameSelect){
   var val = nameSelect.options[nameSelect.selectedIndex].value;
   document.getElementById("OpenStack").style.display = val == 'OpenStack' ? "block" : 'none';
   document.getElementById("VMware").style.display = val == 'VMware' ? "block" : 'none';
}

</script>

  <ol class="breadcrumb">
    <li><a href="<?=base_url()?>admin/">Clouds</a></li>
    <li class="active">Cloud settings</li>
  </ol>

  <p class="help-block"><br/></p>

  <div class="pull-right">
    <a href="<?=base_url()?>admin/linkuser/<?=$cloud['cID']?>"><button type="button" class="btn btn-success">Add user</button></a>
    <a href="<?=base_url()?>admin/cloud/<?=$cloud['cID']?>"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> View</button></a>
  </div>

  <ul class="nav nav-tabs">
   <li class="active"><a href="#home" data-toggle="tab">Cloud settings</a></li>
  </ul>

  <div class="tab-content">
    <div id="home" class="tab-pane active" style="padding-top: 20px; padding-left: 10px;">

      <?php if($success==1){ ?>
      <div class="alert alert-block alert-success">
        <a class="close" data-dismiss="alert" href="#">X</a>
        <h4 class="alert-heading">Success!</h4>
        This cloud has been updated!
      </div>
      <?php } ?>

    <form action="<?=base_url()?>admin/editcloud/<?=$cloud['cID']?>" method="post">
      <div class="row">
        <div class="col-md-6 left-row" style="display: block">
          <p>Type:
            <div class="form-group" style="width: 300px">
              <select class="form-control" style="width:200px" name="type" onchange="selectCheck(this);">
                <option value="OpenStack" <? echo $cloud['type']=='OpenStack' ? 'selected' : ''; ?>>OpenStack</option>
                <option value="Hyper-V" <? echo $cloud['type']=='Hyper-V' ? 'selected' : ''; ?>>Hyper-V</option>
                <option value="VMware" <? echo $cloud['type']=='VMware' ? 'selected' : ''; ?>>VMware</option>
              </select>
            </div>
          </p>
          <p>Name:<input class="form-control" name="name" type="text" value="<?=$cloud['name']?>" /></p>
          <p>Endpoint:<input class="form-control" name="endpoint" type="text" value="<?=$cloud['endpoint']?>" /></p>
          <p>Dashboard:<input class="form-control" name="dashboard" type="text" value="<?=$cloud['dashboard']?>" /></p>
          <p>Active: <input type="checkbox" name="active" value="<?=$cloud['active']?>" checked></p>
          <input class="btn btn-primary" type="submit" value="Update"/>
        </div>

        <div class="col-md-5 pull-right" style="display:none;" id="OpenStack">
          <p class="pull-right"><img src="<?=base_url()?>assets/img/<?=$cloud['type']?>.png" width="40px"></p>
          <p>Additional info for <span class="text-danger"><?=$cloud['type']?></span></p>
          <p>Admin user:<input class="form-control" name="admin_user" type="text" value="<?=$cloud['admin_user']?>" /></p>
          <p>Admin pass:<input class="form-control" name="admin_pass" type="text" value="<?=$cloud['admin_pass']?>" /></p>
          <p>Admin token:<input class="form-control" name="admin_token" type="text" value="<?=$cloud['admin_token']?>" /></p>
          <p>User tenant:<input class="form-control" name="user_tenant" type="text" value="<?=$cloud['user_tenant']?>" /></p>
        </div>
        
        <div class="col-md-5 pull-right" style="display:none;" id="VMware">
          <p class="pull-right"><img src="<?=base_url()?>assets/img/<?=$cloud['type']?>.png" width="40px"></p>
          <p>Additional info for <span class="text-danger"><?=$cloud['type']?></span></p>
          <p>Admin user:<input class="form-control" name="admin_user" type="text" value="<?=$cloud['admin_user']?>" /></p>
          <p>Admin pass:<input class="form-control" name="admin_pass" type="text" value="<?=$cloud['admin_pass']?>" /></p>
          <p>Admin token (optional):<input class="form-control" name="admin_token" type="text" value="<?=$cloud['admin_token']?>" /></p>
          <p>Organization:<input class="form-control" name="user_tenant" type="text" value="<?=$cloud['user_tenant']?>" /></p>
          <p>SAML Metadata:<textarea class="form-control" name="metadata" rows="10" /><?=$cloud['metadata']?></textarea></p>
        </div>
        
      </div>
    </form>
  </div>
</div> <!-- tab content -->


<? $this->load->view('includes/footer'); ?>
