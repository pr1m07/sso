<? $this->load->view('includes/header'); ?>
<? $this->load->view('includes/nav_bar'); ?>

<ol class="breadcrumb">
    <li><a href="<?=base_url()?>users/">Users</a></li>
    <li class="active">Add user</li>
  </ol>

<?php if($errors){ ?>
	<div class="alert alert-block alert-danger">
		<a class="close" data-dismiss="alert" href="#">X</a>
		<h4 class="alert-heading">Error!</h4>
		<?=$errors?>
	</div>
<?php } ?>

<form action="<?=base_url()?>users/new_user" class="well" method="post">
	
	 <legend>Add new user</legend>
	
	<div class="input-group" style="width: 300px">
		<span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
			<input type="text" name="email" class="form-control" placeholder="Email" />
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
			<input type="password" name="password" class="form-control" placeholder="Password" />
	</div>

	<p class="help-block"></p>
	<div class="input-group" style="width: 300px">
		<span class="input-group-addon"><span class="glyphicon glyphicon-repeat"></span></span>
			<input type="password" name="password2" class="form-control" placeholder="Repeat password" />
	</div>

	<p class="help-block"></p>
	<div class="form-group" style="width: 300px">
	        <select class="form-control" style="width:200px" name="type">
	          <option value="0">select user type</option>
	          <option value="admin">admin</option>
	          <option value="user">user</option>
	        </select>
	</div>

	<p class="help-block"></p>
	<p><input type="submit" class="btn btn-primary" value="Add user" /></p>
</form>




<? $this->load->view('includes/footer'); ?>