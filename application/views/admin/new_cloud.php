<? $this->load->view('includes/header'); ?>
<? $this->load->view('includes/nav_bar'); ?>

<script type="text/javascript">

function selectCheck(nameSelect){
   var val = nameSelect.options[nameSelect.selectedIndex].value;
   document.getElementById("OpenStack").style.display = val == 'OpenStack' ? "block" : 'none';
}

</script>

  <ol class="breadcrumb">
    <li><a href="<?=base_url()?>admin/index">Clouds</a></li>
    <li class="active">New cloud</li>
  </ol>

  <ul class="nav nav-tabs">
   <li class="active"><a href="#home" data-toggle="tab">Cloud settings</a></li>
  </ul>

  <div class="tab-content">
    <div id="home" class="tab-pane active" style="padding-top: 20px; padding-left: 10px;">

      <form action="<?=base_url()?>admin/new_cloud" method="post">
        <div class="row">
          <div class="col-md-6 left-row" style="display: block"> 
            <p>Type:
              <div class="form-group" style="width: 300px">
                <select class="form-control" style="width:200px" name="type" onchange="selectCheck(this);">
                  <option value="0">select type</option>
                  <option value="OpenStack">OpenStack</option>
                  <option value="Hyper-V">Hyper-V</option>
                  <option value="VMware">VMware</option>
                </select>
              </div>
            </p>
            <p>Name:<input class="form-control" name="name" type="text" /></p>
            <p>Endpoint:<input class="form-control" name="endpoint" type="text" /></p>
            <p>Dashboard:<input class="form-control" name="dashboard" type="text" /></p>
            <input class="btn btn-primary" type="submit" value="Save"/>
          </div>

          <div class="col-md-5 pull-right" style="display:none;" id="OpenStack">
            <p class="pull-right"><img src="<?=base_url()?>assets/img/OpenStack.png" width="40px"></p>
            <p>Additional info for <span class="text-danger">OpenStack</span></p>
            <p>Admin user:<input class="form-control" name="admin_user" type="text" /></p>
            <p>Admin pass:<input class="form-control" name="admin_pass" type="text" /></p>
            <p>Admin token:<input class="form-control" name="admin_token" type="text" /></p>
            <p>User tenant:<input class="form-control" name="user_tenant" type="text" /></p>
            <p>Active: <input type="checkbox" name="active" value="1" checked></p>
          </div>
        </div>
    </form>
  </div>
</div> <!-- tab content -->


<? $this->load->view('includes/footer'); ?>
