<? $this->load->view('includes/header'); ?>
<? $this->load->view('includes/nav_bar'); ?>

<?php if($success){ ?>
    <div class="alert alert-block alert-success">
     <a class="close" data-dismiss="alert" href="#">X</a>
     <h4 class="alert-heading">Success!</h4>
       <?=$success?>
    </div>
<?php } ?>

<? $this->load->view('includes/footer'); ?>
