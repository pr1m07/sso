<? $this->load->view('includes/header'); ?>
<? $this->load->view('includes/nav_bar'); ?>

<?php if($errors){ ?>
	<div class="alert alert-block alert-danger">
		<a class="close" data-dismiss="alert" href="#">X</a>
		<h4 class="alert-heading">Error!</h4>
		<?=$errors?>
	</div>
<?php } ?>

<?php if($success==1){ ?>
    <div class="alert alert-block alert-success">
     <a class="close" data-dismiss="alert" href="#">X</a>
     <h4 class="alert-heading">Success!</h4>
       This user has been updated!
    </div>
<?php } ?>

<form action="<?=base_url()?>users/editprofile/<?=$user->userID?>" class="well" method="post">
	
	 <legend>Edit profile</legend>
	 <div class="row">
		<div class="col-md-5 left-row" style="display: block; ">

			<div class="input-group" style="width: 300px">
				<span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
				<input type="text" name="email" class="form-control" placeholder="Email" value="<?=$user->email;?>" readonly/>
			</div>

			<p class="help-block"></p>
			<div class="input-group" style="width: 300px">
				<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
				<input type="text" name="first_name" class="form-control" placeholder="First Name" value="<?=$user->first_name;?>" />
			</div>

			<p class="help-block"></p>
			<div class="input-group" style="width: 300px">
				<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
				<input type="text" name="last_name" class="form-control" placeholder="Last Name" value="<?=$user->last_name;?>"/>
			</div>

			<p class="help-block"></p>
			<div class="input-group" style="width: 300px">
				<span class="input-group-addon"><span class="glyphicon glyphicon-eye-open"></span></span>
					<input type="text" name="username" class="form-control" placeholder="Username" value="<?=$user->username;?>" />
			</div>

			<p class="help-block"></p>
			<div class="input-group" style="width: 300px">
				<span class="input-group-addon"><span class="glyphicon glyphicon-picture"></span></span>
					<input type="text" name="avatar" class="form-control" placeholder="Avatar URL" value="<?=$user->avatar;?>" />
			</div>

			<p class="help-block"></p>
			<div class="input-group" style="width: 300px">
				<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
					<input type="password" name="password" class="form-control" placeholder="Password" value="" />
			</div>

			<p class="help-block"></p>
			<div class="input-group" style="width: 300px">
				<span class="input-group-addon"><span class="glyphicon glyphicon-repeat"></span></span>
					<input type="password" name="password2" class="form-control" placeholder="Repeat password" value="" />
			</div>

			<p class="help-block"></p>
			<p><input type="submit" class="btn btn-primary" value="Edit user" /></p>

		</div>
		<div class="col-md-3" style="display: block;">
			<a href="#" class="thumbnail" class="thumbnail pull-right">
				<img src="<?=$this->session->userdata('avatar')==NULL ? base_url().'assets/img/noimage.png' : $this->session->userdata('avatar'); ?>" width="250px">
			</a>
		</div>
	</div>
</form>

<? $this->load->view('includes/footer'); ?>