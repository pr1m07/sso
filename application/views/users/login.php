<? $this->load->view('includes/header'); ?>
<? $this->load->view('includes/nav_bar'); ?>

<style type="text/css">
.left-row {
	border-right: 1px solid #e5e5e5;
}
</style>

<?php if($error==1){ ?>
	<div class="alert alert-block alert-danger">
		<a class="close" data-dismiss="alert" href="#">X</a>
		<h4 class="alert-heading">Error!</h4>
		Your username / password did not match!
	</div>
<?php } ?>

<form action="<?=base_url()?>users/login"  class="well" method="post">

	<legend>Login</legend>
	<div class="row">
		<div class="col-md-6 left-row" style="display: block; ">	
			<div class="input-group" style="width: 250px">
				<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
				<input type="text" name="username" class="form-control" placeholder="Username" />
			</div>

			<p class="help-block"></p>
			<div class="input-group" style="width: 250px">
				<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
				<input type="password" name="password" class="form-control" placeholder="Password" />
			</div>

			<p class="help-block"></p>
			<p><input type="submit" class="btn btn-info" value="Login" /></p>
		</div>
		<div class="col-md-6" style="display: block;">
			<p class="help-block">You can also sign in using your social account</p>
			<?php if($settings[0]['data']!=NULL && $settings[1]['data']!=NULL) { ?>
			<a href="<?=base_url()?>users/session/facebook" class="btn btn-facebook"><i class="icon-facebook"></i> | Connect with Facebook</a>
			<?php } if($settings[2]['data']!=NULL && $settings[3]['data']!=NULL) { ?>
			<p class="help-block"></p>
			<a href="<?=base_url()?>users/session/github" class="btn btn-github"><i class="icon-github"></i> | Connect with Github&nbsp;&nbsp;&nbsp;</a>
			<?php } if($settings[4]['data']!=NULL && $settings[5]['data']!=NULL) { ?>
			<p class="help-block"></p>
			<a href="<?=base_url()?>users/session/google" class="btn btn-google-plus"><i class="icon-google-plus"></i> | Connect with Google+</a>
			<p class="help-block"></p>
			<a href="#" class="btn btn-google-plus"><i class=""></i>Connect with Stork</a>
			<?php } ?>
		</div>
	</div>

</form>



<? $this->load->view('includes/footer'); ?>