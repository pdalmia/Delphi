<div class="content">
  <!-- BEGIN LOGIN FORM -->
  <form name="form" id="login" class="login-form" action="" method="post" autocomplete="off">
    <h3 class="form-title">Forgot password</h3>
    <?php
    $message = $this->session->flashdata('message');
    if($message){?>
      <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        <span><?php echo $message;?>. </span>
      </div>
    <?php }?>
    <div class="form-group">
      <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
      <label class="control-label visible-ie8 visible-ie9">Username</label>
      <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="userName" id="userName" value="<?php echo @$_REQUEST['userName'] ?>" autocomplete="off" />
      <div class="error" style="display:none;" id="ERROR_user"><p class="error-block"><span></span></p></div>
    </div>
    <div class="form-actions no-bottom-padding">
      <button type="submit" class="btn btn-success uppercase">Submit</button>
      <div class="other-links"><a href="<?php echo base_url();?>Superadmin/">Login</a></div>
    </div>
  </form>
  <!-- END LOGIN FORM -->
</div>
