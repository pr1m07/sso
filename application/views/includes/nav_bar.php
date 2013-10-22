<nav class="navbar navbar-default" role="navigation">
  <div class="navbar-header">
    <?php if($this->session->userdata('logged_in')!=TRUE){ ?>
    <a class="navbar-brand" href="<?=base_url()?>users/login"><span class="glyphicon glyphicon-home"></span> <strong>sso class</strong></a>
    <?php } else { ?>
    <a class="navbar-brand" href="<?=base_url()?>admin/index"><span class="glyphicon glyphicon-home"></span> <strong>sso class</strong></a>
      <?php } if($this->session->userdata('user_type')=='admin') { ?>
      <ul class="nav navbar-nav">
      <li class="dropdown">
        <a href="#" class="dropdown-toggle navbar-brand" data-toggle="dropdown">Admin <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="<?=base_url()?>users"><span class="glyphicon glyphicon-user"></span> Users</a></li>
          <li><a href="<?=base_url()?>admin/settings"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
          <li role="presentation" class="divider"></li>
          <li><a href="<?=base_url()?>admin/new_cloud"><span class="glyphicon glyphicon-plus"></span> Add cloud</a></li>
          <li><a href="<?=base_url()?>users/new_user"><span class="glyphicon glyphicon-plus"></span> Add user</a></li>
        </ul>
      </li>
    </ul>
    <?php } ?>

  </div>
  <?php if($this->session->userdata('logged_in')==TRUE){ ?>
    <ul class="nav navbar-nav pull-right">
      <li class="dropdown">
        <a href="#" class="dropdown-toggle navbar-brand" data-toggle="dropdown"><img src="<?=$this->session->userdata('avatar')==NULL ? base_url().'assets/img/avatar.png' : $this->session->userdata('avatar'); ?>" style="padding-right:10px; height:30px; width:40px">Welcome, <?=$this->session->userdata('username')?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="<?=base_url()?>users/editprofile/<?=$this->session->userdata('userID')?>"><span class="glyphicon glyphicon-cog"></span> Preferences</a></li>
          <?php if($this->session->userdata('user_type')!='admin') { ?>
          <li><a href="#"><span class="glyphicon glyphicon-envelope"></span> Contact support</a></li>
          <? } ?>
          <li role="presentation" class="divider"></li>
          <li><a href="<?=base_url()?>users/logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
      </li>
    </ul>

  <?php } else { ?>
    <p class="navbar-brand pull-right"><a href="<?=base_url()?>users/register" class="navbar-link"><span class="glyphicon glyphicon-user"></span> Sign Up</a> | <a href="<?=base_url()?>users/login" class="navbar-link">Login</a></p>
  <?php } ?>
</nav>