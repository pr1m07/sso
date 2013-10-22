<? $this->load->view('includes/header'); ?>
<? $this->load->view('includes/nav_bar'); ?>

<form action="#" class="well" >
	
	 <legend><?=$user->username.' profile'; ?></legend>
	 <div class="row">
		<div class="col-md-5 left-row" style="display: block; ">

			<div class="input-group" style="width: 300px">
				<span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
				<input type="text" name="email" class="form-control" value="<?=$user->email;?>" readonly/>
			</div>

			<p class="help-block"></p>
			<div class="input-group" style="width: 300px">
				<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
				<input type="text" name="first_name" class="form-control" value="<?=$user->first_name;?>" readonly/>
			</div>

			<p class="help-block"></p>
			<div class="input-group" style="width: 300px">
				<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
				<input type="text" name="last_name" class="form-control" value="<?=$user->last_name;?>" readonly/>
			</div>

			<p class="help-block"></p>
			<div class="input-group" style="width: 300px">
				<span class="input-group-addon"><span class="glyphicon glyphicon-eye-open"></span></span>
					<input type="text" name="username" class="form-control" value="<?=$user->username;?>" readonly/>
			</div>

			<p class="help-block"></p>
		</div>
		<div class="col-md-3" style="display: block;">
			<a href="#" class="thumbnail" class="thumbnail pull-right">
				<img src="<?=$this->session->userdata('avatar')==NULL ? base_url().'assets/img/noimage.png' : $this->session->userdata('avatar'); ?>" width="250px">
			</a>
		</div>
	</div>
</form>

<? $this->load->view('includes/footer'); ?>