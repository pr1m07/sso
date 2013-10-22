<? $this->load->view('includes/header'); ?>
<? $this->load->view('includes/nav_bar'); ?>

<?php if($errors){ ?>
	<div class="alert alert-block alert-danger">
		<a class="close" data-dismiss="alert" href="#">X</a>
		<h4 class="alert-heading">Error!</h4>
		<?=$errors?>
	</div>
<?php } ?>

<form action="<?=base_url()?>users/register" class="well" method="post">
	
	 <legend>Sign Up</legend>
	<div class="row">
		<div class="col-md-6 left-row" style="display: block; ">
			<div class="input-group" style="width: 300px">
				<span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
				<input type="text" name="email" class="form-control" placeholder="Your Email" />
			</div>

			<p class="help-block"></p>
			<div class="input-group" style="width: 300px">
				<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
				<input type="text" name="first_name" class="form-control" placeholder="First Name" />
			</div>

			<p class="help-block"></p>
			<div class="input-group" style="width: 300px">
				<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
				<input type="text" name="last_name" class="form-control" placeholder="Last Name" />
			</div>

			<p class="help-block"></p>
			<div class="input-group" style="width: 300px">
				<span class="input-group-addon"><span class="glyphicon glyphicon-eye-open"></span></span>
				<input type="text" name="username" class="form-control" placeholder="Username" />
			</div>

			<p class="help-block"></p>
			<div class="input-group" style="width: 300px">
				<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
				<input type="password" name="password" class="form-control" placeholder="New Password" />
			</div>

			<p class="help-block"></p>
			<div class="input-group" style="width: 300px">
				<span class="input-group-addon"><span class="glyphicon glyphicon-repeat"></span></span>
				<input type="password" name="password2" class="form-control" placeholder="Repeat password" />
			</div>

			<p class="help-block"></p>

			<p><input type="submit" class="btn btn-primary" value="Register" /></p>
		</div>
		<div class="col-md-6" style="display: block;">
			<p class="help-block">You can also sign in using your social account</p>
			<?php if($settings[0]!=NULL && $settings[1]!=NULL) { ?>
			<a href="<?=base_url()?>users/session/facebook" class="btn btn-facebook"><i class="icon-facebook"></i> | Connect with Facebook</a>
			<?php } elseif($settings[2]!=NULL && $settings[3]!=NULL) { ?>
			<p class="help-block"></p>
			<a href="<?=base_url()?>users/session/github" class="btn btn-github"><i class="icon-github"></i> | Connect with Github&nbsp;&nbsp;&nbsp;</a>
			<?php } elseif($settings[3]!=NULL && $settings[4]!=NULL) { ?>
			<p class="help-block"></p>
			<a href="<?=base_url()?>users/session/google" class="btn btn-google-plus"><i class="icon-google-plus"></i> | Connect with Google+</a>
			<?php } ?>
		</div>
	</div>
</form>




<? $this->load->view('includes/footer'); ?>