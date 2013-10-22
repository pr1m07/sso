<? $this->load->view('includes/header'); ?>
<? $this->load->view('includes/nav_bar'); ?>

<style type="text/css">
.space {
	padding-bottom: 15px;
}
</style>

 <?php if($success==1){ ?>
 	<div class="alert alert-block alert-success">
 		<a class="close" data-dismiss="alert" href="#">X</a>
 		<h4 class="alert-heading">Success!</h4>
 		Social settings has been updated!
 	</div>
<?php } ?>

<ol class="breadcrumb">
    <li><a href="<?=base_url()?>admin/settings">Settings</a></li>
    <li class="active">Social keys</li>
  </ol>


<form action="<?=base_url()?>admin/settings" class="well" method="post">
	
	 <legend>Edit social keys</legend>

	<div class="input-group" style="width: 400px">
		<span class="input-group-addon"><span class="icon-facebook"></span></span>
			<input type="text" name="fb_id" class="form-control" placeholder="Facebook App ID" value="<?=$settings[0]['data'];?>" />
	</div>
	<p class="help-block"></p>

	<div class="input-group" style="width: 400px">
		<span class="input-group-addon"><span class="icon-facebook"></span></span>
			<input type="text" name="fb_key" class="form-control" placeholder="Facebook Secret KEY" value="<?=$settings[1]['data'];?>" />
	</div>
	<p class="help-block space"></p>


	<div class="input-group" style="width: 400px">
		<span class="input-group-addon"><span class="icon-github"></span></span>
			<input type="text" name="git_id" class="form-control" placeholder="Git Client ID" value="<?=$settings[2]['data'];?>" />
	</div>
	<p class="help-block"></p>

	<div class="input-group" style="width: 400px">
		<span class="input-group-addon"><span class="icon-github"></span></span>
			<input type="text" name="git_key" class="form-control" placeholder="Git Secret KEY" value="<?=$settings[3]['data'];?>" />
	</div>
	<p class="help-block space"></p>


	<div class="input-group" style="width: 400px">
		<span class="input-group-addon"><span class="icon-google-plus"></span></span>
			<input type="text" name="go_id" class="form-control" placeholder="Google Client ID" value="<?=$settings[4]['data'];?>" />
	</div>
	<p class="help-block"></p>

	<div class="input-group" style="width: 400px">
		<span class="input-group-addon"><span class="icon-google-plus"></span></span>
			<input type="text" name="go_key" class="form-control" placeholder="Google Client KEY" value="<?=$settings[5]['data'];?>" />
	</div>

	<p class="help-block"></p>
	<p><input type="submit" class="btn btn-primary" value="Save" /></p>

</form>




<? $this->load->view('includes/footer'); ?>