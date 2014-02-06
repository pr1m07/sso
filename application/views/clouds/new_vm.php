<? $this->load->view('includes/header'); ?>
<? $this->load->view('includes/nav_bar'); ?>

<?php if($success){ ?>
    <div class="alert alert-block alert-success">
     <a class="close" data-dismiss="alert" href="#">X</a>
     <h4 class="alert-heading">Success!</h4>
       <?=$success?>
    </div>
<?php } ?>

  <ol class="breadcrumb">
    <li><a href="<?=base_url()?>admin/index">Clouds</a></li>
    <li><a href="<?=base_url()?>clouds/cloud/<?=$cloud['cID']?>">Cloud: <?=$cloud['name']?></a></li>
    <li class="active">New Virtual Machine</li>
  </ol>

  <ul class="nav nav-tabs">
   <li class="active"><a href="#home" data-toggle="tab">VM settings</a></li>
  </ul>
  
  <div class="tab-content">
    <div id="home" class="tab-pane active" style="padding-top: 20px; padding-left: 10px;">
	
      <form action="<?=base_url()?>clouds/new_vm/<?=$cloud['cID']?>" method="post">
        <div class="row">
          <div class="col-md-6 left-row" style="display: block"> 
            <p>Name:<input class="form-control" name="name" type="text" /></p>
            <p>Image:
              <div class="form-group" style="width: 300px">
                <select class="form-control" style="width:200px" name="image">
                  <option value="0">select image</option>
                  <? foreach($imlist as $image) { ?>
                  <option value="<?=$image->id?>"><?=$image->name?></option>
				  <?php } ?>
                </select>
              </div>
            </p>
            
            <p>Flavor:
              <div class="form-group" style="width: 300px">
                <select class="form-control" style="width:200px" name="flavor">
                  <option value="0">select flavor</option>
                  <? foreach($flist as $flavor) { ?>
                  <option value="<?=$flavor->id?>"><?=$flavor->name?></option>
				  <?php } ?>
                </select>
              </div>
            </p>
            
            <input class="btn btn-primary" type="submit" value="Create virtual machine"/>
          </div>
          
        </div>
    </form>
  </div>
</div> <!-- tab content -->


<? $this->load->view('includes/footer'); ?>
